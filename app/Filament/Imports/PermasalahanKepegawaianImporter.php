<?php

namespace App\Filament\Imports;

use App\Models\InventarisirPermasalahanKepegawaian;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\DataDukungan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PermasalahanKepegawaianImporter extends Importer
{
    protected static ?string $model = InventarisirPermasalahanKepegawaian::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('pegawai_nip'),
            ImportColumn::make('data_dukungan_id'),
            ImportColumn::make('permasalahan'),
            ImportColumn::make('file_upload'),
            ImportColumn::make('surat_pengantar_unit_kerja'),
        ];
    }

    public function resolveRecord(): ?InventarisirPermasalahanKepegawaian
    {
        // Cari atau buat Pegawai berdasarkan NIP
        $pegawai = Pegawai::firstOrCreate(
            ['nip' => $this->data['pegawai_nip'] ?? '0000000000'], // Default NIP
            [
                'nama' => $this->data['nama'] ?? 'Nama Tidak Diketahui',
                'no_telepon' => $this->data['no_wa'] ?? '0000000000',
                'unit_kerja_id' => $this->data['unit_kerja_id'] ?? 1,
            ]
        );


        // Validasi Pegawai
        if (!$pegawai) {
            throw new \Exception('Pegawai tidak ditemukan atau gagal dibuat.');
        }

        // Cari atau buat DataDukungan
        // Cari atau buat DataDukungan baru
    $dataDukungan = DataDukungan::firstOrCreate([
        // sesuaikan dengan field yang ada di model DataDukungan
        'data_dukungan_id' => $this->data['data_dukungan_id'],
        // tambahkan field lain yang diperlukan
    ]);

        // Buat atau update record
        return InventarisirPermasalahanKepegawaian::create([
            'pegawai_nip' => $pegawai->nip,
            'permasalahan' => $this->data['permasalahan'] ?? 'Permasalahan tidak diketahui',
            'data_dukungan_id' => $this->data['data_dukungan_id'],
            'file_upload' => $this->data['file_upload'] ?? null,
            'surat_pengantar_unit_kerja' => $this->data['surat_pengantar_unit_kerja'] ?? null,
        ]);
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Proses impor inventarisir permasalahan kepegawaian selesai. ' . number_format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
