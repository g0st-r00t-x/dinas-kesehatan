<?php
namespace App\Filament\Resources\PengajuanSuratResource\Pages;

use App\Filament\Resources\PengajuanSuratResource;
use App\Models\ArsipSurat;
use App\Models\PengajuanSurat;
use App\Models\PermohonanCuti;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Exception;

class EditPengajuanSurat extends EditRecord
{
    protected static string $resource = PengajuanSuratResource::class;

    protected function beforeSave(): void
    {
        DB::beginTransaction();
        try {
            // Pastikan id_pemohon ada
            if (!isset($this->data['id_pengajuan'])) {
                throw new Exception('ID Pengajuan tidak ditemukan dalam data.');
            }

            // Cek apakah PengajuanSurat ditemukan
            $pengajuanSurat = PengajuanSurat::where('id_pengajuan', $this->data['id_pengajuan'])->first();
            if (!$pengajuanSurat) {
                throw new Exception('Pengajuan Surat tidak ditemukan.');
            }

            // Cek apakah PermohonanCuti ditemukan
            $permohonanCuti = PermohonanCuti::where('id', $pengajuanSurat->id_pengajuan)->first();
            if (!$permohonanCuti) {
                throw new Exception('Permohonan Cuti tidak ditemukan.');
            }

            // Cek apakah data pegawai tersedia
            if (!$permohonanCuti->pegawai) {
                throw new Exception('Data Pegawai tidak ditemukan.');
            }

            $pegawai = $permohonanCuti->pegawai;

            // Generate nama file unik
            $filename = "Surat_Cuti_{$pegawai->nama}_" . now()->format('YmdHis') . '.pdf';
            $filePath = "arsip_surat/{$filename}";

            // Generate PDF
            $pdf = Pdf::loadView('pdf', ['record' => $pegawai]);

            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Times-Roman',
                'isPhpEnabled' => true,
                'autoScriptToLang' => true,
                'defaultPaperSize' => 'a4',
                'dpi' => 150,
                'defaultEncoding' => 'UTF-8',
            ]);

            $existingArsip = ArsipSurat::where('id_pengajuan_surat', $this->data['id'])->first();
            if (!$existingArsip) {
                // Jika belum ada arsip, buat arsip baru
                $pdf->stream();

                ArsipSurat::create([
                    'id_pengajuan_surat' => $this->data['id'],
                    'file_surat_path' => $filePath,
                    'tgl_arsip' => now(),
                ]);
            } else {
                // Jika arsip sudah ada, update file lama dengan yang baru
                Storage::disk('public')->delete($existingArsip->file_surat_path); // Hapus file lama
                $pdf->stream(); // Simpan file baru
            }

            // Kirim notifikasi kepada pegawai yang mengajukan cuti
            $userPemohon = User::where('id', $permohonanCuti->user_id)->first();
            if ($userPemohon) {
                if($this->data['status_pengajuan'] == "Ditolak"){
                    Notification::make()
                        ->title('Pengajuan Surat Cuti Ditolak')
                        ->body("Surat cuti Anda ditolak, mohon untuk periksa kembali pengajuan anda.")
                        ->danger()
                        ->sendToDatabase($userPemohon,  isEventDispatched: true);
                        event(new DatabaseNotificationsSent($userPemohon));
                }
                elseif($this->data['status_pengajuan'] == "Diterima"){
                    Notification::make()
                    ->title('Pengajuan Surat Cuti Diterima')
                    ->body("Surat cuti Anda telah diproses, sekarang anda dapat mendownloadnya.")
                    ->success()
                    ->sendToDatabase($userPemohon,  isEventDispatched: true);
                    event(new DatabaseNotificationsSent($userPemohon));
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            
            // Kirim notifikasi error kepada admin/pengguna yang sedang login
            Notification::make()
                ->title('Gagal Memproses Surat Cuti')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('Usulan Permohonan Cuti Diperbarui')
            ->body('Data usulan permohonan cuti telah diperbarui dan diarsipkan.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}