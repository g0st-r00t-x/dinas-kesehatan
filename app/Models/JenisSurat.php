<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JenisSurat extends Model
{
    use HasFactory;
    protected $table = 'jenis_surat';
    protected $fillable = ['nama', 'kode', 'template_surat'];

    public function suratKeluar(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat');
    }
}
