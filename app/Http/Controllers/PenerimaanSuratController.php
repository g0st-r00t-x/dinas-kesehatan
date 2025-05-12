<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PengajuanSurat;
use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\QrCodeController;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class PenerimaanSuratController extends Controller
{
    /**
     * The Document Controller instance
     * 
     * @var DocumentController
     */
    protected $documentController;

    /**
     * The QR Code Controller instance
     * 
     * @var QrCodeController
     */
    protected $qrCodeController;

    /**
     * Constructor
     * 
     * @param DocumentController $documentController
     * @param QrCodeController $qrCodeController
     */
    public function __construct(DocumentController $documentController, QrCodeController $qrCodeController)
    {
        $this->documentController = $documentController;
        $this->qrCodeController = $qrCodeController;
    }

    /**
     * Handle the letter approval process
     * 
     * @param PengajuanSurat $submission The submission to approve
     * @param string|null $catatan Optional notes for approval
     * @return void
     * @throws Exception
     */
    public function handle(PengajuanSurat $submission, ?string $catatan = null): void
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();

            if (!$user) {
                throw new Exception('User not authenticated.');
            }

            // Check if submission is already processed
            if ($submission->status_pengajuan === 'Diterima') {
                throw new Exception('Surat ini sudah disetujui sebelumnya.');
            } elseif ($submission->status_pengajuan === 'Ditolak') {
                throw new Exception('Surat ini sudah ditolak sebelumnya. Pengaju harus mengajukan ulang.');
            }

            // Get jenis_surat for template
            $jenisSurat = JenisSurat::where('kode_jenis', $submission->kode_jenis_surat)->first();
            if (!$jenisSurat) {
                throw new Exception('Template surat tidak ditemukan.');
            }

            // Generate nomor surat first so we can use it for QR code
            $nomorSurat = $this->generateNomorSurat($jenisSurat);

            // Generate document from template
            $documentPath = $this->generateDocument($submission, $jenisSurat, $nomorSurat);

            // Create entry in SuratKeluar
            $suratKeluar = $this->createSuratKeluar($submission, $documentPath, $jenisSurat, $nomorSurat);

            // Update submission status
            $submission->update([
                'status_pengajuan' => 'Diterima',
                'tgl_persetujuan' => now(),
                'id_penyetuju' => $user->id,
                'catatan' => $catatan,
                'file_surat' => $documentPath,
            ]);

            // Notify the submitter
            $this->sendNotificationToSubmitter($submission, $suratKeluar);

            DB::commit();
            $this->sendSuccessNotification($submission);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Letter approval failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'submission_id' => $submission->id,
                'error' => $e->getMessage()
            ]);

            $this->sendErrorNotification($e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate QR code image and save it to storage
     * 
     * @param string $nomorSurat
     * @param int $submissionId
     * @return string Path to the generated QR code image
     */
    private function generateQrCode(string $nomorSurat, int $submissionId): string
    {
        try {
            // Create QR code content with verification URL
            $verificationUrl = URL::to('/verify-document/' . $submissionId);

            // Prepare mock request for the controller
            $request = new Request();
            $request->merge(['text' => $verificationUrl]);

            // Generate QR code using the controller
            $qrCodeContent = $this->qrCodeController->generate($request);

            // Save QR code image to storage
            $qrImagePath = 'qrcodes/' . Str::slug($nomorSurat) . '-' . time() . '.png';
            Storage::disk('public')->put($qrImagePath, $qrCodeContent);

            Log::info('QR code generated', [
                'path' => $qrImagePath,
                'url' => $verificationUrl
            ]);

            return $qrImagePath;
        } catch (Exception $e) {
            Log::error('QR code generation failed', [
                'error' => $e->getMessage()
            ]);
            throw new Exception("Gagal membuat QR code: " . $e->getMessage());
        }
    }

    /**
     * Generate document from template using DocumentController
     * 
     * @param PengajuanSurat $submission
     * @param JenisSurat $jenisSurat
     * @param string $nomorSurat
     * @return string Document path
     */
    private function generateDocument(PengajuanSurat $submission, JenisSurat $jenisSurat, string $nomorSurat): string
    {
        // Check if template exists
        $templatePath = $jenisSurat->template_content;
        if (empty($templatePath)) {
            throw new Exception('Path template surat tidak ditemukan.');
        }

        // Get the record data
        $recordData = $submission->data_pengajuan;
        $dataPegawai = $recordData['pegawai_data'];
        $pegawaiUnitKerja = UnitKerja::where('unit_kerja_id', $dataPegawai['unit_kerja_id'])->first();
        // Generate QR code
        $qrCodePath = $this->generateQrCode($nomorSurat, $submission->id);

        // Prepare replacements array focusing on the three required fields
        $replacements = [
            [
                'search' => 'perihal',
                'replace' => $recordData['perihal'] ?? $recordData['judul'] ?? $jenisSurat->nama_jenis,
                'type' => 'text'
            ],
            [
                'search' => 'nomor_surat',
                'replace' => $nomorSurat,
                'type' => 'text'
            ],
            [
                'search' => 'qr_code',
                'replace' => $qrCodePath,
                'type' => 'image'
            ],
            [
                'search' => 'nama',
                'replace' => $dataPegawai['nama'],
                'type' => 'text'
            ],
            [
                'search' => 'nip',
                'replace' => $dataPegawai['nip'],
                'type' => 'text'
            ],
            [
                'search' => 'jabatan',
                'replace' => $dataPegawai['jabatan'],
                'type' => 'text'
            ],
            [
                'search' => 'unit_kerja',
                'replace' => $pegawaiUnitKerja->nama,
                'type' => 'text'
            ]
        ];

        // Add any additional standard replacements from the record data
        $additionalReplacements = $this->formatAdditionalReplacements($recordData);
        $replacements = array_merge($replacements, $additionalReplacements);

        try {
            // Use the DocumentController to process the document
            $pdfPath = $this->documentController->processDocument($templatePath, $replacements);

            // Log success
            Log::info('Document generated successfully', [
                'template' => $templatePath,
                'output' => $pdfPath,
                'submission_id' => $submission->id
            ]);

            return $pdfPath;
        } catch (Exception $e) {
            Log::error('Document generation failed', [
                'template' => $templatePath,
                'error' => $e->getMessage(),
                'submission_id' => $submission->id
            ]);
            throw new Exception("Gagal membuat dokumen: " . $e->getMessage());
        }
    }

    /**
     * Format additional replacements array for DocumentController
     * 
     * @param array $data The data to format
     * @return array Formatted replacements
     */
    private function formatAdditionalReplacements(array $data): array
    {
        $replacements = [];

        // Add basic data
        $replacements[] = [
            'search' => 'tanggal_surat',
            'replace' => Carbon::now()->format('d F Y'),
            'type' => 'text'
        ];

        $replacements[] = [
            'search' => 'tanggal_mulai',
            'replace' => $data['tanggal_mulai'] ?? '',
            'type' => 'text'
        ];

        $replacements[] = [
            'search' => 'tanggal_selesai',
            'replace' => $data['tanggal_selesai'] ?? '',
            'type' => 'text'
        ];

        $replacements[] = [
            'search' => 'nama_pemohon',
            'replace' => $data['nama_pemohon'] ?? '',
            'type' => 'text'
        ];

        // Process flat variables
        foreach ($data as $key => $value) {
            // Skip the three main fields that we already handle specially
            if (in_array($key, ['perihal', 'nomor_surat', 'qr_code', 'nama', 'nip', 'jabatan', 'unit_kerja'])) {
                continue;
            }

            if (is_string($value) || is_numeric($value)) {
                $replacements[] = [
                    'search' => $key,
                    'replace' => (string)$value,
                    'type' => 'text'
                ];
            }
        }

        // Process nested pegawai data if available
        if (isset($data['pegawai']) && is_array($data['pegawai'])) {
            foreach ($data['pegawai'] as $key => $value) {
                if (is_string($value) || is_numeric($value)) {
                    $replacements[] = [
                        'search' => "pegawai.$key",
                        'replace' => (string)$value,
                        'type' => 'text'
                    ];
                }
            }
        }

        // Check for any signature images that need to be handled
        if (isset($data['ttd_path']) && !empty($data['ttd_path'])) {
            $replacements[] = [
                'search' => 'ttd_pemohon',
                'replace' => $data['ttd_path'],
                'type' => 'image'
            ];
        }

        if (isset($data['pegawai']['ttd_path']) && !empty($data['pegawai']['ttd_path'])) {
            $replacements[] = [
                'search' => 'ttd_pegawai',
                'replace' => $data['pegawai']['ttd_path'],
                'type' => 'image'
            ];
        }

        return $replacements;
    }

    /**
     * Generate nomor surat based on jenis surat and sequence
     * 
     * @param JenisSurat $jenisSurat
     * @return string
     */
    private function generateNomorSurat(JenisSurat $jenisSurat): string
    {
        $today = Carbon::now();
        $month = $today->format('m');
        $year = $today->format('Y');

        // Get the latest sequence number for this jenis_surat in current month
        $latestSurat = SuratKeluar::where('kode_jenis_surat', $jenisSurat->kode_jenis)
            ->whereYear('tanggal_surat', $year)
            ->whereMonth('tanggal_surat', $month)
            ->latest('nomor_urut')
            ->first();

        $nomorUrut = $latestSurat ? ($latestSurat->nomor_urut + 1) : 1;

        // Format: [Sequence]/[Kode Jenis]/[Month in Roman]/[Year]
        return sprintf(
            '%03d/%s/%s/%s',
            $nomorUrut,
            $jenisSurat->kode_jenis,
            $this->getRomanMonth($month),
            $year
        );
    }

    /**
     * Convert month number to Roman numeral
     * 
     * @param string $month
     * @return string
     */
    private function getRomanMonth(string $month): string
    {
        $romans = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII'
        ];

        return $romans[$month] ?? $month;
    }

    /**
     * Create entry in SuratKeluar
     * 
     * @param PengajuanSurat $submission
     * @param string $documentPath
     * @param JenisSurat $jenisSurat
     * @param string $nomorSurat
     * @return SuratKeluar
     */
    private function createSuratKeluar(PengajuanSurat $submission, string $documentPath, JenisSurat $jenisSurat, string $nomorSurat): SuratKeluar
    {
        // Extract data for SuratKeluar
        $data = $submission->data_pengajuan;

        // Extract sequence number from nomor surat
        preg_match('/^(\d+)\//', $nomorSurat, $matches);
        $nomorUrut = (int)($matches[1] ?? 1);

        // Create the SuratKeluar record
        return SuratKeluar::create([
            'nomor_surat' => $nomorSurat,
            'nomor_urut' => $nomorUrut,
            'perihal' => $data['perihal'] ?? $data['judul'] ?? $jenisSurat->nama_jenis,
            'id_pengajuan' => $submission->id,
            'id_pegawai' => $data['pegawai_data']['id'],
            'tanggal_surat' => now(),
            'id_jenis_surat' => $jenisSurat->id,
            'kode_jenis_surat' => $jenisSurat->kode_jenis,
            'path_file' => $documentPath,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Send notification to the submitter
     * 
     * @param PengajuanSurat $submission
     * @param SuratKeluar $suratKeluar
     * @return void
     */
    private function sendNotificationToSubmitter(PengajuanSurat $submission, SuratKeluar $suratKeluar): void
    {
        $pemohon = User::find($submission->id_pemohon);
        if (!$pemohon) {
            return;
        }

        $jenisSurat = JenisSurat::where('kode_jenis', $submission->kode_jenis_surat)->first();
        $jenisSuratNama = $jenisSurat ? $jenisSurat->nama_jenis : 'Surat';

        $notification = Notification::make()
            ->title("Pengajuan $jenisSuratNama Disetujui")
            ->body("Pengajuan $jenisSuratNama Anda telah disetujui. Dokumen dapat diunduh dari menu Surat Keluar atau Pengajuan Surat.")
            ->success();

        $notification->sendToDatabase($pemohon, isEventDispatched: true);
        event(new DatabaseNotificationsSent($pemohon));
    }

    /**
     * Send success notification
     * 
     * @param PengajuanSurat $submission
     * @return void
     */
    private function sendSuccessNotification(PengajuanSurat $submission): void
    {
        $jenisSurat = JenisSurat::where('kode_jenis', $submission->kode_jenis_surat)->first();
        $jenisSuratNama = $jenisSurat ? $jenisSurat->nama_jenis : 'Surat';

        Notification::make()
            ->title("Berhasil Menyetujui Pengajuan")
            ->success()
            ->body("Pengajuan $jenisSuratNama telah berhasil disetujui dan dokumen telah dibuat")
            ->send();
    }

    /**
     * Send error notification
     * 
     * @param string $message
     * @return void
     */
    private function sendErrorNotification(string $message): void
    {
        Notification::make()
            ->title("Gagal Menyetujui Pengajuan")
            ->danger()
            ->body($message)
            ->send();
    }
}
