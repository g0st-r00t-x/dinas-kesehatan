<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisPermasalahanKepegawaian extends Model
{
    use HasFactory;
    
    protected $table = 'inventaris_permasalahan_kepegawaian';
    
    protected $fillable = [
        'nama',
        'nip',
        'pangkat_golongan',
        'jabatan',
        'unit_kerja',
        'permasalahan',
        'data_dukungan',
        'file_upload',
        'surat_pengantar_unit_kerja',
    ];

    // Relasi ke model UnggahDokumen
    public function dokumen()
    {
        return $this->hasMany(UnggahDokumen::class, 'inventaris_id', 'id');
    }
}