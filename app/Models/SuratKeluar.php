<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'id_pegawai',
        'id_jenis_surat',
        'nomor_surat',
        'perihal',
        'tujuan_surat',
        'tanggal_surat',
        'file_surat',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat');
    }
}
