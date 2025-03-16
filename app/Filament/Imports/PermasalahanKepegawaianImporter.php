<?php

namespace App\Filament\Imports;

use App\Models\InventarisirPermasalahanKepegawaian;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\DataDukungan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PermasalahanKepegawaianImporter extends Importer
{
    protected static ?string $model = InventarisirPermasalahanKepegawaian::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('pegawai_nip')
                ->example('198501012')
                ->exampleHeader('NIP')
                ->requiredMapping(),
            ImportColumn::make('dataDukungan')
                ->relationship(resolveUsing: function (string $state): ?DataDukungan {
                    return DataDukungan::query()
                        ->where('id', $state)
                        ->orWhere('jenis', $state)
                        ->first();
                }),
            ImportColumn::make('permasalahan')
                ->example('Pengajuan kenaikan pangkat tertunda')
                ->exampleHeader('PERMASALAHAN')
                ->requiredMapping(),
            ImportColumn::make('file_upload')
                ->example('dokumen_pendukung.pdf')
                ->exampleHeader('FILE UPLOAD'),
            ImportColumn::make('surat_pengantar_unit_kerja')
                ->example('surat_pengantar.pdf')
                ->exampleHeader('SURAT PENGANTAR UNIT KERJA'),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label('Update data yang sudah ada')
                ->helperText('Jika dicentang, data yang sudah ada akan diupdate. Jika tidak, hanya akan membuat data baru.'),
        ];
    }

    public function resolveRecord(): ?InventarisirPermasalahanKepegawaian
    {
        try {
            DB::beginTransaction();

            // Create or update Pegawai first
            $pegawai = Pegawai::updateOrCreate(
                ['nip' => $this->data['pegawai_nip']],
                [
                    'nama' => $this->data['nama'] ?? 'Nama Tidak Diketahui',
                    'no_telepon' => $this->data['no_wa'] ?? '0000000000',
                    'unit_kerja_id' => $this->data['unit_kerja_id'] ?? 1,
                    'jabatan' => $this->data['jabatan'] ?? 'Jabatan Tidak Diketahui',
                    'pangkat_golongan' => $this->data['pangkat_golongan'] ?? 'Pangkat/Golongan Tidak Diketahui',
                ]
            );

            // Check if we should update existing records
            if ($this->options['updateExisting'] ?? false) {
                $record = InventarisirPermasalahanKepegawaian::firstOrNew([
                    'pegawai_nip' => $this->data['pegawai_nip'],
                    'data_dukungan_id' => $this->data['data_dukungan_id'],
                ]);
            } else {
                $record = new InventarisirPermasalahanKepegawaian();
            }

            // Set the record attributes
            $record->user_id = Auth::id();
            $record->pegawai_nip = $pegawai->nip;
            $record->permasalahan = $this->data['permasalahan'];
            $record->file_upload = $this->data['file_upload'] ?? null;
            $record->surat_pengantar_unit_kerja = $this->data['surat_pengantar_unit_kerja'] ?? null;

            DB::commit();

            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in resolveRecord: ' . $e->getMessage(), [
                'data' => $this->data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Proses impor selesai. ' . number_format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
