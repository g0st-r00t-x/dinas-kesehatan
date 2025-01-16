<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UsulanPenerbitanAjj extends Model
{
    use HasFactory;

    protected $table = 'usulan_penerbitan_ajj';

    protected $fillable = [
        'nama',
        'nip',
        'unit_kerja',
        'tmt_pemberian_tunjangan',
        'sk_jabatan',
        'upload_berkas',
        'surat_pengantar_unit_kerja',
    ];

    // Cast attributes untuk menangani array/json

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Handle multiple files for each field
            $fields = [ 'upload_berkas', 'surat_pengantar_unit_kerja'];
            
            foreach ($fields as $field) {
                if (!empty($model->$field)) {
                    $files = is_array($model->$field) ? $model->$field : [$model->$field];
                    $processedFiles = [];
                    
                    foreach ($files as $file) {
                        $targetDir = match($field) {
                            'upload_berkas' => 'upload_berkas',
                            'surat_pengantar_unit_kerja' => 'surat_pengantar'
                        };
                        
                        $processedFiles[] = self::moveFileFromTmp($file, $targetDir);
                    }
                    
                    $model->$field = count($processedFiles) > 1 ? $processedFiles : $processedFiles[0];
                }
            }
        });

        static::saved(function () {
            // Bersihkan folder temporary setelah penyimpanan
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

        return $filePath; // Return original path if file doesn't exist in tmp
    }

    // Mutators


    public function setUploadBerkasAttribute($value)
    {
        $this->attributes['upload_berkas'] = $this->processFileAttribute($value);
    }

    public function setSuratPengantarUnitKerjaAttribute($value)
    {
        $this->attributes['surat_pengantar_unit_kerja'] = $this->processFileAttribute($value);
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

    // Accessors


    public function getUploadBerkasUrlAttribute()
    {
        return $this->getFileUrls($this->upload_berkas);
    }

    public function getSuratPengantarUnitKerjaUrlAttribute()
    {
        return $this->getFileUrls($this->surat_pengantar_unit_kerja);
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