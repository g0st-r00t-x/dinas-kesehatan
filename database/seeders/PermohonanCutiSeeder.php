<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermohonanCuti;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PermohonanCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil daftar user dan pegawai yang ada di database
        $users = User::pluck('id')->toArray();
        $pegawaiNips = Pegawai::pluck('nip')->toArray();

        if (empty($users) || empty($pegawaiNips)) {
            return; // Berhenti jika tidak ada data pegawai atau user
        }

        for ($i = 0; $i < 10; $i++) {
            PermohonanCuti::create([
                'user_id' => $users[array_rand($users)],
                'pegawai_nip' => $pegawaiNips[array_rand($pegawaiNips)], // Gunakan NIP yang valid
                'jenis_cuti_id' => rand(1, 4),
                'tanggal_mulai' => now()->addDays(rand(1, 30))->toDateString(),
                'tanggal_selesai' => now()->addDays(rand(31, 60))->toDateString(),
                'alasan' => 'Cuti ' . ['tahunan', 'sakit', 'melahirkan', 'alasan penting'][rand(0, 3)],
                'status' => ['Diajukan', 'Disetujui', 'Ditolak'][rand(0, 2)]
            ]);
        }
    }
}
