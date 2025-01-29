<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanSkPemberhentianSementara extends Model
{
    use HasFactory;

    protected $table = 'usulan_sk_pemberhentian_sementara';

    protected $fillable = [
        'pegadwai_nip',
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
}

