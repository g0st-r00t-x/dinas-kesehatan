<?php

namespace App\Filament\Imports;

use App\Models\PermohonanCuti;
use App\Models\Pegawai;
use App\Models\JenisCuti;
use App\Models\UnitKerja;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PermohonanCutiImporter extends Importer
{
    protected static ?string $model = PermohonanCuti::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('alasan'),
            ImportColumn::make('jenis_cuti_id'),
        ];
    }

public function resolveRecord(): ?PermohonanCuti
{
    $this->data['status'] = strtolower($this->data['status'] ?? 'diajukan'); // Normalisasi status

    $pegawai = Pegawai::firstOrCreate(
        ['nip' => $this->data['nip'] ?? '12'],
        [
            'nama' => $this->data['nama'] ?? 'Nama Default',
            'no_telepon' => $this->data['no_wa'] ?? '0000000000',
            'unit_kerja_id' => $this->data['unit_kerja_id'] ?? 1,
        ]
    );



    return PermohonanCuti::create([
        'pegawai_nip' => $pegawai->nip,
        'jenis_cuti_id' => $this->data['jenis_cuti_id'],
        'alasan' => $this->data['alasan'],
        'tanggal_mulai' => $this->data['tanggal_mulai'] ?? now(),
        'tanggal_selesai' => $this->data['tanggal_selesai'] ?? now()->addDays(1),
        'status' => $this->data['status'],
    ]);
}




    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Proses impor permohonan cuti selesai. ' . number_format($import->successful_rows) . ' baris berhasil diimpor.';
       
        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diimpor.';
        }
       
        return $body;
    }
}
