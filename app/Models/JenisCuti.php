<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    protected $table = 'jenis_cuti';
    protected $fillable = ['nama', 'deskripsi'];

    public function permohonanCuti()
    {
        return $this->hasMany(PermohonanCuti::class);
    }
}