<?php

namespace App\Filament\Resources\UsulanPermohonanCutiResource\Pages;

use App\Filament\Resources\UsulanPermohonanCutiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsulanPermohonanCuti extends EditRecord
{
    protected static string $resource = UsulanPermohonanCutiResource::class;

     // protected function beforeCreate(): void
    // {
    //     $pegawai = Pegawai::where('nip', $this->data['pegawai_nip'])->first();
    //     // Create PengajuanSurat first
    //     $pengajuanSurat = PengajuanSurat::create([
    //         'id_pengajuan_surat' => $this->data['id_pengajuan_surat'],
    //         'jenis_surat' => 'Surat Masuk',
    //         'perihal' => 'Permohonan Cuti ' . $pegawai->nama,
    //         'status_pengajuan' => 'Diajukan',
    //         'tgl_pengajuan' => now(),
    //     ]);

    //     // Add the pengajuan_surat_id to the form data
    //     $this->data['id_pengajuan_surat'] = $pengajuanSurat->id;
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
