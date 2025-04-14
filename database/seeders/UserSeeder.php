<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('12345678'), // ganti sesuai kebutuhan
            ]
        );

        // Assign role super_admin (pastikan ShieldSeeder sudah dijalankan)
        $user->assignRole('super_admin');
    }
}
