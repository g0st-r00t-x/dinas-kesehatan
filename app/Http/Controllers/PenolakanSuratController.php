<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PengajuanSurat;
use App\Models\JenisSurat;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class PenolakanSuratController extends Controller
{
  /**
   * Handle the letter rejection process
   * 
   * @param PengajuanSurat $submission The submission to reject
   * @param string $alasan Reason for rejection
   * @return void
   * @throws Exception
   */
  public function handle(PengajuanSurat $submission, string $alasan): void
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
        throw new Exception('Surat ini sudah ditolak sebelumnya.');
      }

      // Update submission status
      $submission->update([
        'status_pengajuan' => 'Ditolak',
        'tgl_penolakan' => now(),
        'id_penolak' => $user->id,
        'alasan_penolakan' => $alasan,
      ]);

      // Notify the submitter
      $this->sendNotificationToSubmitter($submission, $alasan);

      DB::commit();
      $this->sendSuccessNotification($submission);
    } catch (Exception $e) {
      DB::rollBack();

      Log::error('Letter rejection failed: ' . $e->getMessage(), [
        'user_id' => auth()->id(),
        'submission_id' => $submission->id,
        'error' => $e->getMessage()
      ]);

      $this->sendErrorNotification($e->getMessage());
    }
  }

  /**
   * Send notification to the submitter
   * 
   * @param PengajuanSurat $submission
   * @param string $alasan
   * @return void
   */
  private function sendNotificationToSubmitter(PengajuanSurat $submission, string $alasan): void
  {
    $pemohon = User::find($submission->id_pemohon);
    if (!$pemohon) {
      return;
    }

    $jenisSurat = JenisSurat::where('kode_jenis', $submission->kode_jenis_surat)->first();
    $jenisSuratNama = $jenisSurat ? $jenisSurat->nama_jenis : 'Surat';

    $notification = Notification::make()
      ->title("Pengajuan $jenisSuratNama Ditolak")
      ->body("Pengajuan $jenisSuratNama Anda ditolak dengan alasan: $alasan. Anda dapat mengajukan ulang jika diperlukan.")
      ->danger();

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
      ->title("Pengajuan Berhasil Ditolak")
      ->success()
      ->body("Pengajuan $jenisSuratNama telah berhasil ditolak")
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
      ->title("Gagal Menolak Pengajuan")
      ->danger()
      ->body($message)
      ->send();
  }
}
Gagal menyetujui pengajuan surat.