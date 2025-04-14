<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Role Super Admin, Admin, dan User
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Ambil semua permissions
        $allPermissions = Permission::all();

        // Berikan semua permission ke Super Admin
        $superAdmin->givePermissionTo($allPermissions);

        // Tambahkan User sebagai Super Admin
        $superAdminUser = User::where('email', 'superadmin@example.com')->first();
        if ($superAdminUser) {
            $superAdminUser->assignRole('super_admin');
        }

        // Tambahkan User sebagai Admin
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        // Tambahkan User Biasa
        $normalUser = User::where('email', 'user@example.com')->first();
        if ($normalUser) {
            $normalUser->assignRole('user');
        }
    }
}
