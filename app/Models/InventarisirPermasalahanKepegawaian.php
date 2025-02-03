<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class InventarisirPermasalahanKepegawaian extends Model
{
    use HasFactory;

    protected $table = 'permasalahan_kepegawaian';

    protected $fillable = [
        'user_id',
        'pegawai_nip',
        'permasalahan',
        'data_dukungan_id',
        'file_upload',
        'surat_pengantar_unit_kerja',
    ];

    // Relasi ke tabel pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }

    // Relasi ke tabel data dukungan
    public function dataDukungan()
    {
        return $this->belongsTo(DataDukungan::class, 'data_dukungan_id', 'data_dukungan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Event before saving the model
        static::saving(function ($model) {
            $fields = ['file_upload', 'surat_pengantar_unit_kerja'];
            foreach ($fields as $field) {
                if (!empty($model->$field)) {
                    $model->$field = self::processUploadedFiles($model->$field, $field);
                }
            }
        });

        // Event after saving the model
        static::saved(function () {
            // Clean up temporary directory
            if (Storage::disk('public')->exists('tmp')) {
                Storage::disk('public')->deleteDirectory('tmp');
            }
        });
    }

    /**
     * Process and move uploaded files from tmp directory
     *
     * @param mixed $files
     * @param string $field
     * @return string|array|null
     */
    private static function processUploadedFiles($files, $field)
    {
        $files = is_array($files) ? $files : [$files];
        $processedFiles = [];
        $targetDir = match ($field) {
            'file_upload' => 'file_uploads',
            'surat_pengantar_unit_kerja' => 'surat_pengantar',
            default => 'other',
        };

        foreach ($files as $file) {
            if (!empty($file)) {
                $processedFiles[] = self::moveFileFromTmp($file, $targetDir);
            }
        }

        return count($processedFiles) > 1 ? json_encode($processedFiles) : $processedFiles[0] ?? null;
    }

    /**
     * Move file from tmp to target directory
     *
     * @param string $filePath
     * @param string $targetDirectory
     * @return string|null
     */
    private static function moveFileFromTmp($filePath, $targetDirectory)
    {
        if (empty($filePath)) {
            return null;
        }

        $filePath = str_replace('public/', '', $filePath);
        $tmpPath = 'tmp/' . $filePath;
        $newPath = $targetDirectory . '/' . basename($filePath);

        if (Storage::disk('public')->exists($tmpPath)) {
            Storage::disk('public')->move($tmpPath, $newPath);
            return $newPath;
        }

        return $filePath;
    }

    /**
     * Accessor for file_upload URL
     */
    public function getFileUploadUrlAttribute()
    {
        return $this->generateFileUrls($this->file_upload);
    }

    /**
     * Accessor for surat_pengantar_unit_kerja URL
     */
    public function getSuratPengantarUnitKerjaUrlAttribute()
    {
        return $this->generateFileUrls($this->surat_pengantar_unit_kerja);
    }

    /**
     * Generate URLs for stored files
     *
     * @param mixed $files
     * @return string|array|null
     */
    private function generateFileUrls($files)
    {
        if (empty($files)) {
            return null;
        }

        $files = is_string($files) ? json_decode($files, true) : $files;

        if (is_array($files)) {
            return array_map(fn($file) => asset('storage/' . $file), $files);
        }

        return asset('storage/' . $files);
    }

    /**
     * Mutator for file_upload
     */
    public function setFileUploadAttribute($value)
    {
        $this->attributes['file_upload'] = $this->processFileValue($value);
    }

    /**
     * Mutator for surat_pengantar_unit_kerja
     */
    public function setSuratPengantarUnitKerjaAttribute($value)
    {
        $this->attributes['surat_pengantar_unit_kerja'] = $this->processFileValue($value);
    }

    /**
     * Process file value for storage
     *
     * @param mixed $value
     * @return string|null
     */
    private function processFileValue($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_array($value)) {
            return json_encode(array_map(fn($file) => str_replace('public/', '', $file), $value));
        }

        return str_replace('public/', '', $value);
    }

    /**
     * Relation to DataDukungan
     */
}
