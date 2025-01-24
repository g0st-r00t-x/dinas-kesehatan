<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenCuti extends Model
{
    protected $table = 'dokumen_cuti';
    protected $fillable = [
        'permohonan_cuti_id', 'jenis_dokumen', 
        'path_file', 'nama_file'
    ];

    public function permohonanCuti()
    {
        return $this->belongsTo(PermohonanCuti::class);
    }
}
