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
use Exception;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    private const PUBLIC_DIR = 'surat-keluar/';

    /**
     * Process document template, replace variables and convert to PDF
     *
     * @param string $tempPath Path to the template file
     * @param array $replacements Array of search and replace values
     * @return string Public URL to generated PDF
     */
    public function processDocument(string $tempPath, $replacements): string
    {
        try {
            Settings::setDefaultPaper('Letter');
            $fullFilePath = storage_path('app/' . $tempPath);

            Log::info('Full Path', [$fullFilePath]);

            // Validate template file
            if (!file_exists($fullFilePath)) {
                throw new Exception("Template file not found at: {$fullFilePath}");
            }

            // Create public directory if it doesn't exist
            $publicPath = public_path(self::PUBLIC_DIR);
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // Process template with replacements
            $editedFilePath = $this->processTemplateWithReplacements(
                $fullFilePath,
                $replacements
            );

            // Convert to PDF
            $pdfPath = $this->convertDocxToPdf($editedFilePath);

            // Cleanup temporary DOCX file
            if (file_exists($editedFilePath)) {
                unlink($editedFilePath);
            }

            // **Pindahkan hasil PDF ke public storage**
            $pdfFileName = 'surat-keluar/' . basename($pdfPath);
            Storage::disk('public')->put($pdfFileName, file_get_contents($pdfPath));

            // Hapus file PDF dari direktori awal setelah dipindahkan ke storage
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            // Mengembalikan path relatif dari penyimpanan publik
            return $pdfFileName;
        } catch (Exception $e) {
            Log::error('Document processing failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'template_path' => $tempPath ?? 'not set'
            ]);

            throw new Exception('Gagal memproses dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Process template with replacement values
     *
     * @param string $templatePath
     * @param array<string, string> $replacements
     * @return string Path to processed DOCX
     * @throws Exception
     */
    /**
     * Process template with replacement values
     * Handles both ${variable} and {variable} formats
     *
     * @param string $templatePath
     * @param array<string, string> $replacements
     * @return string Path to processed DOCX
     * @throws Exception
     */
    private function processTemplateWithReplacements(string $templatePath, array $replacements): string
    {
        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            // Get and log available variables
            $variables = $templateProcessor->getVariables();
            Log::info('Template variables found:', $variables);

            foreach ($replacements as $item) {
                try {
                    $search = $item['search'];
                    $replace = $item['replace'];

                    // Remove any leading $ if present
                    $search = ltrim($search, '$');

                    // Remove curly braces if present
                    $search = trim($search, '{}');

                    // Try to replace both with and without curly braces
                    try {
                        $templateProcessor->setValue($search, $replace);
                    } catch (Exception $e) {
                        // If that fails, try with curly braces
                        $templateProcessor->setValue('{' . $search . '}', $replace);
                    }

                    // Also try to replace any instances with $ prefix
                    try {
                        $templateProcessor->setValue('$' . $search, $replace);
                    } catch (Exception $e) {
                        // If that fails, try with ${variable} format
                        $templateProcessor->setValue('${' . $search . '}', $replace);
                    }
                } catch (Exception $e) {
                    Log::warning("Failed to replace variable: {$search}", [
                        'error' => $e->getMessage(),
                        'search' => $search,
                        'replace' => $replace
                    ]);
                }
            }

            // Save processed document to temporary storage
            $editedFileName = 'edited_' . time() . '.docx';
            $editedFilePath = storage_path('app/temp/' . $editedFileName);

            // Create temp directory if it doesn't exist
            if (!file_exists(dirname($editedFilePath))) {
                mkdir(dirname($editedFilePath), 0755, true);
            }

            $templateProcessor->saveAs($editedFilePath);

            return $editedFilePath;
        } catch (Exception $e) {
            throw new Exception("Template processing failed: {$e->getMessage()}");
        }
    }

    /**
     * Convert DOCX to PDF
     *
     * @param string $docxPath
     * @return string Path to generated PDF
     * @throws Exception
     */
    private function convertDocxToPdf(string $docxPath): string
    {
        try {
            // Load DOCX
            $phpWord = IOFactory::load($docxPath);

            // Convert to HTML
            $htmlWriter = new HTML($phpWord);
            $htmlContent = $htmlWriter->getContent();

            // Generate PDF with timestamp and sanitized filename
            $timestamp = time();
            $pdfFileName = 'surat_' . $timestamp . '.pdf';
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
}
