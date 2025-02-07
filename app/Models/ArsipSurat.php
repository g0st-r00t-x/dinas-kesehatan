<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipSurat extends Model
{
    use HasFactory;
    protected $table = 'arsip_surat';
    protected $fillable = [
        'id_pengajuan_surat',  // Nama kolom yang benar
        'file_surat_path',
        'tgl_arsip'
    ];

    protected $casts = [
        'tgl_arsip' => 'datetime',
    ];

    public function pengajuanSurat()
    {
        // Perbaiki nama kolom di relasi
        return $this->belongsTo(PengajuanSurat::class, 'id_pengajuan_surat', 'id');
    }
}