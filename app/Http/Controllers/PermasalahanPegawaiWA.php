<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\InventarisirPermasalahanKepegawaian;

class PermasalahanPegawaiWA extends Controller
{
    public function send($target, $message)
    {
        // Validate input
        if (empty($target) || empty($message)) {
            Log::warning('Skipped WhatsApp notification', [
                'target' => $target,
                'message' => $message
            ]);
            return false;
        }

        // Improved phone number validation for Indonesian numbers
        if (!preg_match('/^(\+62|0)?8[1-9][0-9]{7,10}$/', $target)) {
            Log::warning('Invalid phone number format', ['target' => $target]);
            return false;
        }

        try {
            // Ensure phone number is in international format for Fonnte
            $formattedTarget = preg_replace('/^0/', '+62', $target);
            
            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.token'),
            ])->post("https://api.fonnte.com/send", [
                'target' => $formattedTarget,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp notification sent', [
                    'target' => $formattedTarget,
                ]);
                return true;
            }

            Log::error('Failed to send WhatsApp notification', [
                'target' => $formattedTarget,
                'response' => $response->body()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp notification exception', [
                'error' => $e->getMessage(),
                'target' => $target
            ]);
            return false;
        }
    }

    public function sendBulkNotification(Request $request)
    {
        try {
            // Use first() or get() to resolve the Builder type
            $records = InventarisirPermasalahanKepegawaian::with('pegawai')->FIRST();
            $successCount = 0;
            $failedCount = 0;

            foreach ($records as $record) {
                if (!$record->pegawai || empty($record->pegawai->no_telepon)) {
                    Log::warning('Skipped notification', [
                        'record_id' => $record->id,
                    ]);
                    $failedCount++;
                    continue;
                }
                
                $message = $this->formatNotificationMessage($record);
                $target = $record->pegawai->no_telepon;

                $this->send($target, $message) ? $successCount++ : $failedCount++;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Bulk notifications processed',
                'success_count' => $successCount,
                'failed_count' => $failedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk notification processing failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process bulk notifications',
            ], 500);
        }
    }
    private function formatNotificationMessage(InventarisirPermasalahanKepegawaian $record)
    {
        return implode("\n", [
            "Notifikasi Permasalahan Kepegawaian",
            "Nama: {$record->pegawai->nama}",
            "NIP: {$record->pegawai->nip}",
            "Permasalahan: {$record->permasalahan}",
            "Status: Menunggu Tindak Lanjut"
        ]);
    }
}