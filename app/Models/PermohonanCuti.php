<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanCuti extends Model
{
    protected $table = 'permohonan_cuti';
    protected $fillable = [
        'pegawai_nip', 'jenis_cuti_id', 'tanggal_mulai', 
        'tanggal_selesai', 'alasan', 'status'
    ];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class, 'jenis_cuti_id', 'jenis_cuti_id');
    }
}