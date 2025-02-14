<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelQRCode\Facades\QRCode;

class PenerimaanPengajuan extends Controller
{
    public function __invoke(PengajuanSurat $pengajuan): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Log start of process
            Log::info('Memulai proses persetujuan surat', [
                'pengajuan_id' => $pengajuan->id,
                'user' => auth()->user()->name
            ]);

            // Load pengajuan dengan relasi yang dibutuhkan
            $pengajuan->load(['suratKeluar.jenisSurat', 'pemohon', 'diajukan']);

            // Validate relationships
            if (!$pengajuan->suratKeluar?->jenisSurat) {
                throw new Exception("Data surat keluar atau jenis surat tidak ditemukan");
            }

            // Get and validate template path
            $templateFile = $pengajuan->suratKeluar->jenisSurat->template_surat;
            if (empty($templateFile)) {
                throw new Exception("File template belum diatur pada jenis surat");
            }
            //     Log::error('Template file not found', [
            //         'template_path' => $templatePath,
            //         'template_file' => $templateFile
            //     ]);
            // }

            // Generate dan simpan QR Code
            $qrPath = $this->generateQRCode($pengajuan);
            Log::info('QR Code generated', ['qr_path' => $qrPath]);

            // Proses dokumen
            try {
                $pdfPath = $this->processDocument($pengajuan, $templateFile, $qrPath);
                Log::info('Document processed successfully', ['pdf_path' => $pdfPath]);
            } catch (Exception $e) {
                Log::error('Document processing failed', [
                    'error' => $e->getMessage(),
                    'template_file' => $templateFile,
                ]);
                throw new Exception("Gagal memproses dokumen: " . $e->getMessage());
            }

            // Update status
            $this->updateStatus($pengajuan, $pdfPath);
            Log::info('Status updated successfully', [
                'pengajuan_id' => $pengajuan->id,
                'pdf_path' => $pdfPath
            ]);

            // Cleanup temporary QR Code
            Storage::delete($qrPath);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan surat berhasil disetujui',
                'data' => [
                    'pdf_path' => Storage::url($pdfPath),
                    'approved_at' => now()->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Proses persetujuan surat gagal', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateQRCode(PengajuanSurat $pengajuan): string
    {
        try {
            $qrContent = implode("\n", [
                "Nomor: {$pengajuan->suratKeluar->nomor_surat}",
                "Perihal: {$pengajuan->suratKeluar->perihal}",
                "Disetujui pada: " . now()->format('d/m/Y H:i:s'),
                "Oleh: " . auth()->user()->name
            ]);

            $filename = "qr-codes/surat-{$pengajuan->id}-" . time() . ".png";
            $directory = Storage::path('public/qr-codes');

            // Ensure directory exists
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $fullPath = Storage::path("public/$filename");

            QRCode::text($qrContent)
                ->setOutfile($fullPath)
                ->png();

            Log::info('QR Code generated successfully', [
                'path' => $fullPath,
                'pengajuan_id' => $pengajuan->id
            ]);

            return $filename;
        } catch (Exception $e) {
            Log::error('QR Code generation failed', [
                'error' => $e->getMessage(),
                'pengajuan_id' => $pengajuan->id
            ]);
            throw new Exception("Gagal membuat QR Code: " . $e->getMessage());
        }
    }

    private function processDocument(PengajuanSurat $pengajuan, string $templatePath, string $qrPath): string
    {
        // $replacements = [
        //     '{nomor_surat}' => $pengajuan->suratKeluar->nomor_surat ?? '',
        //     '{perihal}' => $pengajuan->suratKeluar->perihal ?? '',
        //     '{qr-code}' => Storage::url($qrPath),
        //     '{tanggal}' => now()->format('d/m/Y'),
        //     '{pemohon}' => $pengajuan->pemohon->name ?? '',
        //     '{jabatan_pemohon}' => $pengajuan->pemohon->jabatan ?? '',
        //     '{penerima}' => $pengajuan->diajukan->nama ?? '',
        //     '{jabatan_penerima}' => $pengajuan->diajukan->jabatan ?? ''
        // ];

        log::info('QRCode', [
            public_path('storage/' . $qrPath)]);

        $replacements = [
            ['search' => '{nomor_surat}', 'replace' => $pengajuan->suratKeluar->nomor_surat ?? ''],
            ['search' => '{perihal}', 'replace' => $pengajuan->suratKeluar->perihal ?? ''],
            ['search' => '{qr_code}', 'replace' => public_path('storage/' . $qrPath), 'type' => 'image'],
        ];

        Log::info('Processing document with replacements', [
            'template_path' => $templatePath,
            'replacements' => array_keys($replacements)
        ]);

        $documentController = new DocumentController();
        return $documentController->processDocument($templatePath, $replacements, $isClean=true);
    }

    private function updateStatus(PengajuanSurat $pengajuan, string $pdfPath): void
    {
        $pengajuan->update([
            'status_pengajuan' => 'Diterima',
            'tgl_diterima' => now(),
        ]);

        $pengajuan->suratKeluar->update([
            'file_surat' => $pdfPath
        ]);

        Log::info('Status updated', [
            'pengajuan_id' => $pengajuan->id,
            'status' => 'Diterima',
            'pdf_path' => $pdfPath
        ]);
    }
}
