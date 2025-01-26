<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanSkBerkala extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'unit_kerja',
        'pangkat_golongan',
        'jabatan',
        'tmt_sk_pangkat_terakhir',
        'tanggal_penerbitan_pangkat_terakhir',
        'tmt_sk_berkala_terakhir',
        'tanggal_penerbitan_sk_berkala_terakhir',
        'upload_sk_pangkat_terakhir',
        'upload_sk_berkala_terakhir',
        'upload_surat_pengantar',
    ];
}
