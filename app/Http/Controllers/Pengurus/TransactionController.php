<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Tampilkan halaman Buku Kas Mutasi ormawa (Pemasukan, Pengeluaran, & Transparansi).
     */
    public function index(Request $request): View
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        // Hitung total finansial real-time
        $totalIncome = (float) $organization->transactions()->where('type', 'in')->sum('amount');
        $totalExpense = (float) $organization->transactions()->where('type', 'out')->sum('amount');
        $currentBalance = $totalIncome - $totalExpense;

        // Filter tipe & bulan
        $typeFilter = $request->query('type', 'all');
        $monthFilter = $request->query('month');
        $search = $request->query('search');

        $query = $organization->transactions()
            ->with(['category', 'creator']);

        if ($typeFilter === 'in' || $typeFilter === 'out') {
            $query->where('type', $typeFilter);
        }

        if ($monthFilter) {
            try {
                $cDate = Carbon::createFromFormat('Y-m', $monthFilter);
                $query->whereBetween('transaction_date', [
                    $cDate->copy()->startOfMonth()->toDateString(),
                    $cDate->copy()->endOfMonth()->toDateString(),
                ]);
            } catch (\Throwable $e) {
                // Abaikan jika format bulan tidak valid
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($qc) use ($search) {
                        $qc->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $transactions = $query->latest('transaction_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        // Kategori kas yang sudah ada
        $inCategories = $organization->cashCategories()->where('type', 'in')->get();
        $outCategories = $organization->cashCategories()->where('type', 'out')->get();

        // Standar kategori bawaan jika ormawa belum punya
        $defaultInCategories = ['Iuran Wajib', 'Donasi & Sponsor', 'Dana Usaha (Danus)', 'Subsidi Kampus', 'Pemasukan Lainnya'];
        $defaultOutCategories = ['Konsumsi & Rapat', 'Operasional & Kesekretariatan', 'Acara & Kepanitiaan', 'Perlengkapan & Logistik', 'Transportasi', 'Pengeluaran Lainnya'];

        return view('pengurus.buku-kas.index', compact(
            'organization',
            'totalIncome',
            'totalExpense',
            'currentBalance',
            'transactions',
            'typeFilter',
            'monthFilter',
            'search',
            'inCategories',
            'outCategories',
            'defaultInCategories',
            'defaultOutCategories'
        ));
    }

    /**
     * Catat mutasi kas baru (Pemasukan atau Pengeluaran) beserta upload nota/bukti fisik.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:in,out'],
            'category_name' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'transaction_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:500'],
            'proof' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'type.required' => 'Pilih jenis transaksi kas (Pemasukan atau Pengeluaran).',
            'category_name.required' => 'Kategori transaksi kas wajib diisi.',
            'amount.required' => 'Nominal transaksi kas wajib diisi.',
            'amount.min' => 'Nominal minimal Rp 1.000.',
            'transaction_date.required' => 'Tanggal transaksi wajib ditentukan.',
            'description.required' => 'Keterangan/keperluan transaksi wajib diisi.',
            'proof.image' => 'Bukti nota harus berupa gambar.',
            'proof.max' => 'Ukuran file foto nota maksimal 3 MB.',
        ]);

        // Cek saldo kas jika tipe pengeluaran
        if ($validated['type'] === 'out') {
            $totalIncome = (float) $organization->transactions()->where('type', 'in')->sum('amount');
            $totalExpense = (float) $organization->transactions()->where('type', 'out')->sum('amount');
            $currentBalance = $totalIncome - $totalExpense;

            if ($validated['amount'] > $currentBalance) {
                return back()->withInput()->withErrors([
                    'amount' => 'Nominal pengeluaran (Rp '.number_format($validated['amount'], 0, ',', '.').') melebihi sisa saldo kas aktif (Rp '.number_format($currentBalance, 0, ',', '.').'). Saldo kas ormawa tidak boleh minus.',
                ]);
            }
        }

        // Cari atau buat kategori kas
        $category = $organization->cashCategories()->firstOrCreate([
            'name' => trim($validated['category_name']),
            'type' => $validated['type'],
        ]);

        // Simpan foto nota/bukti jika ada
        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('proofs/transactions', 'public');
        }

        $tx = Transaction::create([
            'organization_id' => $organization->id,
            'type' => $validated['type'],
            'category_id' => $category->id,
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'description' => $validated['description'],
            'proof_path' => $proofPath,
            'created_by' => Auth::id(),
            'is_locked' => false,
        ]);

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'create_manual_transaction',
            'subject_type' => Transaction::class,
            'subject_id' => $tx->id,
            'payload' => [
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'category' => $category->name,
                'description' => $validated['description'],
            ],
        ]);

        $typeLabel = $validated['type'] === 'in' ? 'Pemasukan' : 'Pengeluaran';

        return redirect()->route('pengurus.buku-kas.index')
            ->with('success', "Transaksi {$typeLabel} sebesar Rp ".number_format($validated['amount'], 0, ',', '.').' berhasil dicatat ke Buku Kas ormawa.');
    }

    /**
     * Batalkan / hapus transaksi manual jika salah input.
     */
    public function destroy(Request $request, Transaction $transaction): RedirectResponse
    {
        /** @var Organization $organization */
        $organization = $request->attributes->get('current_organization') ?? Organization::find(session('active_organization_id'));

        if ($transaction->organization_id !== $organization->id) {
            abort(403);
        }

        if ($transaction->is_locked && str_starts_with($transaction->description, 'Iuran ')) {
            return back()->withErrors(['error' => 'Transaksi dari pelunasan iuran anggota tidak dapat dihapus manual dari Buku Kas demi integritas audit finansial.']);
        }

        if ($transaction->proof_path && Storage::disk('public')->exists($transaction->proof_path)) {
            Storage::disk('public')->delete($transaction->proof_path);
        }

        ActivityLog::create([
            'organization_id' => $organization->id,
            'user_id' => Auth::id(),
            'action' => 'delete_transaction',
            'subject_type' => Transaction::class,
            'subject_id' => $transaction->id,
            'payload' => [
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'description' => $transaction->description,
            ],
        ]);

        $transaction->delete();

        return redirect()->route('pengurus.buku-kas.index')
            ->with('success', 'Transaksi berhasil dihapus dari Buku Kas dan saldo telah disesuaikan kembali.');
    }
}
