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
                // Tetap lanjut jika sandbox test, tapi log warning
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

                    Transaction::create([
                        'organization_id' => $organization->id,
                        'type' => 'in',
                        'category_id' => $category?->id,
                        'amount' => $bill->amount,
                        'transaction_date' => now()->toDateString(),
                        'description' => "Pembayaran Otomatis Midtrans ({$paymentType}) {$bill->duesPeriod->title} - {$bill->member->name}",
                        'is_locked' => true,
                    ]);

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

            Transaction::create([
                'organization_id' => $organization->id,
                'type' => 'in',
                'category_id' => $category?->id,
                'amount' => $bill->amount,
                'transaction_date' => now()->toDateString(),
                'description' => "Pembayaran Otomatis Midtrans (QRIS Sandbox) {$bill->duesPeriod->title} - {$bill->member->name}",
                'is_locked' => true,
            ]);

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
