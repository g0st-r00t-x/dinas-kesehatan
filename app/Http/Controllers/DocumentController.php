<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Writer\HTML;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Exception;

class DocumentController extends Controller
{
    private const PUBLIC_DIR = 'surat-keluar/';
    private const TEMP_DIR = 'app/temp/';
    private const IMAGE_WIDTH = 100;
    private const IMAGE_HEIGHT = 100;

    /**
     * Process document template, replace variables and convert to PDF
     *
     * @param string $tempPath Path to the template file
     * @param array $replacements Array of search and replace values
     * @param bool $isClean Whether to clean up temporary files
     * @return string Public URL to generated PDF
     */
    public function processDocument(string $tempPath, array $replacements, bool $isClean = false): string
    {
        try {
            $this->initializeSettings();
            $fullFilePath = $this->validateAndGetTemplatePath($tempPath);
            $this->ensureDirectoryExists(public_path(self::PUBLIC_DIR));

            // Process template and convert to PDF
            $editedFilePath = $this->processTemplateWithReplacements($fullFilePath, $replacements);
            $pdfPath = $this->convertDocxToPdf($editedFilePath);

            // Move PDF to public storage and get final path
            $finalPdfPath = $this->movePdfToStorage($pdfPath);

            // Cleanup
            if ($isClean) {
                $this->cleanupFiles([$editedFilePath, $pdfPath]);
                $this->cleanupReplacementFiles($replacements);
            }

            Notification::make()
                ->title('Dokumen berhasil dibuat')
                ->success()
                ->send();

            return $finalPdfPath;
        } catch (Exception $e) {
            $this->handleError($e);
            throw new Exception('Gagal memproses dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Clean up list of files from direct array of paths
     * 
     * @param array $files Array of file paths to be deleted
     * @return array{success: bool, message: string}
     */
    public function cleanupFiles(array $files): array
    {
        $errors = [];
        $deleted = 0;

        foreach ($files as $file) {
            if (file_exists($file)) {
                if (unlink($file)) {
                    $deleted++;
                } else {
                    $errors[] = "Failed to delete file: $file";
                }
            } else {
                $errors[] = "File not found: $file";
            }
        }

        return [
            'success' => empty($errors),
            'message' => empty($errors)
                ? "$deleted file(s) successfully deleted"
                : implode("\n", $errors)
        ];
    }

    /**
     * Clean up files from replacements array
     * 
     * @param array $replacements Array of replacement items with type and replace keys
     * @return array{success: bool, message: string}
     */
    public function cleanupReplacementFiles(array $replacements): array
    {
        $errors = [];
        $deleted = 0;

        foreach ($replacements as $item) {
            if (($item['type'] ?? 'text') === 'image' && isset($item['replace'])) {
                $file = $item['replace'];

                if (file_exists($file)) {
                    if (unlink($file)) {
                        $deleted++;
                    } else {
                        $errors[] = "Failed to delete file: $file";
                    }
                } else {
                    $errors[] = "File not found: $file";
                }
            }
        }

        return [
            'success' => empty($errors),
            'message' => empty($errors)
                ? "$deleted file(s) successfully deleted"
                : implode("\n", $errors)
        ];
    }

    private function processTemplateWithReplacements(string $templatePath, array $replacements): string
    {
        try {
            $templateProcessor = new TemplateProcessor($templatePath);
            $this->logTemplateVariables($templateProcessor);

            foreach ($replacements as $item) {
                $this->processReplacementItem($templateProcessor, $item);
            }

            return $this->saveProcessedTemplate($templateProcessor);
        } catch (Exception $e) {
            throw new Exception("Template processing failed: {$e->getMessage()}");
        }
    }

    private function processReplacementItem(TemplateProcessor $processor, array $item): void
    {
        try {
            $search = $this->cleanSearchTerm($item['search']);
            $replace = $item['replace'];
            $type = $item['type'] ?? 'text';

            if ($type === 'image') {
                $this->handleImageReplacement($processor, $search, $replace);
            } else {
                $this->replaceTextWithVariants($processor, $search, $replace);
            }
        } catch (Exception $e) {
            $this->logReplacementError($e, $item);
        }
    }

    private function handleImageReplacement(TemplateProcessor $processor, string $search, string $imagePath): void
    {
        if (!file_exists($imagePath)) {
            throw new Exception("Image file not found: {$imagePath}");
        }

        $this->replaceImageWithVariants(
            $processor,
            $search,
            $imagePath,
            self::IMAGE_WIDTH,
            self::IMAGE_HEIGHT
        );
    }

    private function replaceImageWithVariants(
        TemplateProcessor $processor,
        string $search,
        string $imagePath,
        float $width,
        float $height
    ): void {
        foreach ($this->getVariableVariants($search) as $variant) {
            try {
                $processor->setImageValue($variant, [
                    'path' => $imagePath,
                    'width' => $width,
                    'height' => $height,
                    'ratio' => true
                ]);
            } catch (Exception $e) {
                Log::debug("Failed to replace image variant: {$variant}", [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function replaceTextWithVariants(TemplateProcessor $processor, string $search, string $replace): void
    {
        foreach ($this->getVariableVariants($search) as $variant) {
            try {
                $processor->setValue($variant, $replace);
            } catch (Exception $e) {
                Log::debug("Failed to replace text variant: {$variant}", [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function convertDocxToPdf(string $docxPath): string
    {
        try {
            $phpWord = IOFactory::load($docxPath);
            $htmlWriter = new HTML($phpWord);
            $htmlContent = $htmlWriter->getContent();

            $pdfFileName = 'surat_' . time() . '.pdf';
            $pdfPath = public_path(self::PUBLIC_DIR . $pdfFileName);

            PDF::loadHTML($htmlContent)
                ->setPaper('a4')
                ->setWarnings(false)
                ->save($pdfPath);

            if (!file_exists($pdfPath)) {
                throw new Exception("PDF file was not generated");
            }

            return $pdfPath;
        } catch (Exception $e) {
            throw new Exception("PDF conversion failed: {$e->getMessage()}");
        }
    }

    // private function cleanupFiles(string $editedFilePath, string $pdfPath, array $replacements): void
    // {
    //     // Cleanup temporary DOCX
    //     if (file_exists($editedFilePath)) {
    //         unlink($editedFilePath);
    //     }

    //     // Cleanup original PDF
    //     if (file_exists($pdfPath)) {
    //         unlink($pdfPath);
    //     }

    //     // Cleanup image files
    //     foreach ($replacements as $item) {
    //         if (($item['type'] ?? 'text') === 'image' && file_exists($item['replace'])) {
    //             unlink($item['replace']);
    //         }
    //     }
    // }

    private function movePdfToStorage(string $pdfPath): string
    {
        $pdfFileName = 'surat-keluar/' . basename($pdfPath);
        Storage::disk('public')->put($pdfFileName, file_get_contents($pdfPath));
        return $pdfFileName;
    }

    private function initializeSettings(): void
    {
        Settings::setDefaultPaper('Letter');
    }

    private function validateAndGetTemplatePath(string $tempPath): string
    {
        $fullFilePath = public_path('storage/' . $tempPath);
        if (!file_exists($fullFilePath)) {
            throw new Exception("Template file not found at: {$fullFilePath}");
        }
        return $fullFilePath;
    }

    private function ensureDirectoryExists(string $path): void
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    private function cleanSearchTerm(string $search): string
    {
        return trim(ltrim($search, '$'), '{}');
    }

    private function getVariableVariants(string $search): array
    {
        return [
            $search,
            '{' . $search . '}',
            '$' . $search,
            '${' . $search . '}'
        ];
    }

    private function logTemplateVariables(TemplateProcessor $processor): void
    {
        Log::info('Template variables found:', $processor->getVariables());
    }

    private function logReplacementError(Exception $e, array $item): void
    {
        Log::warning("Failed to replace variable: {$item['search']}", [
            'error' => $e->getMessage(),
            'search' => $item['search'],
            'replace' => $item['replace'],
            'type' => $item['type'] ?? 'text'
        ]);
    }

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

    private function saveProcessedTemplate(TemplateProcessor $templateProcessor): string
    {
        $editedFileName = 'edited_' . time() . '.docx';
        $editedFilePath = storage_path(self::TEMP_DIR . $editedFileName);

        if (!file_exists(dirname($editedFilePath))) {
            mkdir(dirname($editedFilePath), 0755, true);
        }

        $templateProcessor->saveAs($editedFilePath);
        return $editedFilePath;
    }
}
