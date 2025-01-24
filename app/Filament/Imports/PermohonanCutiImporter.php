<?php
namespace App\Filament\Imports;

use App\Models\PermohonanCuti;
use App\Models\Pegawai;
use App\Models\JenisCuti;
use App\Models\UnitKerja;
use App\Models\DokumenCuti;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PermohonanCutiImporter extends Importer
{
    protected static ?string $model = PermohonanCuti::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nip')
                ->rules(['required', 'string'])
                ->requiredMapping(),
            ImportColumn::make('nama')
                ->rules(['required', 'string'])
                ->requiredMapping(),
            ImportColumn::make('unit_kerja')
                ->rules(['required', 'string'])
                ->requiredMapping(),
            ImportColumn::make('jenis_cuti')
                ->rules(['required', 'string'])
                ->requiredMapping(),
            ImportColumn::make('no_wa')
                ->rules(['nullable', 'string']),
            ImportColumn::make('alasan')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?PermohonanCuti
{
   return DB::transaction(function () {
       // Pastikan jenis cuti valid
       $jenisCuti = JenisCuti::where('nama', $this->data['jenis_cuti'])->first();
       if (!$jenisCuti) {
           return null;
       }

       // Cari atau buat unit kerja
       $unitKerja = UnitKerja::firstOrCreate(
           ['nama' => $this->data['unit_kerja']]
       );

       // Find pegawai
        $pegawai = Pegawai::find(
            ['nip' => $this->data['nip']]
        );

        //if pegawai is not found
        if (!$pegawai) {
            $pegawai = Pegawai::create(
            [
                'nip' => $this->data['nip'],
                'nama' => $this->data['nama'],
                'unit_kerja_id' => $unitKerja->id,
                'no_wa' => $this->data['no_wa'] ?? null,
            ]
        );
        }



       // Buat permohonan cuti
       $permohonanCuti = PermohonanCuti::create([
           'pegawai_id' => $pegawai->id,
           'jenis_cuti_id' => $jenisCuti->id,
           'alasan' => $this->data['alasan'] ?? null,
           'no_wa' => $this->data['no_wa'] ?? null,
       ]);

       // Tambahkan dokumen cuti jika ada
    //    if (!empty($this->data['dokumen_cuti'])) {
    //        $permohonanCuti->dokumenCuti()->createMany(
    //            collect($this->data['dokumen_cuti'])->map(function ($dokumen) {
    //                return [
    //                    'jenis_dokumen' => $dokumen['jenis_dokumen'] ?? null,
    //                    'path_file' => $dokumen['path_file'] ?? null,
    //                ];
    //            })->toArray()
    //        );
    //    }

       return $permohonanCuti;
   });
}

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Leave request import completed. ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported successfully.';
        
        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }
        
        return $body;
    }
}