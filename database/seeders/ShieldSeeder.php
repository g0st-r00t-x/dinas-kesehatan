<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_activity::log","view_any_activity::log","create_activity::log","update_activity::log","delete_activity::log","delete_any_activity::log","view_arsip::surat","view_any_arsip::surat","create_arsip::surat","update_arsip::surat","delete_arsip::surat","delete_any_arsip::surat","view_data::serkom","view_any_data::serkom","create_data::serkom","update_data::serkom","delete_data::serkom","delete_any_data::serkom","view_inventaris::permasalahan::kepegawaian","view_any_inventaris::permasalahan::kepegawaian","view_own_inventaris::permasalahan::kepegawaian","download_file_inventaris::permasalahan::kepegawaian","create_inventaris::permasalahan::kepegawaian","update_inventaris::permasalahan::kepegawaian","delete_inventaris::permasalahan::kepegawaian","delete_any_inventaris::permasalahan::kepegawaian","kirim_notif_inventaris::permasalahan::kepegawaian","view_jenis::surat","view_any_jenis::surat","create_jenis::surat","update_jenis::surat","delete_jenis::surat","delete_any_jenis::surat","view_pegawai","view_any_pegawai","create_pegawai","update_pegawai","delete_pegawai","delete_any_pegawai","view_pengajuan::a::j::j","view_any_pengajuan::a::j::j","view_own_pengajuan::a::j::j","download_file_pengajuan::a::j::j","create_pengajuan::a::j::j","update_pengajuan::a::j::j","delete_pengajuan::a::j::j","delete_any_pengajuan::a::j::j","kirim_notif_pengajuan::a::j::j","view_pengajuan::surat","view_any_pengajuan::surat","create_pengajuan::surat","update_pengajuan::surat","delete_pengajuan::surat","delete_any_pengajuan::surat","view_rekap::absen","view_any_rekap::absen","create_rekap::absen","update_rekap::absen","delete_rekap::absen","delete_any_rekap::absen","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_surat::keluar","view_any_surat::keluar","create_surat::keluar","update_surat::keluar","delete_surat::keluar","delete_any_surat::keluar","view_surat::masuk","view_any_surat::masuk","create_surat::masuk","update_surat::masuk","delete_surat::masuk","delete_any_surat::masuk","view_user","view_any_user","create_user","update_user","delete_user","delete_any_user","view_usulan::permohonan::cuti","view_any_usulan::permohonan::cuti","view_own_usulan::permohonan::cuti","download_file_usulan::permohonan::cuti","create_usulan::permohonan::cuti","update_usulan::permohonan::cuti","delete_usulan::permohonan::cuti","delete_any_usulan::permohonan::cuti","kirim_notif_usulan::permohonan::cuti","view_usulan::permohonan::pensiun","view_any_usulan::permohonan::pensiun","create_usulan::permohonan::pensiun","update_usulan::permohonan::pensiun","delete_usulan::permohonan::pensiun","delete_any_usulan::permohonan::pensiun","view_usulan::rekomendasi::penelitian","view_any_usulan::rekomendasi::penelitian","create_usulan::rekomendasi::penelitian","update_usulan::rekomendasi::penelitian","delete_usulan::rekomendasi::penelitian","delete_any_usulan::rekomendasi::penelitian","view_usulan::revisi::sk::pangkat","view_any_usulan::revisi::sk::pangkat","create_usulan::revisi::sk::pangkat","update_usulan::revisi::sk::pangkat","delete_usulan::revisi::sk::pangkat","delete_any_usulan::revisi::sk::pangkat","view_usulan::s::k::berkala","view_any_usulan::s::k::berkala","create_usulan::s::k::berkala","update_usulan::s::k::berkala","delete_usulan::s::k::berkala","delete_any_usulan::s::k::berkala","view_usulan::s::k::pemberhentian::sementara","view_any_usulan::s::k::pemberhentian::sementara","create_usulan::s::k::pemberhentian::sementara","update_usulan::s::k::pemberhentian::sementara","delete_usulan::s::k::pemberhentian::sementara","delete_any_usulan::s::k::pemberhentian::sementara","page_CustomDashboard","widget_UsersStats","widget_ExampleChart","widget_TreeChart","widget_LatestActivities"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
