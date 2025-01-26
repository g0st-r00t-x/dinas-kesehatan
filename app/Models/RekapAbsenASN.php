<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsenAsn extends Model
{
    use HasFactory;

    protected $table = 'rekap_absen_asn';

    protected $fillable = [
        'bulan',
        'upload_excel',
    ];
}
