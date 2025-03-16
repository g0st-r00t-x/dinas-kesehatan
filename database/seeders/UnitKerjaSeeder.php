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
            ['unit_kerja_id' => 001, 'nama' => 'RSUD Kalianget'],
            ['unit_kerja_id' => 002, 'nama' => 'RSUD Sumekar'],
            ['unit_kerja_id' => 003, 'nama' => 'RSUD MOH.Anwar'],
            ['unit_kerja_id' => 004, 'nama' => 'Puskesmas Guluk-Guluk'],
            ['unit_kerja_id' => 005, 'nama' => 'Puskesmas Pragaan'],
        ];

        foreach ($unit_kerja as $data) {
            UnitKerja::updateOrCreate(
                ['unit_kerja_id' => $data['unit_kerja_id'], 'nama' => $data['nama']],
                $data
            );
        }

    }
}
