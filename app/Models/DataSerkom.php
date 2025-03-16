<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataSerkom extends Model
{
    use HasFactory;

    protected $table = 'data_serkom'; // Nama tabel di database

    protected $primaryKey = 'id_serkom'; // Primary key

    protected $fillable = [
        'nip',
        'nama_pegawai',
        'nama_sertifikasi',
        'nomor_sertifikat',
        'lembaga_penerbit',
        'tanggal_terbit',
        'tanggal_kadaluarsa',
        'status_validasi',
        'file_sertifikat',
    ];

    protected $dates = ['tanggal_terbit', 'tanggal_kadaluarsa'];

    public function pegawai(): BelongsTo {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }
}
