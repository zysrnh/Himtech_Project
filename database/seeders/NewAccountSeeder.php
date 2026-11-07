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

class NewAccountSeeder extends Seeder
{
    /**
     * Seeder Akun Tambahan Baru - Kasly Platform
     *
     * CATATAN: Seeder ini TIDAK dijalankan secara otomatis pada migrasi default.
     * Untuk menjalankannya secara manual saat pengujian, jalankan perintah:
     * php artisan db:seed --class=NewAccountSeeder
     */
    public function run(): void
    {
        // 1. Buat Organisasi Baru: BEM FASILKOM
        $org = Organization::updateOrCreate(
            ['code' => 'BEMFASILKOM'],
            [
                'name' => 'BEM Fakultas Ilmu Komputer',
                'description' => 'Badan Eksekutif Mahasiswa Fakultas Ilmu Komputer - Periode 2026/2027.',
                'status' => 'active',
            ]
        );

        // 2. Akun Admin Organisasi (Ketua Ormawa BEM)
        $adminOrg = User::updateOrCreate(
            ['email' => 'rizky@fasilkom.id'],
            [
                'name' => 'Rizky Fauzan',
                'username' => 'rizky_bem',
                'phone' => '081223344551',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        if (! $org->users()->where('users.id', $adminOrg->id)->exists()) {
            $org->users()->attach($adminOrg->id, [
                'role' => 'admin_organisasi',
                'is_active' => true,
            ]);
        } else {
            $org->users()->updateExistingPivot($adminOrg->id, ['role' => 'admin_organisasi']);
        }

        // 3. Akun Bendahara BEM
        $bendahara = User::updateOrCreate(
            ['email' => 'nabila@fasilkom.id'],
            [
                'name' => 'Nabila Putri',
                'username' => 'nabila_bendahara',
                'phone' => '081223344552',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        if (! $org->users()->where('users.id', $bendahara->id)->exists()) {
            $org->users()->attach($bendahara->id, [
                'role' => 'bendahara',
                'is_active' => true,
            ]);
        } else {
            $org->users()->updateExistingPivot($bendahara->id, ['role' => 'bendahara']);
        }

        // 4. Akun Staf Operasional BEM
        $staff = User::updateOrCreate(
            ['email' => 'dimas@fasilkom.id'],
            [
                'name' => 'Dimas Pratama',
                'username' => 'dimas_staff',
                'phone' => '081223344553',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        if (! $org->users()->where('users.id', $staff->id)->exists()) {
            $org->users()->attach($staff->id, [
                'role' => 'staff',
                'is_active' => true,
            ]);
        } else {
            $org->users()->updateExistingPivot($staff->id, ['role' => 'staff']);
        }

        // 5. Kategori Kas Default BEM FASILKOM
        $categoriesData = [
            ['name' => 'Iuran Wajib Bulanan', 'type' => 'in'],
            ['name' => 'Sponsorship & Donasi', 'type' => 'in'],
            ['name' => 'Dana Hibah Kampus', 'type' => 'in'],
            ['name' => 'Konsumsi Rapat & Acara', 'type' => 'out'],
            ['name' => 'Perlengkapan & Logistik', 'type' => 'out'],
            ['name' => 'Humas & Publikasi', 'type' => 'out'],
            ['name' => 'Operasional Sekretariat', 'type' => 'out'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['name']] = CashCategory::firstOrCreate([
                'organization_id' => $org->id,
                'name' => $c['name'],
                'type' => $c['type'],
            ]);
        }

        // 6. Data Sample 5 Anggota Mahasiswa BEM FASILKOM
        $memberData = [
            ['nim' => '2026101', 'name' => 'Ahmad Fauzi', 'phone' => '081255550101', 'email' => 'ahmad.fauzi@fasilkom.id'],
            ['nim' => '2026102', 'name' => 'Bella Safitri', 'phone' => '081255550102', 'email' => 'bella.safitri@fasilkom.id'],
            ['nim' => '2026103', 'name' => 'Chandra Wijaya', 'phone' => '081255550103', 'email' => 'chandra.wijaya@fasilkom.id'],
            ['nim' => '2026104', 'name' => 'Dini Anggraini', 'phone' => '081255550104', 'email' => 'dini.anggraini@fasilkom.id'],
            ['nim' => '2026105', 'name' => 'Erlangga Putra', 'phone' => '081255550105', 'email' => 'erlangga.putra@fasilkom.id'],
        ];

        $members = [];
        foreach ($memberData as $m) {
            // Buat akun user login anggota
            $memberUser = User::updateOrCreate(
                ['username' => $m['nim']],
                [
                    'name' => $m['name'],
                    'email' => $m['email'],
                    'phone' => $m['phone'],
                    'password' => Hash::make('password'),
                    'is_super_admin' => false,
                ]
            );

            // Hubungkan user ke organisasi sebagai role 'anggota'
            if (! $org->users()->where('users.id', $memberUser->id)->exists()) {
                $org->users()->attach($memberUser->id, [
                    'role' => 'anggota',
                    'is_active' => true,
                ]);
            }

            // Catat ke tabel members
            $members[] = Member::updateOrCreate(
                [
                    'organization_id' => $org->id,
                    'member_number' => $m['nim'],
                ],
                [
                    'user_id' => $memberUser->id,
                    'name' => $m['name'],
                    'phone' => $m['phone'],
                    'is_active' => true,
                ]
            );
        }

        // 7. Periode Iuran Kas BEM
        $period = DuesPeriod::firstOrCreate(
            [
                'organization_id' => $org->id,
                'title' => 'Iuran Kas Desember 2026',
            ],
            [
                'amount' => 25000,
                'due_date' => '2026-12-10',
                'created_by' => $bendahara->id,
            ]
        );

        // 8. Tagihan Iuran Anggota (Simulasi Kedisiplinan: 3 Tepat Waktu, 1 Terlambat, 1 Belum Bayar)
        foreach ($members as $idx => $member) {
            if ($idx < 3) {
                // 3 anggota lunas tepat waktu (<= due date 10 Des)
                DuesBill::updateOrCreate(
                    [
                        'organization_id' => $org->id,
                        'dues_period_id' => $period->id,
                        'member_id' => $member->id,
                    ],
                    [
                        'amount' => 25000,
                        'status' => 'paid',
                        'is_on_time' => true,
                        'paid_at' => '2026-12-05 09:30:00',
                        'verified_by' => $bendahara->id,
                        'payment_method' => 'transfer',
                    ]
                );
            } elseif ($idx === 3) {
                // 1 anggota lunas terlambat (> due date 10 Des)
                DuesBill::updateOrCreate(
                    [
                        'organization_id' => $org->id,
                        'dues_period_id' => $period->id,
                        'member_id' => $member->id,
                    ],
                    [
                        'amount' => 25000,
                        'status' => 'paid',
                        'is_on_time' => false,
                        'paid_at' => '2026-12-12 15:45:00',
                        'verified_by' => $bendahara->id,
                        'payment_method' => 'cash',
                    ]
                );
            } else {
                // 1 anggota belum bayar
                DuesBill::updateOrCreate(
                    [
                        'organization_id' => $org->id,
                        'dues_period_id' => $period->id,
                        'member_id' => $member->id,
                    ],
                    [
                        'amount' => 25000,
                        'status' => 'unpaid',
                        'is_on_time' => null,
                        'paid_at' => null,
                        'payment_method' => 'cash',
                    ]
                );
            }
        }

        // 9. Mutasi Buku Kas Masuk & Keluar Contoh
        Transaction::firstOrCreate(
            [
                'organization_id' => $org->id,
                'description' => 'Penerimaan Iuran Kas Mahasiswa Terbayar (4 Orang)',
            ],
            [
                'type' => 'in',
                'category_id' => $categories['Iuran Wajib Bulanan']->id ?? null,
                'amount' => 100000,
                'transaction_date' => '2026-12-05',
                'created_by' => $bendahara->id,
                'is_locked' => true,
            ]
        );

        Transaction::firstOrCreate(
            [
                'organization_id' => $org->id,
                'description' => 'Cetak Banner & Sertifikat Pelatihan Desain',
            ],
            [
                'type' => 'out',
                'category_id' => $categories['Perlengkapan & Logistik']->id ?? null,
                'amount' => 45000,
                'transaction_date' => '2026-12-06',
                'created_by' => $bendahara->id,
                'is_locked' => true,
            ]
        );
    }
}
