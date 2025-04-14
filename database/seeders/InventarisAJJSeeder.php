<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventarisAJJ;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Support\Str;

class InventarisAJJSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data user, pegawai, dan unit kerja sebelum membuat inventaris
        $users = User::pluck('id')->toArray();
        $pegawaiNips = Pegawai::pluck('nip')->toArray();

        if (empty($users) || empty($pegawaiNips)) {
            return; // Berhenti jika tidak ada data terkait
        }

        for ($i = 0; $i < 10; $i++) {
            InventarisAJJ::create([
                'user_id' => $users[array_rand($users)],
                'pegawai_nip' => $pegawaiNips[array_rand($pegawaiNips)],
                'tmt_pemberian_tunjangan' => now()->subDays(rand(10, 100))->toDateString(),
                'sk_jabatan' => 'Ada',
                'upload_berkas' => 'uploads/' . Str::random(10) . '.pdf',
                'surat_pengantar_unit_kerja' => 'surat_pengantar/' . Str::random(10) . '.pdf',
            ]);
        }
    }
}
