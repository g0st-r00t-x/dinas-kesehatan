<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat role super_admin
    $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);

    // Buat permission
    Permission::firstOrCreate(['name' => 'view shield']);
    Permission::firstOrCreate(['name' => 'manage shield']);

    // Assign permission ke role
    $superAdmin->givePermissionTo(['view shield', 'manage shield']);

    // Assign role ke user
    $user = \App\Models\User::find(1); // Ganti 1 dengan ID user Anda
    $user->assignRole('super_admin');
    }
}

