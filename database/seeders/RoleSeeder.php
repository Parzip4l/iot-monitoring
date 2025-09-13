<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auth\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator penuh'],
            ['name' => 'manager', 'description' => 'Manajer operasional'],
            ['name' => 'user', 'description' => 'User biasa'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
