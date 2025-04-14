<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventarisAJJ extends Model
{
    use HasFactory;

    protected $table = 'usulan_penerbitan_ajj';

    protected $fillable = [
        'user_id',
        'pegawai_nip',
        'tmt_pemberian_tunjangan',
        'sk_jabatan',
        'unit_kerja_id',
        'upload_berkas',
        'surat_pengantar_unit_kerja',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }

    public function pengajuanSurat()
    {
        return $this->hasOne(PengajuanSurat::class, 'id_pengajuan');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id', 'unit_kerja_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}