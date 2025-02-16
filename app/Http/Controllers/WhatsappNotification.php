<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PermohonanCuti;
use CURLFile;

class WhatsappNotification extends Controller
{
    public function send($target, $message, $file_path = null)
    {
        if (empty($target) || empty($message)) {
            Log::warning('Skipped WhatsApp notification due to empty target or message', [
                'target' => $target,
                'message' => $message,
            ]);
            return false;
        }

        try {
            $params = [
                'target' => $target,
                'message' => $message,
            ];

            // Tambahkan file jika ada
            if ($file_path && file_exists($file_path)) {
                $params['file'] = new CURLFile($file_path);
                $params['filename'] = basename($file_path);
            }

            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.token'),
            ])->post("https://api.fonnte.com/send", $params);

            if ($response->successful()) {
                Log::info('WhatsApp notification sent successfully', [
                    'target' => $target,
                    'message' => $message,
                    'file_path' => $file_path
                ]);
                return true;
            }

            Log::error('Failed to send WhatsApp notification', [
                'target' => $target,
                'response' => $response->body(),
                'file_path' => $file_path
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Exception in sending WhatsApp notification', [
                'error' => $e->getMessage(),
                'target' => $target,
                'file_path' => $file_path
            ]);
            return false;
        }
    }
}
