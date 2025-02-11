<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsen extends Model
{
    use HasFactory;

    protected $table = 'rekap_absen_asn';

    protected $fillable = [
        'bulan',
        'jenis_pegawai',
        'upload_excel',
    ];
}
