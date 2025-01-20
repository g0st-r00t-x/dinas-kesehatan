<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnggahDokumen extends Model
{
    use HasFactory;
    
    protected $table = 'unggah_dokumen'; // Seharusnya menggunakan nama tabel yang benar
    
    protected $fillable = [
        'nama_dokumen',
        'lokasi_dokumen',
        'inventaris_id', // Menambahkan foreign key
    ];

    // Relasi ke model InventarisPermasalahanKepegawaian
    public function inventaris()
    {
        return $this->belongsTo(InventarisPermasalahanKepegawaian::class, 'inventaris_id', 'id');
    }
}