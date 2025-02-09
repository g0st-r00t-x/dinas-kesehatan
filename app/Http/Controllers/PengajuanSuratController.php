<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\PengajuanSurat;
use App\Models\SuratKeluar;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class PengajuanSuratController extends Controller
{
    /**
     * Handle the letter submission process
     * 
     * @param SuratKeluar $record
     * @return void
     * @throws Exception
     */
    public function handle(SuratKeluar $record): void
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();

            if (!$user) {
                throw new Exception('User not authenticated.');
            }

            $pegawai = $this->findPegawai($record->id_pegawai);
            $pengajuanSurat = $this->createPengajuanSurat($user, $pegawai, $record);
            $this->sendNotifications($pegawai, $record);

            DB::commit();

            $this->sendSuccessNotification($record->perihal);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Letter submission failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'record_id' => $record->id,
                'error' => $e->getMessage()
            ]);

            $this->sendErrorNotification($e->getMessage());
        }
    }

    /**
     * Find employee by NIP
     * 
     * @param string $nip
     * @return Pegawai
     * @throws Exception
     */
    private function findPegawai(string $id): Pegawai
    {
        $pegawai = Pegawai::where('id', $id)->first();

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
     * @param SuratKeluar $record
     * @return PengajuanSurat
     */
    private function createPengajuanSurat(User $user, Pegawai $pegawai, SuratKeluar $record): PengajuanSurat
    {
        return PengajuanSurat::create([
            'id_pemohon' => $user->id,
            'id_diajukan' => $pegawai->id,
            'id_pengajuan' => $record->id,
            'status_pengajuan' => 'Diajukan',
            'tgl_pengajuan' => now(),
        ]);
    }

    /**
     * Send notifications to operators
     * 
     * @param Pegawai $pegawai
     * @param SuratKeluar $record
     * @return void
     */
    private function sendNotifications(Pegawai $pegawai, SuratKeluar $record): void
    {
        $operators = User::permission('view_any_pengajuan::surat')->get();

        foreach ($operators as $operator) {
            $notification = Notification::make()
                ->title("Pengajuan {$record->perihal} Baru")
                ->body("Terdapat pengajuan {$record->perihal} baru dari {$pegawai->nama} yang memerlukan persetujuan")
                ->success();

            $notification->sendToDatabase($operator, isEventDispatched: true);
            event(new DatabaseNotificationsSent($operator));
        }
    }

    /**
     * Send success notification
     * 
     * @param string $perihal
     * @return void
     */
    private function sendSuccessNotification(string $perihal): void
    {
        Notification::make()
            ->title("Berhasil Mengajukan {$perihal}")
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
