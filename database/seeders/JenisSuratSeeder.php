<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data tanpa mengganggu foreign key
        DB::table('jenis_surat')->delete();
        DB::statement('ALTER TABLE jenis_surat AUTO_INCREMENT = 1;');

        // Data yang akan dimasukkan
        $data = [
            ['nama' => 'Permohonan Pensiun', 'kode' => 'PP'],
            ['nama' => 'Permohonan Cuti', 'kode' => 'PC'],
            ['nama' => 'Pemberhentian Sementara dari Jabatan Fungsional', 'kode' => 'PSJF'],
            ['nama' => 'Rekomendasi Penelitian', 'kode' => 'RP'],
            ['nama' => 'SK Berkala', 'kode' => 'SKB'],
            ['nama' => 'Revisi SK Pangkat', 'kode' => 'RSKP'],
        ];

        // Insert data ke tabel jenis_surat
        DB::table('jenis_surat')->insert($data);
    }
}
