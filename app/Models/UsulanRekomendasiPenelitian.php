<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class UsulanRekomendasiPenelitian extends Model
{
    use HasFactory;

    protected $table = 'usulan_rekomendasi_penelitians';

    protected $fillable = [
        'nama',
        'judul_penelitian',
        'asal_lembaga_pendidikan',
        'tujuan_penelitian',
        'nim_nip',
        'surat_pengantar_path',
        'no_telp_wa',
    ];

    // Enum untuk tujuan penelitian
    const TUJUAN_PENELITIAN = [
        'magang_pkl' => 'Magang/PKL',
        'penyusunan_tesis' => 'Penyusunan Tesis',
        'penyusunan_skripsi' => 'Penyusunan Skripsi',
        'penyusunan_riset' => 'Penyusunan Riset',
    ];

    /**
     * Accessor untuk mendapatkan deskripsi tujuan penelitian
     */
    public function getTujuanPenelitianLabelAttribute()
    {
        return self::TUJUAN_PENELITIAN[$this->tujuan_penelitian] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor untuk URL file surat pengantar
     */
    public function getSuratPengantarUrlAttribute()
    {
        if ($this->surat_pengantar_path) {
            return asset('storage/' . $this->surat_pengantar_path);
        }
        return null;
    }

    /**
     * Boot method untuk mengelola file upload
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!empty($model->surat_pengantar_path)) {
                // Proses file upload dan simpan path-nya ke atribut
                $processedPath = self::processFileUpload($model->surat_pengantar_path, 'surat_pengantar');
                $model->attributes['surat_pengantar_path'] = $processedPath;
            }
        });

        static::saved(function () {
            // Membersihkan direktori sementara jika ada
            if (Storage::disk('public')->exists('tmp')) {
                Storage::disk('public')->deleteDirectory('tmp');
            }
        });
    }

    /**
     * Proses file upload dari direktori sementara
     *
     * @param string $filePath
     * @param string $targetDirectory
     * @return string|null
     */
    private static function processFileUpload($filePath, $targetDirectory)
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
     * Mutator untuk menyimpan file surat pengantar
     */
    public function setSuratPengantarPathAttribute($value)
    {
        $this->attributes['surat_pengantar_path'] = self::processFileUpload($value, 'surat_pengantar');
    }
}
