<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\PengajuanSurat;
use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PengajuanSuratController extends Controller
{
    /**
     * Handle the letter submission process
     * 
     * @param Model $record The record being submitted
     * @param string $type The type of submission
     * @return void
     * @throws Exception
     */
    public function handle(Model $record, string $type = ''): void
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();

            if (!$user) {
                throw new Exception('User not authenticated.');
            }
            // Dynamic property access based on model type
            $pegawaiId = $record->id_pegawai ?? $record->pegawai_nip ?? null;
            if (!$pegawaiId) {
                throw new Exception('ID pegawai tidak ditemukan pada record.');
            }

            $pegawai = $this->findPegawai($pegawaiId, 'nip');

            // Determine the submission type and get jenis_surat record
            $kodeJenisSurat = $this->getKodeJenisSurat($record, $type);
            $jenisSurat = $this->findJenisSurat($kodeJenisSurat);
            $submissionType = $this->determineSubmissionType($record);

            // dd($submissionType);

            // Get the perihal (subject) from the record
            $perihal = $record->perihal ?? $record->judul ?? ($type ?? 'Surat');

            // Check for existing submission (using record class and ID for flexibility)
            $recordType = get_class($record);
            $existingSubmission = PengajuanSurat::where('id_pengajuan', $record->id)
                ->where('id_pemohon', $user->id)
                ->where('jenis_pengajuan_type', $recordType)
                ->first();

            if ($existingSubmission) {
                // If submission exists and is active, throw exception
                if ($existingSubmission->status_pengajuan === 'Diajukan') {
                    throw new Exception("$submissionType ini sudah diajukan dan masih dalam proses.");
                } else if ($existingSubmission->status_pengajuan === 'Diterima') {
                    throw new Exception("$submissionType ini sudah diterima.");
                }

                // If submission was rejected, update it
                if ($existingSubmission->status_pengajuan === 'Ditolak') {
                    $existingSubmission->update([
                        'status_pengajuan' => 'Diajukan',
                        'tgl_pengajuan' => now(),
                    ]);

                    $this->sendNotifications($pegawai, $record, $submissionType, $perihal);
                    DB::commit();
                    $this->sendSuccessNotification($perihal, $submissionType);
                    return;
                }
            }
            // Create new submission if no existing record found
            $this->createPengajuanSurat($user, $pegawai, $record, $recordType, $kodeJenisSurat);
            $this->sendNotifications($pegawai, $record, $submissionType, $perihal);

            DB::commit();
            $this->sendSuccessNotification($perihal, $submissionType);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Letter submission failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'record_id' => $record->id,
                'record_type' => get_class($record),
                'type' => $type,
                'error' => $e->getMessage()
            ]);

            $this->sendErrorNotification($e->getMessage());
        }
    }

    /**
     * Get the kode_jenis_surat for a record
     * 
     * @param Model $record
     * @param string|null $type
     * @return string
     */
    private function getKodeJenisSurat(Model $record, ?string $type): string
    {
        // First check if record has the kode directly
        if (property_exists($record, 'kode_jenis_surat') && $record->kode_jenis_surat) {
            return $record->kode_jenis_surat;
        }

        // Map record types to jenis_surat kode
        $className = class_basename(get_class($record));

        $typeMap = [
            'UsulanSkPemberhentianSementara' => 'PSJF',
            'PermohonanCuti' => 'PC',
            'UsulanPermohonanPensiun' => 'PP',
            'UsulanRekomendasiPenelitian' => 'RP',
            'UsulanRevisiSkPangkat' => 'RSKP',
            'UsulanSkBerkala' => 'SKB',
            'InventarisAJJ' => 'PAJJ',
            // Add more mappings as needed
        ];

        // If type provided, try to match with existing JenisSurat
        if ($type) {
            $jenisSurat = JenisSurat::where('nama_jenis', 'like', "%$type%")->first();
            if ($jenisSurat) {
                return $jenisSurat->kode_jenis;
            }
        }
        // dd($typeMap[$className]);
        return $typeMap[$className];
    }

    /**
     * Find JenisSurat by kode_jenis
     * 
     * @param string $kodeJenis
     * @return JenisSurat|null
     */
    private function findJenisSurat(string $kodeJenis): ?JenisSurat
    {
        return JenisSurat::where('kode_jenis', $kodeJenis)->first();
    }

    /**
     * Determine the submission type based on record class
     * 
     * @param Model $record
     * @return string
     */
    private function determineSubmissionType(Model $record): string
    {
        $className = class_basename(get_class($record));
        // dd($className);
        $typeMap = [
            'SuratKeluar' => 'Surat Keluar',
            'UsulanSKPemberhentianSementara' => 'SK Pemberhentian Sementara',
            'PermohonanCuti' => 'Permohonan Cuti',
            'PermohonanPensiun' => 'Permohonan Pensiun',
            'RekomendasiPenelitian' => 'Rekomendasi Penelitian',
            'UsulanRevisiSkPangkat' => 'Revisi SK Pangkat',
            // Add more mappings as needed
        ];

        return $typeMap[$className] ?? 'Surat';
    }

    /**
     * Find employee by ID
     * 
     * @param string $id
     * @return Pegawai
     * @throws Exception
     */
    private function findPegawai(string $id, string $column): Pegawai
    {
        $pegawai = Pegawai::where($column ?? 'id', $id)->first();


        if (!$pegawai) {
            throw new Exception('Pegawai tidak ditemukan.');
        }

        return $pegawai;
    }

    /**
     * Create new letter submission
     * 
     * @param User $user
     * @param Pegawai $pegawai
     * @param Model $record
     * @param string $recordType
     * @param string $kodeJenisSurat
     * @return PengajuanSurat
     */
    /**
     * Create new letter submission
     * 
     * @param User $user
     * @param Pegawai $pegawai
     * @param Model $record
     * @param string $recordType
     * @param string $kodeJenisSurat
     * @return PengajuanSurat
     */
    private function createPengajuanSurat(User $user, Pegawai $pegawai, Model $record, string $recordType, string $kodeJenisSurat): PengajuanSurat
    {
        try {
            // Generate reference number for tracking
            $noRef = $this->generateReferenceNumber($kodeJenisSurat);
            if (empty($noRef)) {
                throw new Exception('Gagal mengenerate nomor referensi');
            }

            return PengajuanSurat::create([
                'id_pemohon' => $user->id,
                'id_diajukan' => $pegawai->id,
                'id_pengajuan' => $record->id,
                'jenis_pengajuan_id' => $record->id,
                'jenis_pengajuan_type' => $recordType, // Store the type of document being submitted
                'kode_jenis_surat' => $kodeJenisSurat, // Store reference to jenis_surat
                'no_referensi' => $noRef,
                'status_pengajuan' => 'Diajukan',
                'tgl_pengajuan' => now(),
                'data_pengajuan' => $this->extractRecordData($record), // Store record data for template generation
            ]);
        } catch (Exception $e) {
            throw new Exception('Gagal Mengajukan Surat lai: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique reference number
     * 
     * @param string $kodeJenisSurat
     * @return string
     */
    private function generateReferenceNumber(string $kodeJenisSurat): string
    {
        $today = now();
        $count = PengajuanSurat::whereDate('created_at', $today)->count() + 1;

        return $kodeJenisSurat . '/' .
            $today->format('Ymd') . '/' .
            str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Extract relevant data from record for template generation
     * 
     * @param Model $record
     * @return array
     */
    private function extractRecordData(Model $record): array
    {
        // Get all attributes
        $attributes = $record->getAttributes();

        // Add relational data if needed
        // This is a simplified example, you might want to customize this
        // based on the specific record types and their relationships
        if (method_exists($record, 'pegawai') && $record->pegawai) {
            $attributes['pegawai_data'] = $record->pegawai->toArray();
        }

        return $attributes;
    }

    /**
     * Send notifications to operators
     * 
     * @param Pegawai $pegawai
     * @param Model $record
     * @param string $submissionType
     * @param string $perihal
     * @return void
     */
    private function sendNotifications(Pegawai $pegawai, Model $record, string $submissionType, string $perihal): void
    {
        $operators = User::permission('view_any_pengajuan::surat')->get();

        foreach ($operators as $operator) {
            $notification = Notification::make()
                ->title("Pengajuan {$submissionType} Baru")
                ->body("Terdapat pengajuan {$perihal} baru dari {$pegawai->nama} yang memerlukan persetujuan")
                ->success();

            $notification->sendToDatabase($operator, isEventDispatched: true);
            event(new DatabaseNotificationsSent($operator));
        }
    }

    /**
     * Send success notification
     * 
     * @param string $perihal
     * @param string $submissionType
     * @return void
     */
    private function sendSuccessNotification(string $perihal, string $submissionType = null): void
    {
        $title = $submissionType;

        Notification::make()
            ->title("Berhasil Mengajukan {$title}")
            ->success()
            ->body("Pengajuan {$perihal} telah berhasil disubmit")
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
            ->title("Gagal Mengajukan Surat")
            ->danger()
            ->body($message)
            ->send();
    }
}
