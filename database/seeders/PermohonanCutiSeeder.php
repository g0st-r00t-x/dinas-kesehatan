<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermohonanCuti;

class PermohonanCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PermohonanCuti::create([
            'pegawai_id' => 1,
            'jenis_cuti_id' => 1,
            'tanggal_mulai' => '2025-02-01',
            'tanggal_selesai' => '2025-02-07',
            'alasan' => 'Cuti tahunan',
            'status' => 'Disetujui'
        ]);

        PermohonanCuti::create([
            'pegawai_id' => 2,
            'jenis_cuti_id' => 2,
            'tanggal_mulai' => '2025-03-01',
            'tanggal_selesai' => '2025-03-10',
            'alasan' => 'Cuti melahirkan',
            'status' => 'Diajukan'
        ]);

        // Tambahan Data Permohonan Cuti
        PermohonanCuti::create([
            'pegawai_id' => 3,
            'jenis_cuti_id' => 3,
            'tanggal_mulai' => '2025-04-15',
            'tanggal_selesai' => '2025-04-20',
            'alasan' => 'Cuti sakit',
            'status' => 'Disetujui'
        ]);

        PermohonanCuti::create([
            'pegawai_id' => 4,
            'jenis_cuti_id' => 4,
            'tanggal_mulai' => '2025-05-05',
            'tanggal_selesai' => '2025-05-12',
            'alasan' => 'Cuti alasan penting',
            'status' => 'Diajukan'
        ]);
    }
}
