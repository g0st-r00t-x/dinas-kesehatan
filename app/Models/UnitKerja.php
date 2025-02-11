<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';
    protected $fillable = ['nama'];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}