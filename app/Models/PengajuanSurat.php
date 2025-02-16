<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'tgl_diterima',
        'id_jenis_surat',
    ];

    protected $casts = [
        'tgl_pengajuan' => 'datetime',
        'tgl_diterima' => 'datetime',
    ];

    public function arsipSurat(): HasOne
    {
        return $this->hasOne(ArsipSurat::class, 'id_pengajuan_surat', 'id');
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pemohon');
    }

    public function diajukan(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_diajukan');
    }

    public function suratKeluar(): BelongsTo
    {
        return $this->belongsTo(SuratKeluar::class, 'id_pengajuan', 'id');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat');
    }
}
