<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('1234'),
        ]);
        $superAdmin->assignRole('super_admin');

        // Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234'),
        ]);
        $admin->assignRole('admin');

        // Regular User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('1234'),
        ]);
        $user->assignRole('user');
    }
}