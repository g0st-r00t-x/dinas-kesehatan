<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDukungan extends Model
{
    use HasFactory;

    protected $table = 'data_dukungan';

    protected $fillable = [
        'data_dukungan_id',
        'jenis',
    ];
}
