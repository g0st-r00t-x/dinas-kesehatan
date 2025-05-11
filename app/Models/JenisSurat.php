<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_surat';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'kode_jenis';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_jenis',
        'nama_jenis',
        'deskripsi',
        'template_content',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get all surat keluar for this jenis surat
     * 
     * @return HasMany
     */
    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'kode_jenis_surat', 'kode_jenis');
    }

    /**
     * Get all pengajuan surat for this jenis surat
     * 
     * @return HasMany
     */
    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class, 'kode_jenis_surat', 'kode_jenis');
    }
    public function pengajuanSuratId(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class, 'id_jenis_surat', 'id');
    }
}
