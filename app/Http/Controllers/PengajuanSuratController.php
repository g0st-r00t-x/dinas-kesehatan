<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\PengajuanSurat;
use App\Models\PermohonanCuti;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    public function handle($record, $perihal)
    {
        DB::beginTransaction();

        try {
            // Get current user
            $user = auth()->user();

            // Find employee
            $pegawai = Pegawai::where('nip', $record->pegawai_nip)->first();
            if (!$pegawai) {
                throw new Exception('Pegawai tidak ditemukan.');
            }

            // Get letter type from record if available, otherwise default to 'Surat Masuk'

            // Create letter submission
            $pengajuanSurat = PengajuanSurat::create([
                'id_pemohon' => $user->id,
                'id_diajukan' => $pegawai->id,
                'id_pengajuan' => $record->id,
                'jenis_surat' => 'Surat Masuk',
                'perihal' => $perihal,
                'status_pengajuan' => 'Diajukan',
                'tgl_pengajuan' => now(),
            ]);

            // Kirim notifikasi hanya pada user dengan permission
            $operators = User::permission('view_any_pengajuan::surat')->get();

            

            foreach ($operators as $operator) {
                Notification::make()
                    ->title("Pengajuan {$perihal} Baru")
                    ->body("Terdapat pengajuan {$perihal} baru dari {$pegawai->nama} yang memerlukan persetujuan")
                    ->success()
                    ->sendToDatabase($operator, isEventDispatched: true);

                event(new DatabaseNotificationsSent($operator));
            }

            DB::commit();

            // Success notification
            Notification::make()
                ->title("Berhasil Mengajukan {$perihal}")
                ->success()
                ->body("Pengajuan {$perihal} telah berhasil disubmit")
                ->send();
        } catch (Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title("Gagal Mengajukan Surat")
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
    
}