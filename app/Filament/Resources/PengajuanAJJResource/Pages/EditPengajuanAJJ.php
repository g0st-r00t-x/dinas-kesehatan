<?php

namespace App\Filament\Resources\PengajuanAJJResource\Pages;

use App\Filament\Resources\PengajuanAJJResource;
use App\Http\Controllers\DocumentController;
use App\Models\InventarisAJJ;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EditPengajuanAJJ extends EditRecord
{
    protected static string $resource = PengajuanAJJResource::class;

    /**
     * Daftar field yang perlu dibersihkan filenya saat update
     */
    private const FILE_FIELDS = [
        'upload_berkas',
        'surat_pengantar_unit_kerja'
    ];

    /**
     * Get actions for the resource header
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Handle file cleanup before saving the record
     */
    protected function beforeSave(): void
    {
        $currentRecord = $this->getCurrentRecord();
        $documentController = new DocumentController();

        // Bandingkan langsung nilai lama dan baru untuk setiap file field
        foreach (self::FILE_FIELDS as $field) {
            $oldValue = $currentRecord->{$field};
            $newValue = $this->data[$field] ?? null;

            // Log untuk debugging
            Log::info('Comparing file values', [
                'field' => $field,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'is_different' => $oldValue !== $newValue
            ]);

            // Jika nilai berbeda, bersihkan file lama
            if ($oldValue && $oldValue !== $newValue) {
                $this->cleanupFileIfChanged($documentController, $currentRecord, $field);
            }
        }
    }

    /**
     * Get current record with error handling
     */
    private function getCurrentRecord(): InventarisAJJ
    {
        $record = InventarisAJJ::find($this->data['id']);

        if (!$record) {
            Notification::make()
                ->title('Record tidak ditemukan')
                ->danger()
                ->send();

            throw new \Exception('Record tidak ditemukan');
        }

        return $record;
    }

    /**
     * Cleanup file if field value has changed
     */
    private function cleanupFileIfChanged(
        DocumentController $documentController,
        InventarisAJJ $currentRecord,
        string $field
    ): void {
        $oldValue = $currentRecord->{$field};
        $newValue = $this->data[$field] ?? null;

        Log::info('Checking file changes', [
            'field' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'record_id' => $currentRecord->id,
            'is_different' => $oldValue !== $newValue
        ]);

        if ($oldValue && $oldValue !== $newValue) {
            try {
                Log::info('Starting file cleanup', [
                    'field' => $field,
                    'file_to_delete' => $oldValue
                ]);

                $result = $documentController->cleanup([$oldValue]);

                Log::info('Cleanup result', [
                    'result' => $result,
                    'field' => $field
                ]);
            } catch (\Exception $e) {
                Log::error('Error during file cleanup', [
                    'field' => $field,
                    'error' => $e->getMessage()
                ]);

                Notification::make()
                    ->title('Error saat membersihkan file')
                    ->danger()
                    ->body($e->getMessage())
                    ->send();
            }
        }
    }
}
