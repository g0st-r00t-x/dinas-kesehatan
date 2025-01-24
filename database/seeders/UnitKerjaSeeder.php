<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataDukungan;
use App\Models\UnitKerja;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unit_kerja = [
            ['nama' => 'RSUD Kalianget'],
            ['nama' => 'RSUD Sumekar'],
            ['nama' => 'RSUD MOH.Anwar'],
            ['nama' => 'Puskesmas Guluk-Guluk'],
            ['nama' => 'Puskesmas Pragaan'],
        ];

        foreach ($unit_kerja as $data) {
            UnitKerja::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }

    }
}
