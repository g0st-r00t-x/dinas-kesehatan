<?php

namespace App\Filament\Imports;

use App\Models\Pegawai;
use App\Models\UsulanPermohonanPensiun;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;

// a .	NAMA
// b.	NIP
// c.	PANGKAT/GOLONGAN
// d.	JABATAN
// e.	UPLOAD SURAT PENGANTAR UNIT KERJA
// f.	UPLOAD SK PANGKAT TERAKHIR
// g.	UPLOAD SK CPCNS
// h.	UPLOAD SK PNS
// i.	UPLOAD SK BERKALA TERAKHIR
// j.	UPLOAD AKTE NIKAH
// k.	UPLOAD KTP SUAMI
// l.	UPLOAD KARIS/KARSU
// m.	UPLOAD SKP 1 TAHUN TERKAHIR
// n.	UOLOAD AKTE ANAK
// o.	UPLOAD SURAT KETERANGAN KULIAH
// p.	UPLOAD AKTE KEMATIAN
// q.	JENIS BANK BESERTA NO REKENNG BANK(BANK JATIM-1234)
// r.	UPLOAD NPWP
// s.	UPLOAD FOTO 3X4(SUAMI ISTRI)
// t.	NO TELP/WA YANG BERSANGKUTAN


class PermohonanPensiunImporter extends Importer
{
    protected static ?string $model = UsulanPermohonanPensiun::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nip'),
            ImportColumn::make('nama'),
            ImportColumn::make('jabatan'),
            ImportColumn::make('pangkat_golongan'),
            ImportColumn::make('no_wa'),
            ImportColumn::make('surat_pengantar_unit'),
            ImportColumn::make('sk_pangkat_terakhir'),
            ImportColumn::make('sk_cpns'),
            ImportColumn::make('sk_pns'),
            ImportColumn::make('sk_berkala_terakhir'),
            ImportColumn::make('akte_nikah'),
            ImportColumn::make('ktp_suami'),
            ImportColumn::make('karis_karsu'),
            ImportColumn::make('skp_terakhir'),
            ImportColumn::make('akte_anak'),
            ImportColumn::make('surat_kuliah'),
            ImportColumn::make('akte_kematian'),
            ImportColumn::make('nama_bank'),
            ImportColumn::make('npwp'),
            ImportColumn::make('foto'),
        ];
    }

    public function resolveRecord(): ?UsulanPermohonanPensiun
    {
        $user = Auth::user();

        $this->data['status'] = strtolower($this->data['status'] ?? 'diajukan'); // Normalisasi status

        $pegawai = Pegawai::firstOrCreate(
            ['nip' => $this->data['nip'] ?? '12'],
            [
                'nama' => $this->data['nama'] ?? 'Nama Default',
                'no_telepon' => $this->data['no_wa'] ?? '0000000000',
                'jabatan' => $this->data['jabatan'] ?? 1,
                'pangkat_golongan' => $this->data['pangkat_golongan'],
            ]
        );



        return UsulanPermohonanPensiun::create([
            'user_id' => $user->id,
            'pegawai_nip' => $pegawai->nip,
            'surat_pengantar_unit' => $this->data['surat_pengantar_unit'],
            'sk_pangkat_terakhir' => $this->data['sk_pangkat_terakhir'],
            'sk_cpns' => $this->data['sk_cpns'],
            'sk_pns' => $this->data['sk_pns'],
            'sk_berkala_terakhir' => $this->data['sk_berkala_terakhir'],
            'akte_nikah' => $this->data['akte_nikah'],
            'ktp_pasangan' => $this->data['ktp_suami'],
            'karis_karsu' => $this->data['karis_karsu'],
            'skp_terakhir' => $this->data['skp_terakhir'],
            'akte_anak' => $this->data['akte_anak'],
            'surat_kuliah' => $this->data['surat_kuliah'],
            'akte_kematian' => $this->data['akte_kematian'],
            'nama_bank' => $this->data['nama_bank'],
            'npwp' => $this->data['npwp'],
            'foto' => $this->data['foto'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your permohonan pensiun import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
