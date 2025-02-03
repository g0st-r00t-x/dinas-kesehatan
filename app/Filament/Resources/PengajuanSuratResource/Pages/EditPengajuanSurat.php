<?php

namespace App\Filament\Resources\PengajuanSuratResource\Pages;

use App\Filament\Resources\PengajuanSuratResource;
use App\Models\ArsipSurat;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Exception;

class EditPengajuanSurat extends EditRecord
{
    protected static string $resource = PengajuanSuratResource::class;

    protected function beforeSave(): void
    {
        DB::beginTransaction();
        try {
            // Pastikan 'id' tersedia dalam data
            if (!isset($this->data['id'])) {
                throw new Exception('ID pengajuan surat tidak ditemukan.');
            }

            // Simpan file surat (dummy path untuk testing)
            $filePath = 'Testing/surat.pdf';
            // Jika ingin menyimpan file secara dinamis:
            // $filePath = Storage::put('arsip_surat', $file);

            ArsipSurat::create([
                'id_pengajuan_surat' => $this->data['id'],
                'file_surat_path' => $filePath,
                'tgl_arsip' => now(),
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Gagal Menyimpan Arsip')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Usulan Permohonan Cuti Diperbarui')
            ->success()
            ->body('Data usulan permohonan cuti telah diperbarui dan diarsipkan.')
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
