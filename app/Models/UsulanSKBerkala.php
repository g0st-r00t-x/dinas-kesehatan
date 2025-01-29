<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanSkBerkala extends Model
{
    use HasFactory;

    protected $table = 'usulan_sk_berkala';

    protected $fillable = [
        'pegawai_nip',
        'tmt_sk_pangkat_terakhir',
        'tanggal_penerbitan_pangkat_terakhir',
        'tmt_sk_berkala_terakhir',
        'tanggal_penerbitan_sk_berkala_terakhir',
        'upload_sk_pangkat_terakhir',
        'upload_sk_berkala_terakhir',
        'upload_surat_pengantar',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }
}
