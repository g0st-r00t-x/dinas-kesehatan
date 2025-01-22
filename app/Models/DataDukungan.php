<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDukungan extends Model
{
    use HasFactory;

    protected $table = 'jenis_data_dukungans';

    protected $fillable = [
        'jenis',
        'file_path',
    ];

    // Relasi ke InventarisirPermasalahanKepegawaian
    public function inventarisirPermasalahan()
    {
        return $this->hasMany(InventarisirPermasalahanKepegawaian::class, 'data_dukungan_id');
    }
}
