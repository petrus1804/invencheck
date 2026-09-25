<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $staffRole = Role::where('slug', 'staff')->first();
        $userRole = Role::where('slug', 'user')->first();

        User::updateOrCreate(
            ['email' => 'admin@contoh.com'],
            [
                'name' => 'Admin InvenCheck',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'role_id' => $adminRole?->id,
            ]
        );
    }
}