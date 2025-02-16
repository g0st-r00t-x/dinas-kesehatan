<?php

namespace App\Traits;

use App\Http\Controllers\DocumentController;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

trait HandlesFileCleanup
{
  // Define the constant for file fields directly in the trait
  protected const FILE_FIELDS = [
    'upload_berkas',
    'surat_pengantar_unit_kerja'
  ];

  // Menyimpan nilai file lama
  private array $oldFileValues = [];

  /**
   * Simpan nilai file lama sebelum disimpan.
   */
  protected function storeOldFileValues(): void
  {
    foreach (self::FILE_FIELDS as $field) {
      $this->oldFileValues[$field] = $this->record->{$field};
    }

    Log::info('Stored old file values', [
      'old_values' => $this->oldFileValues,
    ]);
  }

  /**
   * Bersihkan file lama setelah data disimpan.
   */
  protected function cleanupOldFiles(): void
  {
    $documentController = new DocumentController();

    foreach (self::FILE_FIELDS as $field) {
      $this->handleFileCleanup($field, $documentController);
    }
  }

  /**
   * Meng-handle proses pembersihan file lama.
   */
  private function handleFileCleanup(string $field, DocumentController $documentController): void
  {
    $oldValue = $this->oldFileValues[$field] ?? null;
    $newValue = $this->record->{$field};

    Log::info('Comparing file values', [
      'field' => $field,
      'old_value' => $oldValue,
      'new_value' => $newValue,
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
          'field' => $field,
          'result' => $result
        ]);

        if (!$result['success']) {
          $this->notifyError("Gagal menghapus file lama pada field: {$field}", $result['errors']);
        }
      } catch (\Exception $e) {
        Log::error('Error during file cleanup', [
          'field' => $field,
          'error' => $e->getMessage()
        ]);

        $this->notifyError('Error saat membersihkan file', [$e->getMessage()]);
      }
    }
  }

  /**
   * Mengirim notifikasi error.
   */
  private function notifyError(string $title, array $errors): void
  {
    Notification::make()
      ->title($title)
      ->danger()
      ->body(implode(', ', $errors))
      ->send();
  }
}
