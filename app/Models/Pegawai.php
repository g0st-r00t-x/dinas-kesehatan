<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $fillable = [
        'nama', 'nip', 'unit_kerja_id', 'pangkat_golongan', 
        'jabatan', 'email', 'no_telepon', 'status_kepegawaian', 
        'tanggal_lahir', 'jenis_kelamin', 'alamat'
    ];

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function permohonanCuti()
    {
        return $this->hasMany(PermohonanCuti::class);
    }
}
