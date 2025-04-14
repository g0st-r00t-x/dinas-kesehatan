<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataDukungan;

class DataDukunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataDukungans = [
            ['data_dukungan_id' => 001,'jenis' => 'SK PEMBERIAN TUNJANGAN'],
            ['data_dukungan_id' => 002,'jenis' => 'SK PENCANTUMAN GELAR S-1'],
            ['data_dukungan_id' => 003,'jenis' => 'IBEL S-1'],
            ['data_dukungan_id' => 004,'jenis' => 'SK KP TERAKHIR'],
            ['data_dukungan_id' => 005,'jenis' => 'IJAZAH TERAKHIR'],
        ];

        foreach ($dataDukungans as $data) {
            DataDukungan::updateOrCreate(
                ['data_dukungan_id' => $data['data_dukungan_id'], 'jenis' => $data['jenis']],
                $data
            );
        }

        $this->command->info('Data dukungan telah berhasil dimasukkan ke tabel jenis_data_dukungans.');
    }
}
