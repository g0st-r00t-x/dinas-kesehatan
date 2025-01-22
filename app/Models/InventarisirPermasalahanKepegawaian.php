<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisirPermasalahanKepegawaian extends Model
{
    use HasFactory;

    protected $table = 'inventarisir_permasalahan_kepegawaian';

    protected $fillable = [
        'nama',
        'nip',
        'pangkat_golongan',
        'jabatan',
        'unit_kerja',
        'permasalahan',
        'data_dukungan_id',
        'file_upload',
        'surat_pengantar_unit_kerja',
    ];

    // Relasi ke DataDukungan
    public function dataDukungan()
    {
        return $this->belongsTo(DataDukungan::class, 'data_dukungan_id');
    }
}
