<?php

namespace Database\Seeders;

use App\Models\CashCategory;
use App\Models\DuesBill;
use App\Models\DuesPeriod;
use App\Models\Member;
use App\Models\Organization;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Organisasi Demo HIMTECH
        $org = Organization::updateOrCreate(
            ['code' => 'HIMTECH'],
            [
                'name' => 'Himpunan Mahasiswa Teknologi',
                'description' => 'Organisasi kemahasiswaan jurusan teknik & teknologi informasi.',
                'status' => 'active',
            ]
        );

        // 2. Akun Admin Organisasi (Ketua / Pengelola yang Mendaftar)
        $adminOrg = User::updateOrCreate(
            ['email' => 'zaki@himtech.id'],
            [
                'name' => 'Muhammad Zaki',
                'username' => 'bendahara_himtech',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        // Hubungkan sebagai admin_organisasi
        if (! $org->users()->where('users.id', $adminOrg->id)->exists()) {
            $org->users()->attach($adminOrg->id, [
                'role' => 'admin_organisasi',
                'is_active' => true,
            ]);
        } else {
            $org->users()->updateExistingPivot($adminOrg->id, ['role' => 'admin_organisasi']);
        }

        // Akun Admin Staff / Bendahara (Dibuat oleh Admin Organisasi)
        $staffBendahara = User::updateOrCreate(
            ['email' => 'siti@himtech.id'],
            [
                'name' => 'Siti Nurhaliza',
                'username' => 'siti_bendahara',
                'phone' => '081299998888',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        if (! $org->users()->where('users.id', $staffBendahara->id)->exists()) {
            $org->users()->attach($staffBendahara->id, [
                'role' => 'bendahara',
                'is_active' => true,
            ]);
        }

        // 3. Kategori Kas Default
        $categoriesData = [
            ['name' => 'Iuran Wajib', 'type' => 'in'],
            ['name' => 'Donasi / Sponsor', 'type' => 'in'],
            ['name' => 'Kas Awal', 'type' => 'in'],
            ['name' => 'Konsumsi', 'type' => 'out'],
            ['name' => 'ATK & Perlengkapan', 'type' => 'out'],
            ['name' => 'Operasional Kegiatan', 'type' => 'out'],
            ['name' => 'Lain-lain', 'type' => 'out'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['name']] = CashCategory::firstOrCreate([
                'organization_id' => $org->id,
                'name' => $c['name'],
                'type' => $c['type'],
            ]);
        }

        // 4. Sample 10 Anggota Ormawa
        $memberNames = [
            ['nim' => '2026001', 'name' => 'Aditya Pratama', 'phone' => '081211110001'],
            ['nim' => '2026002', 'name' => 'Budi Santoso', 'phone' => '081211110002'],
            ['nim' => '2026003', 'name' => 'Citra Lestari', 'phone' => '081211110003'],
            ['nim' => '2026004', 'name' => 'Daffa Al Ghifari', 'phone' => '081211110004'],
            ['nim' => '2026005', 'name' => 'Eka Rahmawati', 'phone' => '081211110005'],
            ['nim' => '2026006', 'name' => 'Farhan Maulana', 'phone' => '081211110006'],
            ['nim' => '2026007', 'name' => 'Gita Permata', 'phone' => '081211110007'],
            ['nim' => '2026008', 'name' => 'Hendra Saputra', 'phone' => '081211110008'],
            ['nim' => '2026009', 'name' => 'Indah Wahyuni', 'phone' => '081211110009'],
            ['nim' => '2026010', 'name' => 'Joko Susilo', 'phone' => '081211110010'],
        ];

        $members = [];
        foreach ($memberNames as $m) {
            $members[] = Member::updateOrCreate(
                [
                    'organization_id' => $org->id,
                    'member_number' => $m['nim'],
                ],
                [
                    'name' => $m['name'],
                    'phone' => $m['phone'],
                    'is_active' => true,
                ]
            );
        }

        // 5. Periode Iuran Kas
        $period = DuesPeriod::firstOrCreate(
            [
                'organization_id' => $org->id,
                'title' => 'Iuran Kas November 2026',
            ],
            [
                'amount' => 20000,
                'due_date' => '2026-11-15',
                'created_by' => $staffBendahara->id,
            ]
        );

        // 6. Tagihan Iuran ke Anggota (Simulasi Disiplin Tepat Waktu vs Terlambat vs Belum Bayar)
        foreach ($members as $index => $member) {
            // 6 anggota lunas tepat waktu (<= due date 15 Nov)
            if ($index < 6) {
                DuesBill::updateOrCreate(
                    [
                        'organization_id' => $org->id,
                        'dues_period_id' => $period->id,
                        'member_id' => $member->id,
                    ],
                    [
                        'amount' => 20000,
                        'status' => 'paid',
                        'is_on_time' => true,
                        'paid_at' => '2026-11-05 10:00:00',
                        'verified_by' => $staffBendahara->id,
                        'payment_method' => 'transfer',
                    ]
                );
            }
            // 2 anggota lunas terlambat (> due date 15 Nov)
            elseif ($index < 8) {
                DuesBill::updateOrCreate(
                    [
                        'organization_id' => $org->id,
                        'dues_period_id' => $period->id,
                        'member_id' => $member->id,
                    ],
                    [
                        'amount' => 20000,
                        'status' => 'paid',
                        'is_on_time' => false,
                        'paid_at' => '2026-11-18 14:00:00',
                        'verified_by' => $staffBendahara->id,
                        'payment_method' => 'cash',
                    ]
                );
            }
            // 2 anggota belum bayar (unpaid)
            else {
                DuesBill::updateOrCreate(
                    [
                        'organization_id' => $org->id,
                        'dues_period_id' => $period->id,
                        'member_id' => $member->id,
                    ],
                    [
                        'amount' => 20000,
                        'status' => 'unpaid',
                        'is_on_time' => null,
                        'paid_at' => null,
                        'payment_method' => 'cash',
                    ]
                );
            }
        }

        // 7. Transaksi Kas Masuk & Keluar Contoh
        Transaction::firstOrCreate(
            [
                'organization_id' => $org->id,
                'description' => 'Penerimaan Iuran Kas Anggota Terbayar (8 Orang)',
            ],
            [
                'type' => 'in',
                'category_id' => $categories['Iuran Wajib']->id ?? null,
                'amount' => 160000,
                'transaction_date' => '2026-11-05',
                'created_by' => $staffBendahara->id,
                'is_locked' => true,
            ]
        );

        Transaction::firstOrCreate(
            [
                'organization_id' => $org->id,
                'description' => 'Konsumsi Rapat Pleno Pengurus HIMTECH',
            ],
            [
                'type' => 'out',
                'category_id' => $categories['Konsumsi']->id ?? null,
                'amount' => 50000,
                'transaction_date' => '2026-11-06',
                'created_by' => $staffBendahara->id,
                'is_locked' => true,
            ]
        );

        Transaction::firstOrCreate(
            [
                'organization_id' => $org->id,
                'description' => 'Pembelian Kertas HVS & Spidol Whiteboard',
            ],
            [
                'type' => 'out',
                'category_id' => $categories['ATK & Perlengkapan']->id ?? null,
                'amount' => 25000,
                'transaction_date' => '2026-11-06',
                'created_by' => $staffBendahara->id,
                'is_locked' => true,
            ]
        );
    }
}
