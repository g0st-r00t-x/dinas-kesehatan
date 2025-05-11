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
        // Hapus data tanpa melanggar foreign key constraints
        DB::table('jenis_surat')->delete();

        // Data yang akan dimasukkan
        $data = [
            [
                'id'=> random_int(1000000, 9999900),
                'kode_jenis' => 'PP',
                'nama_jenis' => 'Permohonan Pensiun',
                'deskripsi' => 'Surat untuk mengajukan pensiun.',
                'template_content' => null,
                'active' => true,
            ],
            [
                'id' => random_int(1000000, 9999900),
                'kode_jenis' => 'PC',
                'nama_jenis' => 'Permohonan Cuti',
                'deskripsi' => 'Surat untuk permohonan cuti pegawai.',
                'template_content' => null,
                'active' => true,
            ],
            [
                'id' => random_int(1000000, 9999900),
                'kode_jenis' => 'PSJF',
                'nama_jenis' => 'Pemberhentian Sementara dari Jabatan Fungsional',
                'deskripsi' => 'Surat pemberhentian sementara dari jabatan fungsional.',
                'template_content' => null,
                'active' => true,
            ],
            [
                'id' => random_int(1000000, 9999900),
                'kode_jenis' => 'RP',
                'nama_jenis' => 'Rekomendasi Penelitian',
                'deskripsi' => 'Surat rekomendasi untuk keperluan penelitian.',
                'template_content' => null,
                'active' => true,
            ],
            [
                'id'=> random_int(1000000, 9999900),
                'kode_jenis' => 'SKB',
                'nama_jenis' => 'SK Berkala',
                'deskripsi' => 'Surat keputusan berkala.',
                'template_content' => null,
                'active' => true,
            ],
            [
                'id'=> random_int(1000000, 9999900),
                'kode_jenis' => 'RSKP',
                'nama_jenis' => 'Revisi SK Pangkat',
                'deskripsi' => 'Surat revisi SK pangkat pegawai.',
                'template_content' => null,
                'active' => true,
            ],
            [
                'id'=> random_int(1000000, 9999900),
                'kode_jenis' => 'PAJJ',
                'nama_jenis' => 'Pengajuan Alih Jenjang Jabatan',
                'deskripsi' => 'Surat pengajuan alih jabatan.',
                'template_content' => null,
                'active' => true,
            ]
        ];

        // Insert data ke tabel jenis_surat
        DB::table('jenis_surat')->insert($data);
    }
}
