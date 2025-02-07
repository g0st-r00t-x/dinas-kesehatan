<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsulanSkPemberhentianSementara extends Model
{
    use HasFactory;

    protected $table = 'usulan_sk_pemberhentian_sementara';

    protected $fillable = [
        'pegawai_nip',
        'user_id',
        'tmt_sk_pangkat_terakhir',
        'tmt_sk_jabatan_terakhir',
        'file_sk_jabatan_fungsional_terakhir',
        'alasan',
        'file_pak',
        'surat_pengantar',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pengajuanSurat()
    {
        return $this->hasOne(PengajuanSurat::class, 'id_pengajuan');
    }
}

