<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role admin
        $adminRole = Role::where('name', 'admin')->first();

        // Buat user admin
        if ($adminRole) {
            User::updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin Utama',
                    'password' => Hash::make('password123'), // ganti sesuai kebutuhan
                    'role_id' => $adminRole->id,
                ]
            );
        }

        // Tambahan user sample
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            User::updateOrCreate(
                ['email' => 'user@example.com'],
                [
                    'name' => 'User Biasa',
                    'password' => Hash::make('password123'),
                    'role_id' => $userRole->id,
                ]
            );
        }
    }
}
