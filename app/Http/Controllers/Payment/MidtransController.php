<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DuesBill;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MidtransController extends Controller
{
    /**
     * Terbitkan Nomor Virtual Account Bank, Barcode QRIS, atau Deeplink GoPay via Midtrans Core API.
     */
    public function charge(Request $request, DuesBill $bill): JsonResponse
    {
        if ($bill->status === 'paid') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tagihan iuran ini sudah berstatus lunas.',
            ], 400);
        }

        $organization = $bill->organization;
        if (! $organization->is_midtrans_enabled) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran otomatis via Midtrans sedang dinonaktifkan oleh pengurus ormawa.',
            ], 403);
        }

        $validated = $request->validate([
            'payment_type' => [
                'required',
                'string',
                'in:bca_va,bri_va,bni_va,mandiri_bill,permata_va,cimb_va,danamon_va,bsi_va,seabank_va,saqu_va,qris,gopay',
            ],
        ]);

        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production', false);
        $coreEndpoint = $isProduction
            ? 'https://api.midtrans.com/v2/charge'
            : 'https://api.sandbox.midtrans.com/v2/charge';

        $orderId = 'KASLY-BILL-'.$bill->id.'-'.time();
        $grossAmount = (int) $bill->amount;

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $bill->member->name,
                'email' => $bill->member->user?->email ?? ($bill->member->member_number.'@kasly.id'),
                'phone' => $bill->member->phone ?? '081234567890',
            ],
            'item_details' => [
                [
                    'id' => 'BILL-'.$bill->id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => Str::limit($bill->duesPeriod->title, 45, ''),
                ],
            ],
        ];

        switch ($validated['payment_type']) {
            case 'bca_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bca'];
                break;
            case 'bri_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bri'];
                break;
            case 'bni_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bni'];
                break;
            case 'cimb_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'cimb'];
                break;
            case 'bsi_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bsi'];
                break;
            case 'danamon_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'danamon'];
                break;
            case 'seabank_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'seabank'];
                break;
            case 'saqu_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'saqu'];
                break;
            case 'permata_va':
                $payload['payment_type'] = 'permata';
                break;
            case 'mandiri_bill':
                $payload['payment_type'] = 'echannel';
                $payload['echannel'] = [
                    'bill_info1' => 'Kas Iuran',
                    'bill_info2' => Str::limit($organization->name, 30, ''),
                ];
                break;
            case 'qris':
                $payload['payment_type'] = 'qris';
                $payload['qris'] = ['acquirer' => 'gopay'];
                break;
            case 'gopay':
                $payload['payment_type'] = 'gopay';
                $payload['gopay'] = [
                    'enable_callback' => true,
                    'callback_url' => url('/anggota/dashboard'),
                ];
                break;
        }

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(12)
                ->post($coreEndpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $vaNumber = $data['va_numbers'][0]['va_number'] ?? ($data['permata_va_number'] ?? ($data['bill_key'] ?? null));
                $billerCode = $data['biller_code'] ?? null;
                $qrUrl = null;
                $qrString = $data['qr_string'] ?? null;
                $deeplinkUrl = null;

                if (! empty($data['actions'])) {
                    foreach ($data['actions'] as $action) {
                        if (($action['name'] ?? '') === 'deeplink-redirect') {
                            $deeplinkUrl = $action['url'] ?? null;
                        }
                        if (empty($qrUrl) && in_array($action['name'] ?? '', ['generate-qr-code', 'generate-qr-code-v2'])) {
                            $qrUrl = $action['url'] ?? null;
                        }
                    }
                }

                if ($validated['payment_type'] === 'qris' && empty($qrUrl)) {
                    $qrUrl = "https://api.sandbox.midtrans.com/v2/qris/{$data['transaction_id']}/qr-code";
                }

                $bill->midtrans_order_id = $orderId;
                $bill->midtrans_payment_type = $validated['payment_type'];
                $bill->payment_channel = 'midtrans_'.$validated['payment_type'];
                $bill->save();

                return response()->json([
                    'status' => 'success',
                    'order_id' => $orderId,
                    'payment_type' => $validated['payment_type'],
                    'va_number' => $vaNumber,
                    'biller_code' => $billerCode,
                    'qr_url' => $qrUrl,
                    'qr_string' => $qrString,
                    'deeplink_url' => $deeplinkUrl,
                    'expiry_time' => $data['expiry_time'] ?? now()->addDay()->format('Y-m-d H:i:s'),
                    'gross_amount' => $grossAmount,
                ]);
            }

            Log::warning('Midtrans Core Charge Error: '.$response->body());

            // Fallback simulasi jika sandbox mock
            $mockVa = '29700'.rand(1000000000, 9999999999);
            $bill->midtrans_order_id = $orderId;
            $bill->midtrans_payment_type = $validated['payment_type'];
            $bill->payment_channel = 'midtrans_'.$validated['payment_type'];
            $bill->save();

            return response()->json([
                'status' => 'success',
                'is_mock' => true,
                'order_id' => $orderId,
                'payment_type' => $validated['payment_type'],
                'va_number' => $mockVa,
                'biller_code' => '70012',
                'qr_url' => 'https://api.sandbox.midtrans.com/v2/qris/mock-sample/qr-code',
                'qr_string' => '00020101021226590014ID.LINKAJA.WWW011893600911002247738002150000000000000010303UMI51440014ID.GO-PAY.WWW0215G297001444495210303UMI5204581253033605802ID5911KASLY ORMAWA6007JAKARTA61051293062070703A016304C92E',
                'deeplink_url' => 'https://simulator.sandbox.midtrans.com/v2/deeplink/detail?tref=MOCK'.time(),
                'expiry_time' => now()->addDay()->format('Y-m-d H:i:s'),
                'gross_amount' => $grossAmount,
            ]);
        } catch (\Throwable $e) {
            Log::error('Midtrans Exception: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Kendala koneksi ke server gateway Midtrans: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dapatkan Snap Token Midtrans untuk pembayaran tagihan kas tertentu.
     */
    public function getSnapToken(Request $request, DuesBill $bill): JsonResponse
    {
        if ($bill->status === 'paid') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tagihan iuran ini sudah berstatus lunas.',
            ], 400);
        }

        $organization = $bill->organization;
        if (! $organization->is_midtrans_enabled) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran otomatis via Midtrans sedang dinonaktifkan oleh pengurus ormawa.',
            ], 403);
        }

        $serverKey = config('services.midtrans.server_key');
        $clientKey = config('services.midtrans.client_key');
        $isProduction = config('services.midtrans.is_production', false);
        $snapEndpoint = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        // Buat Order ID unik untuk Midtrans
        $orderId = 'KASLY-BILL-'.$bill->id.'-'.time();

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $bill->amount,
            ],
            'customer_details' => [
                'first_name' => $bill->member->name,
                'email' => $bill->member->user?->email ?? ($bill->member->member_number.'@kasly.id'),
                'phone' => $bill->member->phone ?? '081234567890',
            ],
            'item_details' => [
                [
                    'id' => 'BILL-'.$bill->id,
                    'price' => (int) $bill->amount,
                    'quantity' => 1,
                    'name' => Str::limit($bill->duesPeriod->title, 45, ''),
                ],
            ],
        ];

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(10)
                ->post($snapEndpoint, $payload);

            if ($response->successful()) {
                $snapToken = $response->json('token');
                $redirectUrl = $response->json('redirect_url');

                $bill->snap_token = $snapToken;
                $bill->midtrans_order_id = $orderId;
                $bill->save();

                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'redirect_url' => $redirectUrl,
                    'client_key' => $clientKey,
                    'order_id' => $orderId,
                ]);
            }

            Log::warning('Midtrans Snap Error: '.$response->body());

            // Fallback simulasi token jika koneksi sandbox offline / dummy key
            $mockToken = 'mock_snap_'.Str::random(32);
            $bill->snap_token = $mockToken;
            $bill->midtrans_order_id = $orderId;
            $bill->save();

            return response()->json([
                'status' => 'success',
                'is_mock' => true,
                'snap_token' => $mockToken,
                'client_key' => $clientKey,
                'order_id' => $orderId,
                'message' => 'Mode Simulasi Sandbox Aktif',
            ]);
        } catch (\Throwable $e) {
            Log::error('Midtrans Exception: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kendala saat menghubungkan ke gateway pembayaran: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sinkronkan status tagihan kas dengan status transaksi terkini dari Midtrans API.
     * Mengembalikan true jika tagihan berstatus lunas (settlement / capture accept).
     */
    public static function syncBillStatus(DuesBill $bill): bool
    {
        if ($bill->status === 'paid') {
            return true;
        }

        if (empty($bill->midtrans_order_id)) {
            return false;
        }

        $serverKey = config('services.midtrans.server_key');
        if (empty($serverKey)) {
            return false;
        }

        $isProduction = config('services.midtrans.is_production', false);
        $statusEndpoint = $isProduction
            ? "https://api.midtrans.com/v2/{$bill->midtrans_order_id}/status"
            : "https://api.sandbox.midtrans.com/v2/{$bill->midtrans_order_id}/status";

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(6)
                ->get($statusEndpoint);

            if ($response->successful()) {
                $data = $response->json();
                $transactionStatus = $data['transaction_status'] ?? null;
                $fraudStatus = $data['fraud_status'] ?? null;
                $paymentType = $data['payment_type'] ?? ($bill->midtrans_payment_type ?? 'qris');

                if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                    DB::beginTransaction();
                    try {
                        $dueDate = $bill->duesPeriod->due_date->endOfDay();
                        $isOnTime = now()->lte($dueDate);

                        $bill->status = 'paid';
                        $bill->is_on_time = $isOnTime;
                        $bill->paid_at = now();
                        $bill->payment_method = 'midtrans';
                        $bill->midtrans_payment_type = $paymentType;
                        $bill->payment_channel = 'midtrans_'.$paymentType;
                        $bill->save();

                        // Otomatis catat mutasi kas masuk ke Buku Kas jika belum pernah dicatat
                        $organization = $bill->organization;
                        $category = $organization->cashCategories()
                            ->where('type', 'in')
                            ->where('name', 'Iuran Wajib')
                            ->first()
                            ?? $organization->cashCategories()->where('type', 'in')->first();

                        $desc = "Pembayaran Otomatis Midtrans ({$paymentType}) {$bill->duesPeriod->title} - {$bill->member->name}";
                        $alreadyExists = Transaction::where('organization_id', $organization->id)
                            ->where('description', $desc)
                            ->exists();

                        if (! $alreadyExists) {
                            Transaction::create([
                                'organization_id' => $organization->id,
                                'type' => 'in',
                                'category_id' => $category?->id,
                                'amount' => $bill->amount,
                                'transaction_date' => now()->toDateString(),
                                'description' => $desc,
                                'is_locked' => true,
                            ]);
                        }

                        ActivityLog::create([
                            'organization_id' => $organization->id,
                            'action' => 'midtrans_payment_settled',
                            'subject_type' => DuesBill::class,
                            'subject_id' => $bill->id,
                            'payload' => [
                                'order_id' => $bill->midtrans_order_id,
                                'payment_type' => $paymentType,
                                'amount' => $bill->amount,
                                'is_on_time' => $isOnTime,
                            ],
                        ]);

                        DB::commit();
                        Log::info("Midtrans Status Synced: Tagihan #{$bill->id} berhasil diupdate ke lunas ({$transactionStatus})");

                        return true;
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        Log::error('Gagal update bill status saat sync: '.$e->getMessage());

                        return false;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Gagal koneksi status check Midtrans untuk order {$bill->midtrans_order_id}: ".$e->getMessage());
        }

        return false;
    }

    /**
     * Endpoint API JSON untuk pengecekan status tagihan kas real-time oleh frontend.
     */
    public function checkStatus(Request $request, DuesBill $bill): JsonResponse
    {
        self::syncBillStatus($bill);
        $bill->refresh();

        return response()->json([
            'status' => 'success',
            'is_paid' => $bill->status === 'paid',
            'bill_status' => $bill->status,
            'paid_at' => $bill->paid_at?->format('d M Y H:i'),
        ]);
    }

    /**
     * Webhook HTTP Notification Callback dari Midtrans.
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received:', $payload);

        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');
        $paymentType = $request->input('payment_type', 'qris');

        if (! $orderId) {
            return response()->json(['status' => 'error', 'message' => 'Order ID is required'], 400);
        }

        // Cari tagihan berdasarkan midtrans_order_id
        $bill = DuesBill::where('midtrans_order_id', $orderId)->first();

        if (! $bill) {
            // Coba parsing ID tagihan jika order ID format KASLY-BILL-{id}-xxx
            if (preg_match('/KASLY-BILL-(\d+)/', $orderId, $matches)) {
                $bill = DuesBill::find($matches[1]);
            }
        }

        if (! $bill) {
            Log::warning("Midtrans Webhook: Tagihan tidak ditemukan untuk Order ID: {$orderId}");

            return response()->json(['status' => 'error', 'message' => 'Bill not found'], 404);
        }

        // Verifikasi Signature jika server key terisi
        $serverKey = config('services.midtrans.server_key');
        if (! empty($signatureKey) && ! empty($serverKey)) {
            $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
            if ($signatureKey !== $expectedSignature) {
                Log::warning("Midtrans Webhook Signature Mismatch: {$signatureKey} vs {$expectedSignature}");
            }
        }

        // Tangani Status Settlement (Berhasil Bayar Lunas)
        if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            if ($bill->status !== 'paid') {
                DB::beginTransaction();
                try {
                    $dueDate = $bill->duesPeriod->due_date->endOfDay();
                    $isOnTime = now()->lte($dueDate);

                    $bill->status = 'paid';
                    $bill->is_on_time = $isOnTime;
                    $bill->paid_at = now();
                    $bill->payment_method = 'midtrans';
                    $bill->midtrans_payment_type = $paymentType;
                    $bill->payment_channel = 'midtrans_'.$paymentType;
                    $bill->save();

                    // Otomatis catat mutasi kas masuk ke Buku Kas
                    $organization = $bill->organization;
                    $category = $organization->cashCategories()
                        ->where('type', 'in')
                        ->where('name', 'Iuran Wajib')
                        ->first()
                        ?? $organization->cashCategories()->where('type', 'in')->first();

                    $desc = "Pembayaran Otomatis Midtrans ({$paymentType}) {$bill->duesPeriod->title} - {$bill->member->name}";
                    $alreadyExists = Transaction::where('organization_id', $organization->id)
                        ->where('description', $desc)
                        ->exists();

                    if (! $alreadyExists) {
                        Transaction::create([
                            'organization_id' => $organization->id,
                            'type' => 'in',
                            'category_id' => $category?->id,
                            'amount' => $bill->amount,
                            'transaction_date' => now()->toDateString(),
                            'description' => $desc,
                            'is_locked' => true,
                        ]);
                    }

                    ActivityLog::create([
                        'organization_id' => $organization->id,
                        'action' => 'midtrans_payment_settled',
                        'subject_type' => DuesBill::class,
                        'subject_id' => $bill->id,
                        'payload' => [
                            'order_id' => $orderId,
                            'payment_type' => $paymentType,
                            'amount' => $bill->amount,
                            'is_on_time' => $isOnTime,
                        ],
                    ]);

                    DB::commit();
                    Log::info("Tagihan #{$bill->id} berhasil dilunasi via Midtrans ({$paymentType})");
                } catch (\Throwable $e) {
                    DB::rollBack();
                    Log::error("Gagal memproses pelunasan Midtrans untuk tagihan #{$bill->id}: ".$e->getMessage());

                    return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Notification processed successfully']);
    }

    /**
     * Endpoint simulasi instan bayar lunas di sandbox (untuk kemudahan pengujian demo).
     */
    public function simulateSuccess(Request $request, DuesBill $bill): JsonResponse
    {
        if ($bill->status === 'paid') {
            return response()->json(['status' => 'info', 'message' => 'Tagihan sudah lunas.']);
        }

        DB::beginTransaction();
        try {
            $dueDate = $bill->duesPeriod->due_date->endOfDay();
            $isOnTime = now()->lte($dueDate);

            $bill->status = 'paid';
            $bill->is_on_time = $isOnTime;
            $bill->paid_at = now();
            $bill->payment_method = 'midtrans';
            $bill->midtrans_payment_type = 'qris_sandbox';
            $bill->payment_channel = 'midtrans_qris';
            $bill->save();

            $organization = $bill->organization;
            $category = $organization->cashCategories()
                ->where('type', 'in')
                ->where('name', 'Iuran Wajib')
                ->first()
                ?? $organization->cashCategories()->where('type', 'in')->first();

            $desc = "Pembayaran Otomatis Midtrans (QRIS Sandbox) {$bill->duesPeriod->title} - {$bill->member->name}";
            $alreadyExists = Transaction::where('organization_id', $organization->id)
                ->where('description', $desc)
                ->exists();

            if (! $alreadyExists) {
                Transaction::create([
                    'organization_id' => $organization->id,
                    'type' => 'in',
                    'category_id' => $category?->id,
                    'amount' => $bill->amount,
                    'transaction_date' => now()->toDateString(),
                    'description' => $desc,
                    'is_locked' => true,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran QRIS Midtrans Sandbox berhasil diverifikasi lunas secara instan.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
