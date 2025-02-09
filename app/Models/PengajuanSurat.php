<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'id_diajukan',
        'id_pengajuan',
        'id_pemohon',
        'status_pengajuan',
        'tgl_pengajuan',
        'tgl_diterima'
    ];

    protected $casts = [
        'tgl_pengajuan' => 'datetime',
        'tgl_diterima' => 'datetime',
    ];

    public function arsipSurat()
    {
        // Perbaiki nama kolom di relasi
        return $this->hasOne(ArsipSurat::class, 'id_pengajuan_surat', 'id');
    }

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'id_pemohon');
    }

    public function diajukan()
    {
        return $this->belongsTo(Pegawai::class, 'id_diajukan');
    }

    public function pengajuan()
    {
        return $this->belongsTo(SuratKeluar::class, 'id_pengajuan', 'id');
    }
}
