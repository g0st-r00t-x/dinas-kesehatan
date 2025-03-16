<?php

namespace App\Filament\Resources\SuratKeluarResource\Pages;

use App\Filament\Resources\SuratKeluarResource;
use App\Http\Controllers\DocumentController;
use Filament\Resources\Pages\CreateRecord;
use App\Models\JenisSurat;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateSuratKeluar extends CreateRecord
{
    protected static string $resource = SuratKeluarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $templateSurat = JenisSurat::find($data['id_jenis_surat']);

        try {
            // Get the uploaded file path
            $filePath = $templateSurat->template_surat;
            Log::info('Processing surat keluar', ['original_path' => $filePath]);

           

            if (empty($filePath)) {
                throw new \Exception("File template tidak ditemukan: {$filePath}");
            }

            $replacements = [
                ['search' => '{nomor_surat}', 'replace' => $this->data['nomor_surat']],
                ['search' => '{perihal}', 'replace' =>
                $this->data['perihal']],
            ];

            // Process the document
            $documentController = new DocumentController();
            $pdfPath = $documentController->processDocument(
                $filePath,
                $replacements
            );

            if (empty($pdfPath)) {
                throw new \Exception('Gagal menghasilkan file PDF');
            }

            // Convert storage path to relative path for database
            $pdfRelativePath = str_replace(storage_path('app/public/'), '', $pdfPath);

            Log::info('Document processed successfully', [
                'original_file' => $filePath,
                'pdf_path' => $pdfRelativePath
            ]);


            // Update file path in data array
            $data['file_surat'] = $pdfRelativePath;

            Notification::make()
                ->title('Dokumen berhasil diproses')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Log::error('Gagal memproses surat keluar', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file_path' => $filePath ?? null
            ]);

            Notification::make()
                ->title('Gagal memproses dokumen')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Cleanup any temporary files
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            // Return original data without modification
            return $data;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {

        // Clear any temporary files in the documents directory
        $this->cleanupTemporaryFiles();
    }

    private function cleanupTemporaryFiles(): void
    {
        try {
            $pattern = storage_path('app/public/documents/edited_*');
            array_map('unlink', glob($pattern));

            $pattern = storage_path('app/public/documents/output_*');
            array_map('unlink', glob($pattern));
        } catch (\Exception $e) {
            Log::warning('Failed to cleanup temporary files', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
