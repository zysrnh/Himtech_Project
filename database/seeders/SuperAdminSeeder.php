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
    }
}
