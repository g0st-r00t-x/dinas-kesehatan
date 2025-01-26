<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanCuti extends Model
{
    protected $table = 'permohonan_cuti';
    protected $fillable = [
        'pegawai_id', 'jenis_cuti_id', 'tanggal_mulai', 
        'tanggal_selesai', 'alasan', 'status'
    ];


    public function pegawai()
{
    return $this->belongsTo(Pegawai::class);
}

public function unitKerja()
{
    return $this->belongsTo(UnitKerja::class);
}

    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class);
    }

    public function dokumenCuti()
    {
        return $this->hasMany(DokumenCuti::class);
    }
}