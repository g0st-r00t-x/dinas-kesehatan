<?php

namespace App\Filament\Imports;

use App\Models\PermohonanCuti;
use App\Models\Pegawai;
use App\Models\JenisCuti;
use App\Models\UnitKerja;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;

// a.	NAMA
// b.	NIP
// c.	UNIT KERJA
// d.	PANGKAT/GOLONGAN
// e.	PERMOHONA CUTI
// •	CUTO TAHUNAN
// •	CUTI MELAHIRKAN
// •	CUTI SAKIT
// •	CUTI ALASAN PENTING
// •	CUTI BESAR
// •	CUTI DI LUAR TANGGUNGAN
// f.	UPLOAD DATA DUKUNG CUTI TAHUNAN
// g.	UPLOAD DATA DUKUNG CUTI MELAHIRKAN
// h.	 PLOAD DATA DUKUNG CUTI SAKIT 
// i.	UPLOAD DATA DUKUNG CUTI ALASAN PENTING
// j.	UPLOAD DATA DUKUNG CUTI DI LUAR TANGGUNGAN 
// k.	SURAT PENGANTAR UNIT KERJA
// l.	NO TELP/WA YANG BERSANGKUTAN


class PermohonanCutiImporter extends Importer
{
    protected static ?string $model = PermohonanCuti::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nip'),
            ImportColumn::make('nama'),
            ImportColumn::make('unit_kerja_id'),
            ImportColumn::make('pangkat_golongan'),
            ImportColumn::make('tanggal_mulai'),
            ImportColumn::make('tanggal_selesai'),
            ImportColumn::make('alasan'),
            ImportColumn::make('jenis_cuti_id'),
            ImportColumn::make('data_dukungan'),
            ImportColumn::make('surat_pengantar'),
            ImportColumn::make('no_wa'),
            
        ];
    }

public function resolveRecord(): ?PermohonanCuti
{
    $user = Auth::user();

    $this->data['status'] = strtolower($this->data['status'] ?? 'diajukan'); // Normalisasi status

    $pegawai = Pegawai::firstOrCreate(
        ['nip' => $this->data['nip'] ?? '12'],
        [
            'nama' => $this->data['nama'] ?? 'Nama Default',
            'no_telepon' => $this->data['no_wa'] ?? '0000000000',
            'unit_kerja_id' => $this->data['unit_kerja_id'] ?? 1,
            'jenis_cuti_id' => $this->data['alasan'],
            'pangkat_golongan' => $this->data['pangkat_golongan'],
        ]
    );



    return PermohonanCuti::create([
        'user_id' => $user->id,
        'pegawai_nip' => $pegawai->nip,
        'jenis_cuti_id' => $this->data['jenis_cuti_id'],
        'alasan' => $this->data['alasan'],
        'tanggal_mulai' => $this->data['tanggal_mulai'] ?? now(),
        'tanggal_selesai' => $this->data['tanggal_selesai'] ?? now()->addDays(1),
        'data_dukungan' => $this->data['data_dukungan'],
        'surat_pengantar' => $this->data['surat_pengantar'],
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
