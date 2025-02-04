<?php
namespace App\Filament\Resources\PengajuanSuratResource\Pages;

use App\Filament\Resources\PengajuanSuratResource;
use App\Models\ArsipSurat;
use App\Models\PengajuanSurat;
use App\Models\PermohonanCuti;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
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
        if (!isset($this->data['id_pemohon'])) {
            throw new Exception('ID Pemohon tidak ditemukan dalam data.');
        }

        // Cek apakah PengajuanSurat ditemukan
        $pengajuanSurat = PengajuanSurat::where('id_pemohon', $this->data['id_pemohon'])->first();
        if (!$pengajuanSurat) {
            throw new Exception('Pengajuan Surat tidak ditemukan.');
        }

        // Cek apakah PermohonanCuti ditemukan
        $permohonanCuti = PermohonanCuti::where('id', $pengajuanSurat->id_pemohon)->first();
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
                Storage::disk('local')->put($filePath, $pdf->output());

                ArsipSurat::create([
                    'id_pengajuan_surat' => $this->data['id'],
                    'file_surat_path' => $filePath,
                    'tgl_arsip' => now(),
                ]);
            } else {
                // Jika arsip sudah ada, update file lama dengan yang baru
                Storage::disk('local')->delete($existingArsip->file_surat_path); // Hapus file lama
                Storage::disk('local')->put($filePath, $pdf->output()); // Simpan file baru

                $existingArsip->update([
                    'file_surat_path' => $filePath,
                    'tgl_arsip' => now(),
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Notification::make()->title('Gagal Menyimpan Arsip')->danger()->body($e->getMessage())->send();
        }
    }

    protected function afterSave(): void
    {
        Notification::make()->title('Usulan Permohonan Cuti Diperbarui')->success()->body('Data usulan permohonan cuti telah diperbarui dan diarsipkan.')->send();
    }

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
