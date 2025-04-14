<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class FileManagerServices
{
  public const PRIVATE_DISK = 'local';
  public const PUBLIC_DISK = 'public';

  protected array $allowedMimeTypes = [
    'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    'document' => [
      'application/pdf',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ],
  ];

  protected array $maxFileSizes = [
    'image' => 5120, // 5MB in kilobytes
    'document' => 10240, // 10MB in kilobytes
  ];

  /**
   * Upload file ke storage dengan validasi dan pengaturan nama file
   *
   * @param UploadedFile $file File yang akan diupload
   * @param string $path Path tujuan upload
   * @param bool $isPublic True jika file akan disimpan di public storage
   * @param string|null $filename Custom filename (opsional)
   * @param string|null $type Tipe file untuk validasi (image/document)
   * @return array Response dengan status upload dan informasi file
   */
  public function upload(
    UploadedFile $file,
    string $path,
    bool $isPublic = false,
    ?string $filename = null,
    ?string $type = null
  ): array {
    try {
      // Validasi file
      $validationResult = $this->validateFile($file, $type);
      if (!$validationResult['success']) {
        return $validationResult;
      }

      // Generate nama file yang aman
      $finalFilename = $this->generateFilename($file, $filename);

      // Tentukan disk yang akan digunakan
      $disk = $isPublic ? self::PUBLIC_DISK : self::PRIVATE_DISK;

      // Buat direktori jika belum ada
      $fullPath = trim($path, '/') . '/' . $finalFilename;
      $this->ensureDirectoryExists($path, $disk);

      // Upload file
      $uploaded = Storage::disk($disk)->putFileAs(
        $path,
        $file,
        $finalFilename
      );

      if (!$uploaded) {
        throw new Exception("Gagal mengupload file");
      }

      // Siapkan response
      $response = [
        'success' => true,
        'message' => 'File berhasil diupload',
        'data' => [
          'path' => $fullPath,
          'filename' => $finalFilename,
          'original_name' => $file->getClientOriginalName(),
          'mime_type' => $file->getMimeType(),
          'size' => $file->getSize(),
          'extension' => $file->getClientOriginalExtension(),
          'disk' => $disk,
        ]
      ];

      // Tambahkan URL jika file public
      if ($isPublic) {
        $response['data']['url'] = Storage::disk($disk)->url($fullPath);
      }

      return $response;
    } catch (Exception $e) {
      Log::error('File upload failed', [
        'error' => $e->getMessage(),
        'file' => $file->getClientOriginalName(),
        'path' => $path
      ]);

      return [
        'success' => false,
        'message' => 'Gagal mengupload file: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Validasi file berdasarkan tipe dan ukuran
   */
  protected function validateFile(UploadedFile $file, ?string $type = null): array
  {
    if ($type && !isset($this->allowedMimeTypes[$type])) {
      return [
        'success' => false,
        'message' => "Tipe file '$type' tidak valid"
      ];
    }

    if ($type && !in_array($file->getMimeType(), $this->allowedMimeTypes[$type])) {
      return [
        'success' => false,
        'message' => "Tipe file tidak diizinkan untuk kategori '$type'"
      ];
    }

    if ($type && $file->getSize() > ($this->maxFileSizes[$type] * 1024)) {
      return [
        'success' => false,
        'message' => "Ukuran file melebihi batas maksimum untuk kategori '$type'"
      ];
    }

    return ['success' => true];
  }

  /**
   * Generate nama file yang aman
   */
  protected function generateFilename(UploadedFile $file, ?string $customFilename = null): string
  {
    if ($customFilename) {
      $filename = Str::slug(pathinfo($customFilename, PATHINFO_FILENAME));
    } else {
      $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
    }

    $extension = $file->getClientOriginalExtension();
    $timestamp = Carbon::now()->format('YmdHis');
    $random = Str::random(8);

    return "{$filename}-{$timestamp}-{$random}.{$extension}";
  }

  /**
   * Pastikan direktori tujuan ada
   */
  protected function ensureDirectoryExists(string $path, string $disk): void
  {
    $storage = Storage::disk($disk);
    if (!$storage->exists($path)) {
      $storage->makeDirectory($path);
    }
  }

  /**
   * Hapus file dari storage
   */
  public function delete(string $path, ?string $disk = null): array
  {
    try {
      if ($disk) {
        // Hapus dari disk spesifik
        if (Storage::disk($disk)->exists($path)) {
          Storage::disk($disk)->delete($path);
          return [
            'success' => true,
            'message' => "File berhasil dihapus dari $disk storage"
          ];
        }
        return [
          'success' => false,
          'message' => "File tidak ditemukan di $disk storage"
        ];
      }

      // Cek dan hapus dari kedua storage
      $deleted = false;
      $messages = [];

      if (Storage::disk(self::PUBLIC_DISK)->exists($path)) {
        Storage::disk(self::PUBLIC_DISK)->delete($path);
        $messages[] = "File dihapus dari public storage";
        $deleted = true;
      }

      if (Storage::disk(self::PRIVATE_DISK)->exists($path)) {
        Storage::disk(self::PRIVATE_DISK)->delete($path);
        $messages[] = "File dihapus dari private storage";
        $deleted = true;
      }

      return [
        'success' => $deleted,
        'message' => $deleted ? implode(', ', $messages) : "File tidak ditemukan"
      ];
    } catch (Exception $e) {
      Log::error('File deletion failed', [
        'error' => $e->getMessage(),
        'path' => $path,
        'disk' => $disk
      ]);

      return [
        'success' => false,
        'message' => 'Gagal menghapus file: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Pindahkan file antar storage
   */
  public function move(string $path, bool $toPublic): array
  {
    try {
      $sourceDisk = $toPublic ? self::PRIVATE_DISK : self::PUBLIC_DISK;
      $targetDisk = $toPublic ? self::PUBLIC_DISK : self::PRIVATE_DISK;

      if (!Storage::disk($sourceDisk)->exists($path)) {
        return [
          'success' => false,
          'message' => "File tidak ditemukan di $sourceDisk storage"
        ];
      }

      // Copy file ke target disk
      $contents = Storage::disk($sourceDisk)->get($path);
      $saved = Storage::disk($targetDisk)->put($path, $contents);

      if (!$saved) {
        throw new Exception("Gagal menyimpan file di $targetDisk storage");
      }

      // Hapus file dari source disk
      Storage::disk($sourceDisk)->delete($path);

      $response = [
        'success' => true,
        'message' => "File berhasil dipindahkan ke $targetDisk storage",
        'data' => [
          'path' => $path,
          'disk' => $targetDisk
        ]
      ];

      if ($toPublic) {
        $response['data']['url'] = Storage::disk($targetDisk)->url($path);
      }

      return $response;
    } catch (Exception $e) {
      Log::error('File move failed', [
        'error' => $e->getMessage(),
        'path' => $path,
        'toPublic' => $toPublic
      ]);

      return [
        'success' => false,
        'message' => 'Gagal memindahkan file: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Copy file antar storage
   */
  public function copy(string $path, bool $toPublic): array
  {
    try {
      $sourceDisk = $toPublic ? self::PRIVATE_DISK : self::PUBLIC_DISK;
      $targetDisk = $toPublic ? self::PUBLIC_DISK : self::PRIVATE_DISK;

      if (!Storage::disk($sourceDisk)->exists($path)) {
        return [
          'success' => false,
          'message' => "File tidak ditemukan di $sourceDisk storage"
        ];
      }

      // Copy file ke target disk
      $contents = Storage::disk($sourceDisk)->get($path);
      $saved = Storage::disk($targetDisk)->put($path, $contents);

      if (!$saved) {
        throw new Exception("Gagal menyalin file ke $targetDisk storage");
      }

      $response = [
        'success' => true,
        'message' => "File berhasil disalin ke $targetDisk storage",
        'data' => [
          'path' => $path,
          'disk' => $targetDisk
        ]
      ];

      if ($toPublic) {
        $response['data']['url'] = Storage::disk($targetDisk)->url($path);
      }

      return $response;
    } catch (Exception $e) {
      Log::error('File copy failed', [
        'error' => $e->getMessage(),
        'path' => $path,
        'toPublic' => $toPublic
      ]);

      return [
        'success' => false,
        'message' => 'Gagal menyalin file: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Dapatkan informasi file
   */
  public function getInfo(string $path, ?string $disk = null): array
  {
    try {
      if ($disk) {
        return $this->getFileInfo($path, $disk);
      }

      // Cek di kedua storage
      if (Storage::disk(self::PUBLIC_DISK)->exists($path)) {
        return $this->getFileInfo($path, self::PUBLIC_DISK);
      }

      if (Storage::disk(self::PRIVATE_DISK)->exists($path)) {
        return $this->getFileInfo($path, self::PRIVATE_DISK);
      }

      return [
        'success' => false,
        'message' => 'File tidak ditemukan'
      ];
    } catch (Exception $e) {
      Log::error('Get file info failed', [
        'error' => $e->getMessage(),
        'path' => $path,
        'disk' => $disk
      ]);

      return [
        'success' => false,
        'message' => 'Gagal mendapatkan informasi file: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Dapatkan informasi file dari disk tertentu
   */
  protected function getFileInfo(string $path, string $disk): array
  {
    $storage = Storage::disk($disk);

    if (!$storage->exists($path)) {
      return [
        'success' => false,
        'message' => "File tidak ditemukan di $disk storage"
      ];
    }

    $mime = $storage->mimeType($path);
    $size = $storage->size($path);
    $lastModified = Carbon::createFromTimestamp($storage->lastModified($path));

    $info = [
      'success' => true,
      'data' => [
        'path' => $path,
        'disk' => $disk,
        'mime_type' => $mime,
        'size' => $size,
        'size_formatted' => $this->formatFileSize($size),
        'last_modified' => $lastModified->format('Y-m-d H:i:s'),
        'last_modified_human' => $lastModified->diffForHumans(),
        'extension' => pathinfo($path, PATHINFO_EXTENSION)
      ]
    ];

    if ($disk === self::PUBLIC_DISK) {
      $info['data']['url'] = $storage->url($path);
    }

    return $info;
  }

  /**
   * Format ukuran file ke format yang mudah dibaca
   */
  protected function formatFileSize(int $bytes): string
  {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;

    while ($bytes >= 1024 && $i < count($units) - 1) {
      $bytes /= 1024;
      $i++;
    }

    return round($bytes, 2) . ' ' . $units[$i];
  }
}
