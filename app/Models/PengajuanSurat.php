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
        'id_pengajuan',
        'jenis_pengajuan_id',
        'jenis_pengajuan_type',
        'status_pengajuan',
        'tgl_pengajuan',
        'tgl_diterima',
        'id_pemohon',
        'id_diajukan',
        'kode_jenis_surat',
        'no_referensi',
        'data_pengajuan',
        'tgl_persetujuan',
        'tgl_penolakan',
        'id_penyetuju',
        'id_penolak',
        'catatan',
        'alasan_penolakan',
        'file_surat',
    ];

    /**
     * Atribut yang seharusnya dikonversi ke tipe lain
     *
     * @var array
     */
    protected $casts = [
        'tgl_pengajuan' => 'datetime',
        'tgl_diterima' => 'datetime',
        'tgl_persetujuan' => 'datetime',
        'tgl_penolakan' => 'datetime',
        'data_pengajuan' => 'array', // cast JSON column to array
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

    public function suratKeluar(): HasOne
    {
        return $this->hasOne(SuratKeluar::class, 'id_pengajuan', 'id');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat');
    }

    public function jenisPengajuan()
    {
        return $this->morphTo();
    }
}
