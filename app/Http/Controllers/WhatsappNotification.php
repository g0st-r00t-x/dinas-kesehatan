<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PermohonanCuti;
class WhatsappNotification extends Controller
{

    public function send($target, $message)
    {
        if (empty($target) || empty($message)) {
            Log::warning('Skipped WhatsApp notification due to empty target or message', [
                'target' => $target,
                'message' => $message
            ]);
            return false;
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.token'),
            ])->post("https://api.fonnte.com/send", [
                'target' => $target,
                'message' => $message,
            ]);
            if ($response->successful()) {
                Log::info('WhatsApp notification sent successfully', [
                    'target' => $target,
                    'message' => $message
                ]);
                return true;
            }
            Log::error('Failed to send WhatsApp notification', [
                'target' => $target,
                'response' => $response->body()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception in sending WhatsApp notification', [
                'error' => $e->getMessage(),
                'target' => $target
            ]);
            return false;
        }
    }
    // /
    //  * Send bulk WhatsApp notifications for all permohonan cuti records.
    //  *
    //  * @param Request $request
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    public function sendBulkNotification(Request $request)
    {
        try {
            $permohonanCutis = PermohonanCuti::with('pegawai', 'jenisCuti')->paginate(50);
            $successCount = 0;
            $failedCount = 0;
            foreach ($permohonanCutis as $permohonan) {
                $message = $this->formatNotificationMessage($permohonan);
                $target = $permohonan->pegawai->no_telepon;
                if (empty($target)) {
                    Log::warning('Skipped notification due to missing phone number', [
                        'permohonan_id' => $permohonan->id,
                        'pegawai_id' => $permohonan->pegawai->id ?? null,
                    ]);
                    $failedCount++;
                    continue;
                }
                if ($this->send($target, $message)) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Bulk notifications processed',
                'success_count' => $successCount,
                'failed_count' => $failedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Exception in bulk notification processing', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process bulk notifications',
            ], 500);
        }
    }
    /**
     * Format the notification message.
     *
     * @param PermohonanCuti $permohonan
     * @return string
     */
    private function formatNotificationMessage(PermohonanCuti $permohonan)
    {
        return implode("\n", [
            "Notifikasi Permohonan Cuti",
            "Nama: {$permohonan->pegawai->nama}",
            "Jenis Cuti: {$permohonan->jenisCuti->nama}",
            "Tanggal: {$permohonan->tanggal_mulai} - {$permohonan->tanggal_selesai}",
            "Status: {$permohonan->status}"
        ]);
    }
}