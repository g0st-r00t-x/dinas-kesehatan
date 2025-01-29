<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UsulanRevisiSkPangkat extends Model
{
    use HasFactory;

    protected $table = 'usulan_revisi_sk_pangkat';
    

    protected $fillable = [
        'pegawai_nip',
        'alasan_revisi_sk',
        'kesalahan_tertulis_sk',
        'upload_sk_salah',
        'upload_data_dukung',
        'surat_pengantar',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'processed_at' => 'datetime'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Handle files for each upload field
            $fields = [
                'upload_sk_salah',
                'upload_data_dukung',
                'surat_pengantar'
            ];
            
            foreach ($fields as $field) {
                if (!empty($model->$field)) {
                    $files = is_array($model->$field) ? $model->$field : [$model->$field];
                    $processedFiles = [];
                    
                    foreach ($files as $file) {
                        $targetDir = match($field) {
                            'upload_sk_salah' => 'sk_salah',
                            'upload_data_dukung' => 'data_dukung',
                            'surat_pengantar' => 'surat_pengantar'
                        };
                        
                        $processedFiles[] = self::moveFileFromTmp($file, $targetDir);
                    }
                    
                    $model->$field = count($processedFiles) > 1 ? $processedFiles : $processedFiles[0];
                }
            }
        });

        static::saved(function () {
            // Clean temporary folder after saving
            if (Storage::disk('public')->exists('tmp')) {
                Storage::disk('public')->deleteDirectory('tmp');
            }
        });
    }

    private static function moveFileFromTmp($filePath, $targetDirectory)
    {
        if (empty($filePath)) {
            return null;
        }

        $filePath = str_replace('public/', '', $filePath);
        $tmpPath = 'tmp/' . $filePath;
        $newPath = $targetDirectory . '/' . $filePath;

        if (Storage::disk('public')->exists($tmpPath)) {
            Storage::disk('public')->move($tmpPath, $newPath);
            return $newPath;
        }

        return $filePath;
    }

    // Mutators for file uploads
    public function setUploadSkSalahAttribute($value)
    {
        $this->attributes['upload_sk_salah'] = $this->processFileAttribute($value);
    }

    public function setUploadDataDukungAttribute($value)
    {
        $this->attributes['upload_data_dukung'] = $this->processFileAttribute($value);
    }

    public function setSuratPengantarAttribute($value)
    {
        $this->attributes['surat_pengantar'] = $this->processFileAttribute($value);
    }

    private function processFileAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_array($value)) {
            return json_encode(array_map(fn($file) => str_replace('public/', '', $file), $value));
        }

        return str_replace('public/', '', $value);
    }

    // Accessors for file URLs
    public function getUploadSkSalahUrlAttribute()
    {
        return $this->getFileUrls($this->upload_sk_salah);
    }

    public function getUploadDataDukungUrlAttribute()
    {
        return $this->getFileUrls($this->upload_data_dukung);
    }

    public function getSuratPengantarUrlAttribute()
    {
        return $this->getFileUrls($this->surat_pengantar);
    }

    private function getFileUrls($files)
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

}