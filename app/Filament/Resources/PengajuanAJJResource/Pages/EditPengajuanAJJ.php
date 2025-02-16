<?php

namespace App\Filament\Resources\PengajuanAJJResource\Pages;

use App\Filament\Resources\PengajuanAJJResource;
use App\Traits\HandlesFileCleanup;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanAJJ extends EditRecord
{
    use HandlesFileCleanup;

    protected static string $resource = PengajuanAJJResource::class;

    /**
     * Mendefinisikan aksi header untuk halaman ini.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Memutasi data sebelum disimpan.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Simpan nilai file lama sebelum diupdate
        $this->storeOldFileValues();

        return $data;
    }

    /**
     * Aksi setelah data berhasil disimpan.
     */
    protected function afterSave(): void
    {
        // Bersihkan file lama setelah data disimpan
        $this->cleanupOldFiles();
    }
}
