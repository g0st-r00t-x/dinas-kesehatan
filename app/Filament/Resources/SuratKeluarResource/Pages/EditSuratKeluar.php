<?php

namespace App\Filament\Resources\SuratKeluarResource\Pages;

use App\Filament\Resources\SuratKeluarResource;
use App\Http\Controllers\DocumentController;
use App\Models\JenisSurat;
use App\Models\SuratKeluar;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditSuratKeluar extends EditRecord
{
    protected static string $resource = SuratKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        try {
            // Get current record
            $currentRecord = SuratKeluar::find($this->data['id']);

            // Check if jenis_surat has changed
            if ($currentRecord->id_jenis_surat == $this->data['id_jenis_surat']) {
                // If template hasn't changed, check if other relevant fields have changed
                $relevantFieldsChanged =
                    $currentRecord->nomor_surat !== $this->data['nomor_surat'] ||
                    $currentRecord->perihal !== $this->data['perihal'];

                if (!$relevantFieldsChanged) {
                    // No relevant changes, skip document processing
                    return;
                }
            }

            // Get template data
            $templateSurat = JenisSurat::find($this->data['id_jenis_surat']);

            // Validate template existence
            if (!$templateSurat || empty($templateSurat->template_surat)) {
                throw new \Exception('Template surat tidak ditemukan');
            }

            // Process document if needed
            $filePath = $templateSurat->template_surat;
            $replacements = $this->prepareReplacements();

            // Generate PDF from template
            $documentController = new DocumentController();
            $pdfPath = $documentController->processDocument(
                "$filePath",
                $replacements
            );

            if (empty($pdfPath)) {
                throw new \Exception('Gagal menghasilkan file PDF');
            }

            // Update the record with new PDF path
            SuratKeluar::where('id', $this->data['id'])->update(['file_surat' => $pdfPath]);
            Log::info('Updated_at setelah update', [SuratKeluar::find($this->data['id'])->updated_at]);

            // Show success notification
            Notification::make()
                ->title('Dokumen berhasil diproses')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Log::error('Failed to process document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Notification::make()
                ->title('Gagal memproses dokumen')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Re-throw exception to prevent save
            throw $e;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $this->cleanupTemporaryFiles();
    }

    private function prepareReplacements(): array
    {
        // Add any additional replacements as needed
        return [
            ['search' => 'nomor_surat', 'replace' => $this->data['nomor_surat']],
            ['search' => 'perihal', 'replace' => $this->data['perihal']],
            // Add more fields as needed
        ];
    }

    private function cleanupTemporaryFiles(): void
    {
        try {
            $tempPatterns = [
                'temp/*',
                'surat-keluar/edited_*',
                'surat-keluar/output_*'
            ];

            foreach ($tempPatterns as $pattern) {
                $files = Storage::disk('public')->files(dirname($pattern));

                foreach ($files as $file) {
                    if (strpos(basename($file), basename($pattern, '*')) === 0) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to cleanup temporary files', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
