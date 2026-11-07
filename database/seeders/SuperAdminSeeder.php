<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Super Admin Kasly (Master Platform)
        User::updateOrCreate(
            ['email' => 'admin@kasly.id'],
            [
                'name' => 'Super Admin Kasly',
                'username' => 'admin_kasly',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'is_super_admin' => true,
            ]
        );

        // 2. Akun Admin Penerima Organisasi (Admin Kasly Penerima & Penyetuju Pengajuan Ormawa)
        User::updateOrCreate(
            ['email' => 'admin.organisasi@kasly.id'],
            [
                'name' => 'Admin Penerima Organisasi',
                'username' => 'admin_organisasi',
                'phone' => '081299887766',
                'password' => Hash::make('password'),
                'is_super_admin' => true,
            ]
        );
    }
}
