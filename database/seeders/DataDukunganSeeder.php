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
            ['jenis' => 'SK PEMBERIAN TUNJANGAN'],
            ['jenis' => 'SK PENCANTUMAN GELAR S-1'],
            ['jenis' => 'IBEL S-1'],
            ['jenis' => 'SK KP TERAKHIR'],
            ['jenis' => 'IJAZAH TERAKHIR'],
        ];

        foreach ($dataDukungans as $data) {
            DataDukungan::updateOrCreate(
                ['jenis' => $data['jenis']],
                $data
            );
        }

        $this->command->info('Data dukungan telah berhasil dimasukkan ke tabel jenis_data_dukungans.');
    }
}
