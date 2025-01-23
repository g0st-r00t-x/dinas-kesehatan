<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UsulanPermohonanPensiun extends Model
{
    protected $table = 'usulan_pensiun';

    protected $fillable = [
        'nama',
        'nip',
        'pangkat_golongan',
        'jabatan',
        'surat_pengantar_unit',
        'sk_pangkat_terakhir',
        'sk_cpcns',
        'sk_pns',
        'sk_berkala_terakhir',
        'akte_nikah',
        'ktp_pasangan',
        'karis_karsu',
        'skp_terakhir',
        'akte_anak',
        'surat_kuliah',
        'akte_kematian',
        'nama_bank',
        'nomor_rekening',
        'npwp',
        'foto',
        'nomor_telepon',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $fileFields = [
                'surat_pengantar_unit',
                'sk_pangkat_terakhir',
                'sk_cpcns',
                'sk_pns',
                'sk_berkala_terakhir',
                'akte_nikah',
                'ktp_pasangan',
                'karis_karsu',
                'skp_terakhir',
                'akte_anak',
                'surat_kuliah',
                'akte_kematian',
                'foto',
            ];

            foreach ($fileFields as $field) {
                if (!empty($model->$field)) {
                    $model->$field = self::processUploadedFiles($model->$field, $field);
                }
            }
        });

        static::saved(function () {
            // Bersihkan folder sementara jika ada
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
        $targetDir = 'usulan_pensiun/' . $field;

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
     * Accessor for file URLs
     */
    public function getFileUrlsAttribute()
    {
        $fileFields = [
            'surat_pengantar_unit',
            'sk_pangkat_terakhir',
            'sk_cpcns',
            'sk_pns',
            'sk_berkala_terakhir',
            'akte_nikah',
            'ktp_pasangan',
            'karis_karsu',
            'skp_terakhir',
            'akte_anak',
            'surat_kuliah',
            'akte_kematian',
            'foto',
        ];

        $urls = [];
        foreach ($fileFields as $field) {
            $urls[$field] = $this->generateFileUrls($this->$field);
        }

        return $urls;
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
     * Mutators for file fields
     */
    public function setSuratPengantarUnitAttribute($value)
    {
        $this->attributes['surat_pengantar_unit'] = $this->processFileValue($value);
    }

    public function setSkPangkatTerakhirAttribute($value)
    {
        $this->attributes['sk_pangkat_terakhir'] = $this->processFileValue($value);
    }

    public function setFotoAttribute($value)
    {
        $this->attributes['foto'] = $this->processFileValue($value);
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
}
