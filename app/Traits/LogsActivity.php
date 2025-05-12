<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Exception;
use Illuminate\Support\Facades\Log;

trait LogsActivity
{
  protected static function bootLogsActivity()
  {
    // Gunakan try-catch untuk menangani kemungkinan error
    try {
      static::created(fn($model) => self::logActivity($model, 'created'));
      static::updated(fn($model) => self::logActivity($model, 'updated'));
      static::deleted(fn($model) => self::logActivity($model, 'deleted'));
    } catch (Exception $e) {
      Log::error('Error in bootLogsActivity: ' . $e->getMessage());
    }
  }

  protected static function logActivity($model, $event, $customDescription = null)
  {
    try {
      // Dapatkan nama model yang lebih user-friendly
      $modelName = class_basename($model);

      // Mengubah huruf besar menjadi spasi sebelum huruf kecil, agar nama model lebih mudah dibaca
      $formattedModelName = preg_replace('/(?<!\s)(?=[A-Z])/', ' ', $modelName); // Menambahkan spasi sebelum huruf besar

      // Deskripsi default yang lebih dinamis
      $defaultDescription = static::getDefaultDescription($formattedModelName, $event);

      ActivityLog::create([
        'log_name' => $modelName,
        'description' => $customDescription ?? $defaultDescription,
        'event' => $event,
        'subject_type' => get_class($model),
        'subject_id' => $model->id,
        'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
        'causer_id' => Auth::id(),
        'properties' => json_encode([
          'old' => in_array($event, ['updated', 'updating']) ? $model->getOriginal() : [],
          'new' => $model->getAttributes(),
          'ip_address' => Request::ip() ?? 'N/A',
          'user_agent' => Request::header('User-Agent') ?? 'N/A',
          'timestamp' => now()->toDateTimeString(),
        ])
      ]);
    } catch (Exception $e) {
      Log::error('Error logging activity: ' . $e->getMessage());
    }
  }

  protected static function getDefaultDescription($modelName, $event): string
  {
    return match ($event) {
      'created' => "{$modelName} baru telah dibuat",
      'updated' => "{$modelName} telah diperbarui",
      'deleted' => "{$modelName} telah dihapus",
      default => "{$modelName} telah {$event}",
    };
  }

  public static function logCustomEvent($model, $event, $description)
  {
    try {
      if (!$model || !$event) {
        throw new Exception('Model and event are required for custom logging');
      }
      self::logActivity($model, $event, $description);
    } catch (Exception $e) {
      Log::error('Error in logCustomEvent: ' . $e->getMessage());
    }
  }
}
