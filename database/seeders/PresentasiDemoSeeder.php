<?php

namespace Database\Seeders;

use App\Models\CashCategory;
use App\Models\DuesBill;
use App\Models\DuesPeriod;
use App\Models\Member;
use App\Models\MemberRegistrationToken;
use App\Models\Organization;
use App\Models\OrganizationPaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PresentasiDemoSeeder extends Seeder
{
    /**
     * Jalankan seeder khusus presentasi: 1 Organisasi, 1 Admin Bendahara, 1 Pengurus, 5 Anggota.
     */
    public function run(): void
    {
        // 1. Pastikan akun Super Admin & Admin Penerima tersedia
        $this->call(SuperAdminSeeder::class);

        // 2. Buat / Perbarui 1 Organisasi Utama (HIMTECH)
        $org = Organization::updateOrCreate(
            ['code' => 'HIMTECH'],
            [
                'name' => 'Himpunan Mahasiswa Teknologi',
                'description' => 'Organisasi kemahasiswaan jurusan teknik & teknologi informasi.',
                'status' => 'active',
                'is_midtrans_enabled' => true,
            ]
        );

        // 3. Akun 1: Admin Bendahara (Ketua / PIC Organisasi)
        $adminBendahara = User::updateOrCreate(
            ['email' => 'bendahara@himtech.id'],
            [
                'name' => 'Siti Nurhaliza',
                'username' => 'bendahara_himtech',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        // Pasang peran admin_organisasi pada pivot
        $org->users()->syncWithoutDetaching([
            $adminBendahara->id => [
                'role' => 'admin_organisasi',
                'is_active' => true,
            ],
        ]);

        // 4. Akun 2: Pengurus (Staf Operasional Ormawa)
        $pengurusStaf = User::updateOrCreate(
            ['email' => 'pengurus@himtech.id'],
            [
                'name' => 'Dimas Pratama',
                'username' => 'pengurus_himtech',
                'phone' => '081299887711',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        // Pasang peran staff pada pivot
        $org->users()->syncWithoutDetaching([
            $pengurusStaf->id => [
                'role' => 'staff',
                'is_active' => true,
            ],
        ]);

        // 5. Kategori Kas Bawaan
        $categoriesData = [
            ['name' => 'Iuran Wajib', 'type' => 'in'],
            ['name' => 'Donasi / Sponsor', 'type' => 'in'],
            ['name' => 'Operasional Kegiatan', 'type' => 'out'],
            ['name' => 'Konsumsi Rapat', 'type' => 'out'],
            ['name' => 'ATK & Perlengkapan', 'type' => 'out'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['name']] = CashCategory::firstOrCreate([
                'organization_id' => $org->id,
                'name' => $c['name'],
                'type' => $c['type'],
            ]);
        }

        // 6. Tepat 5 Data Anggota Mahasiswa (Lengkap dengan Akun Login Masing-masing)
        $memberData = [
            [
                'nim' => '2026001',
                'name' => 'Aditya Pratama',
                'email' => 'aditya@himtech.id',
                'phone' => '081211110001',
                'status_simulasi' => 'lunas_tepat_waktu',
                'metode' => 'transfer',
            ],
            [
                'nim' => '2026002',
                'name' => 'Budi Santoso',
                'email' => 'budi@himtech.id',
                'phone' => '081211110002',
                'status_simulasi' => 'lunas_tepat_waktu',
                'metode' => 'midtrans',
            ],
            [
                'nim' => '2026003',
                'name' => 'Citra Lestari',
                'email' => 'citra@himtech.id',
                'phone' => '081211110003',
                'status_simulasi' => 'lunas_tepat_waktu',
                'metode' => 'cash',
            ],
            [
                'nim' => '2026004',
                'name' => 'Daffa Al Ghifari',
                'email' => 'daffa@himtech.id',
                'phone' => '081211110004',
                'status_simulasi' => 'lunas_terlambat',
                'metode' => 'cash',
            ],
            [
                'nim' => '2026005',
                'name' => 'Eka Rahmawati',
                'email' => 'eka@himtech.id',
                'phone' => '081211110005',
                'status_simulasi' => 'belum_bayar',
                'metode' => 'cash',
            ],
        ];

        $seededMembers = [];
        foreach ($memberData as $item) {
            // Buat / update User Login untuk Anggota
            $userMember = User::updateOrCreate(
                ['username' => $item['nim']],
                [
                    'name' => $item['name'],
                    'email' => $item['email'],
                    'phone' => $item['phone'],
                    'password' => Hash::make('password'),
                    'is_super_admin' => false,
                ]
            );

            // Buat / update Record Member
            $member = Member::updateOrCreate(
                [
                    'organization_id' => $org->id,
                    'member_number' => $item['nim'],
                ],
                [
                    'user_id' => $userMember->id,
                    'name' => $item['name'],
                    'phone' => $item['phone'],
                    'is_active' => true,
                ]
            );

            // Hubungkan User ke Organisasi dengan role anggota
            $org->users()->syncWithoutDetaching([
                $userMember->id => [
                    'role' => 'anggota',
                    'member_id' => $member->id,
                    'is_active' => true,
                ],
            ]);

            $seededMembers[] = [
                'member' => $member,
                'status_simulasi' => $item['status_simulasi'],
                'metode' => $item['metode'],
            ];
        }

        // Hapus anggota lama di luar 5 anggota ini jika ada
        $allowedNims = array_column($memberData, 'nim');
        Member::where('organization_id', $org->id)
            ->whereNotIn('member_number', $allowedNims)
            ->delete();

        // 7. Periode Iuran Kas
        $dueDate = Carbon::now()->addDays(7)->format('Y-m-d');
        $period = DuesPeriod::updateOrCreate(
            [
                'organization_id' => $org->id,
                'title' => 'Iuran Kas November 2026',
            ],
            [
                'amount' => 20000,
                'due_date' => $dueDate,
                'created_by' => $adminBendahara->id,
            ]
        );

        // 8. Tagihan Kas untuk 5 Anggota (3 Lunas Tepat Waktu, 1 Lunas Terlambat, 1 Belum Bayar)
        foreach ($seededMembers as $row) {
            $member = $row['member'];
            $status = $row['status_simulasi'];
            $metode = $row['metode'];

            if ($status === 'lunas_tepat_waktu') {
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
                        'paid_at' => Carbon::now()->subDays(2),
                        'verified_by' => $adminBendahara->id,
                        'payment_method' => $metode,
                    ]
                );
            } elseif ($status === 'lunas_terlambat') {
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
                        'paid_at' => Carbon::now()->subHours(5),
                        'verified_by' => $adminBendahara->id,
                        'payment_method' => $metode,
                    ]
                );
            } else {
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

        // 9. Transaksi Mutasi Buku Kas
        Transaction::updateOrCreate(
            [
                'organization_id' => $org->id,
                'description' => 'Penerimaan Iuran Kas Anggota Terbayar (4 Mahasiswa)',
            ],
            [
                'type' => 'in',
                'category_id' => $categories['Iuran Wajib']->id ?? null,
                'amount' => 80000,
                'transaction_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'created_by' => $adminBendahara->id,
                'is_locked' => true,
            ]
        );

        Transaction::updateOrCreate(
            [
                'organization_id' => $org->id,
                'description' => 'Pembelian Spidol & Kertas HVS Sekretariat',
            ],
            [
                'type' => 'out',
                'category_id' => $categories['ATK & Perlengkapan']->id ?? null,
                'amount' => 25000,
                'transaction_date' => Carbon::now()->subHours(8)->format('Y-m-d'),
                'created_by' => $adminBendahara->id,
                'is_locked' => true,
            ]
        );

        // 10. Pengaturan Metode Pembayaran Organisasi (Cash, Transfer Bank, QRIS)
        OrganizationPaymentMethod::updateOrCreate(
            [
                'organization_id' => $org->id,
                'type' => 'cash',
            ],
            [
                'channel_name' => 'Tunai (Cash)',
                'account_holder' => 'Siti Nurhaliza (Bendahara)',
                'instructions' => 'Serahkan uang tunai pas kepada bendahara saat kumpul mingguan atau di sekretariat ormawa.',
                'is_active' => true,
            ]
        );

        OrganizationPaymentMethod::updateOrCreate(
            [
                'organization_id' => $org->id,
                'type' => 'bank',
                'channel_name' => 'Bank Central Asia (BCA)',
            ],
            [
                'account_number' => '1234567890',
                'account_holder' => 'HIMTECH OFFICIAL',
                'instructions' => 'Transfer tepat sesuai nominal tagihan dan unggah tangkapan layar bukti transfer.',
                'is_active' => true,
            ]
        );

        // 11. Token QR Code Registrasi Mandiri Aktif
        MemberRegistrationToken::updateOrCreate(
            [
                'organization_id' => $org->id,
                'token' => 'kasly_himtech_demo2026',
            ],
            [
                'max_uses' => 10,
                'used_count' => 5,
                'expires_at' => Carbon::now()->addDays(7),
                'created_by' => $adminBendahara->id,
                'is_active' => true,
            ]
        );
    }
}
