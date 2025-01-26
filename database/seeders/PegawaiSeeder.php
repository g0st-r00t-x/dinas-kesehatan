<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pegawai::create([
            'nama' => 'John Doe',
            'nip' => '123456789',
            'unit_kerja_id' => 1,
            'pangkat_golongan' => 'III/a',
            'jabatan' => 'Staff IT',
            'email' => 'johndoe@example.com',
            'no_telepon' => '081234567890',
            'status_kepegawaian' => 'Aktif',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Kebon Jeruk No. 12, Jakarta'
        ]);

        Pegawai::create([
            'nama' => 'Jane Smith',
            'nip' => '987654321',
            'unit_kerja_id' => 2,
            'pangkat_golongan' => 'II/b',
            'jabatan' => 'Admin Keuangan',
            'email' => 'janesmith@example.com',
            'no_telepon' => '081298765432',
            'status_kepegawaian' => 'Aktif',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Pahlawan No. 23, Bandung'
        ]);

        // Tambahan Data Pegawai
        Pegawai::create([
            'nama' => 'Michael Tan',
            'nip' => '192837465',
            'unit_kerja_id' => 3,
            'pangkat_golongan' => 'IV/a',
            'jabatan' => 'Kepala Divisi',
            'email' => 'michaeltan@example.com',
            'no_telepon' => '081345678901',
            'status_kepegawaian' => 'Aktif',
            'tanggal_lahir' => '1985-08-20',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Gading Serpong No. 5, Tangerang'
        ]);

        Pegawai::create([
            'nama' => 'Sarah Lim',
            'nip' => '564738291',
            'unit_kerja_id' => 4,
            'pangkat_golongan' => 'III/b',
            'jabatan' => 'Staff Hukum',
            'email' => 'sarahlim@example.com',
            'no_telepon' => '081987654321',
            'status_kepegawaian' => 'Aktif',
            'tanggal_lahir' => '1992-12-05',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Taman Anggrek No. 8, Surabaya'
        ]);
    }
}
