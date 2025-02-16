<?php

namespace App\Http\Controllers;

use App\Services\FileManagerServices;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Writer\HTML;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Exception;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private const STORAGE_DIR = 'surat-keluar/';
    private const TEMP_DIR = 'temp/';
    private const IMAGE_WIDTH = 100;
    private const IMAGE_HEIGHT = 100;

    /**
     * Process document template, replace variables and convert to PDF
     *
     * @param string $templatePath Path to the template file in public storage
     * @param array $replacements Array of search and replace values
     * @return string Public URL to generated PDF
     */
    public function processDocument(string $templatePath, array $replacements): string
    {
        try {
            Settings::setDefaultPaper('Letter');

            // Validate template exists
            $templateFullPath = $this->getPublicPath($templatePath);
            $fullPathTemplate = public_path($templatePath);
            Log::info('Template full path', ['full_path' => $fullPathTemplate, 'template_path' => $templateFullPath]);
            if (!file_exists($templateFullPath)) {
                throw new Exception("Template tidak ditemukan: {$templatePath}");
            }

            // Create necessary directories
            $this->ensureDirectoryExists(self::STORAGE_DIR);
            $this->ensureDirectoryExists(self::TEMP_DIR);

            // Process template
            $processedDocxPath = $this->processTemplateWithReplacements($templateFullPath, $replacements);

            // Convert to PDF
            $pdfPath = $this->convertToPdf($processedDocxPath);

            // Move to final location
            $finalPath = $this->moveToStorage($pdfPath);

            // Cleanup temporary files
            $this->cleanup([
                $processedDocxPath,
                $pdfPath
            ]);

            Notification::make()
                ->title('Dokumen berhasil dibuat')
                ->success()
                ->send();

            return $finalPath;
        } catch (Exception $e) {
            $this->handleError($e);
            throw $e;
        }
    }

    /**
     * Process template with replacements
     */
    private function processTemplateWithReplacements(string $templatePath, array $replacements): string
    {
        try {
            $template = new TemplateProcessor($templatePath);
            Log::info('Template variables found:', $template->getVariables());

            foreach ($replacements as $item) {
                $this->processReplacementItem($template, $item);
            }

            $outputPath = $this->getPublicPath(self::TEMP_DIR . 'processed_' . time() . '.docx');
            $template->saveAs($outputPath);

            return $outputPath;
        } catch (Exception $e) {
            throw new Exception("Template processing failed: {$e->getMessage()}");
        }
    }

    /**
     * Process single replacement item
     */
    private function processReplacementItem(TemplateProcessor $template, array $item): void
    {
        $search = $this->cleanSearchTerm($item['search']);
        $replace = $item['replace'];
        $type = $item['type'] ?? 'text';

        try {
            if ($type === 'image') {
                $this->processImageReplacement($template, $search, $replace);
            } else {
                $this->processTextReplacement($template, $search, $replace);
            }
        } catch (Exception $e) {
            Log::warning("Failed to process replacement", [
                'search' => $search,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Process image replacement
     */
    private function processImageReplacement(TemplateProcessor $template, string $search, string $imagePath): void
    {
        $fullPath = $this->getPublicPath($imagePath);
        if (!file_exists($fullPath)) {
            throw new Exception("Image tidak ditemukan: {$imagePath}");
        }

        foreach ($this->getVariableVariants($search) as $variant) {
            $template->setImageValue($variant, [
                'path' => $fullPath,
                'width' => self::IMAGE_WIDTH,
                'height' => self::IMAGE_HEIGHT,
                'ratio' => true
            ]);
        }
    }

    /**
     * Process text replacement
     */
    private function processTextReplacement(TemplateProcessor $template, string $search, string $replace): void
    {
        foreach ($this->getVariableVariants($search) as $variant) {
            $template->setValue($variant, $replace);
        }
    }

    /**
     * Convert DOCX to PDF
     */
    private function convertToPdf(string $docxPath): string
    {
        $phpWord = IOFactory::load($docxPath);
        $htmlWriter = new HTML($phpWord);
        $htmlContent = $htmlWriter->getContent();

        $pdfPath = $this->getPublicPath(self::TEMP_DIR . 'output_' . time() . '.pdf');

        PDF::loadHTML($htmlContent)
            ->setPaper('a4')
            ->setWarnings(false)
            ->save($pdfPath);

        if (!file_exists($pdfPath)) {
            throw new Exception("PDF generation failed");
        }

        return $pdfPath;
    }

    /**
     * Move file to final storage location
     */
    private function moveToStorage(string $sourcePath): string
    {
        $fileName = self::STORAGE_DIR . basename($sourcePath);
        $destinationPath = $this->getPublicPath($fileName);

        if (!rename($sourcePath, $destinationPath)) {
            throw new Exception("Failed to move file to storage");
        }

        return $fileName;
    }

    /**
     * Clean up temporary files
     */
    public function cleanup(array |string $files): array
    {
        Log::info('DocumentController cleanup called with files:', $files);

        $results = ['success' => true, 'errors' => []];

        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            try {
                $fullPath = storage_path('app/public/' . $file); // sesuaikan dengan path penyimpanan Anda
                Log::info('Attempting to delete file:', ['path' => $fullPath]);

                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    Log::info('File deleted successfully:', ['path' => $fullPath]);
                } else {
                    Log::warning('File not found:', ['path' => $fullPath]);
                }
            } catch (Exception $e) {
                Log::error('Error deleting file:', [
                    'path' => $fullPath ?? null,
                    'error' => $e->getMessage()
                ]);
                $results['success'] = false;
                $results['errors'][] = $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Get full public path
     */
    private function getPublicPath(string $path): string
    {
        return public_path('storage/' . $path);
    }

    /**
     * Ensure directory exists in public storage
     */
    private function ensureDirectoryExists(string $dir): void
    {
        $path = $this->getPublicPath($dir);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Clean search term by removing special characters
     */
    private function cleanSearchTerm(string $search): string
    {
        return trim(ltrim($search, '$'), '{}');
    }

    /**
     * Get variable variants for template replacement
     */
    private function getVariableVariants(string $search): array
    {
        return [
            $search,
            '{' . $search . '}',
            '$' . $search,
            '${' . $search . '}'
        ];
    }

    /**
     * Handle and log errors
     */
    private function handleError(Exception $e): void
    {
        Log::error('Document processing failed', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        Notification::make()
            ->title('Gagal memproses dokumen')
            ->danger()
            ->body($e->getMessage())
            ->send();
    }
}
