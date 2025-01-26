<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $templates = [
        'default' => "{title}\n" .
                     "Nama: {name}\n" .
                     "Jenis: {type}\n" .
                     "Pesan: {message}\n" .
                     "{file_url ? 'Lampiran: ' + file_url : ''}"
    ];

    public function send(string $target, string $template, array $params)
    {
        try {
            $messageTemplate = $this->templates[$template] ?? $this->templates['default'];
            $message = $this->processTemplate($messageTemplate, $params);

            $response = $this->sendWhatsapp($target, $message);

            return $response['success'];
        } catch (\Exception $e) {
            Log::error('Notification error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected function processTemplate(string $template, array $params)
    {
        return preg_replace_callback('/\{([^}]+)\}/', function($matches) use ($params) {
            $expression = $matches[1];

            // Conditional rendering
            if (strpos($expression, '?') !== false) {
                list($condition, $value) = explode('?', $expression);
                $condition = trim($condition);
                $value = trim($value);
                return isset($params[$condition]) && $params[$condition] ? $params[$condition] : '';
            }

            return $params[$expression] ?? $matches[0];
        }, $template);
    }

    protected function sendWhatsapp(string $target, string $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.token'),
            ])->post("https://api.fonnte.com/send", [
                'target' => $target,
                'message' => $message,
            ]);

            return [
                'success' => $response->successful(),
                'error' => $response->unsuccessful() ? $response->body() : null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}