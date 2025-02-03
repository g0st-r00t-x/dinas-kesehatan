<?php

namespace App\Filament\Resources\UsulanPermohonanCutiResource\Pages;

use App\Filament\Resources\UsulanPermohonanCutiResource;
use App\Models\Pegawai;
use App\Models\PengajuanSurat;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUsulanPermohonanCuti extends CreateRecord
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

    // protected function afterCreate(): void
    // {
    //     // Send notification or perform additional tasks after creation
    //     Notification::make()
    //         ->success()
    //         ->title('Permohonan Cuti Berhasil Dibuat')
    //         ->body('Pengajuan surat dan permohonan cuti telah berhasil dibuat.')
    //         ->send();
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
