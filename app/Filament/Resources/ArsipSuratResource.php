<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArsipSuratResource\Pages;
use App\Filament\Resources\ArsipSuratResource\RelationManagers;
use App\Http\Controllers\WhatsappNotification;
use App\Models\ArsipSurat;
use App\Models\PengajuanSurat;
use App\Models\PermohonanCuti;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ArsipSuratResource extends Resource
{
    protected static ?string $model = ArsipSurat::class;

    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'download_file', 'create', 'update', 'delete', 'delete_any', 'kirim_notif'];
    }

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?string $navigationLabel = 'Arsip Surat';
    protected static ?string $modelLabel = 'Arsip Surat';
    protected static ?string $label = 'Arsip Surat';
    protected static ?string $pluralLabel = 'Arsip Surat';
    protected static ?string $path = 'arsip-surat';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Arsip')
                ->schema([
                    Forms\Components\Select::make('id_pengajuan_surat')->relationship('pengajuanSurat', 'nomor_sk')->label('Nomor Surat')->required()->preload(),

                    Forms\Components\FileUpload::make('file_surat_path')
                        ->label('File Surat')
                        ->required()
                        ->directory('arsip-surat')
                        ->preserveFilenames()
                        ->maxSize(5120) // 5MB
                        ->acceptedFileTypes(['application/pdf'])
                        ->downloadable(),

                    Forms\Components\DateTimePicker::make('tgl_arsip')->label('Tanggal Arsip')->required()->default(now()),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengajuanSurat.nomor_sk')->label('Nomor Surat')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('pengajuanSurat.perihal')->label('Perihal')->searchable()->sortable()->limit(50),

                Tables\Columns\TextColumn::make('tgl_arsip')->label('Tanggal Arsip')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('file_surat_path')
                    ->label('File Surat')
                    ->formatStateUsing(fn($state) => $state ? 'Lihat File' : '-')
                    ->url(fn($record) => $record->file_surat_path ? (str_starts_with($record->file_surat_path, 'http') ? $record->file_surat_path : Storage::url($record->file_surat_path)) : null) //This is used to access file private
                    // ->url(function ($record) {
                    //     if (!$record->file_surat_path) {
                    //         return null;
                    //     }

                    //     // Jika sudah berupa URL lengkap
                    //     if (str_starts_with($record->file_surat_path, 'http')) {
                    //         return $record->file_surat_path;
                    //     }
                    //     //to acces private file
                    //     return URL::temporarySignedRoute(
                    //         'download.private.file',
                    //         now()->addMinutes(5),
                    //         ['path' => base64_encode($record->file_surat_path)] // Encode path file
                    //     );
                    // })
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([Forms\Components\DatePicker::make('created_from')->label('Dibuat Dari'), Forms\Components\DatePicker::make('created_until')->label('Dibuat Sampai')])
                    ->query(function ($query, array $data) {
                        return $query->when($data['created_from'], fn($query) => $query->whereDate('created_at', '>=', $data['created_from']))->when($data['created_until'], fn($query) => $query->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        if (!$record->file_surat_path) {
                            return;
                        }

                        if (str_starts_with($record->file_surat_path, 'http')) {
                            // Untuk file dengan URL eksternal
                            return redirect($record->file_surat_path);
                        } else {
                            // Untuk file yang disimpan lokal
                            return response()->download(storage_path('app/public/' . $record->file_surat_path));
                        }
                    })
                    ->visible(fn($record) => $record->file_surat_path !== null),
                // Di ArsipSuratResource
                Action::make('send_wa')
                    ->label('Kirim Notifikasi')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->action(function (ArsipSurat $record) {
                        try {
                            // Mengambil data pengajuan surat dan relasinya
                            $pengajuanSurat = $record->pengajuanSurat;
                            if (!$pengajuanSurat) {
                                throw new Exception('Pengajuan surat tidak ditemukan.');
                            }

                            // Mengambil data permohonan cuti
                            $permohonanCuti = $pengajuanSurat->permohonanCuti;
                            if (!$permohonanCuti) {
                                throw new Exception('Data permohonan cuti tidak ditemukan.');
                            }

                            // Mengambil data pegawai
                            $pegawai = $permohonanCuti->pegawai;
                            if (!$pegawai) {
                                throw new Exception('Data pegawai tidak ditemukan.');
                            }

                            if (empty($pegawai->no_telepon)) {
                                throw new Exception('Nomor telepon pegawai tidak tersedia.');
                            }

                            // Menyiapkan path file
                            $filePath = null;
                            if ($record->file_surat_path) {
                                $filePath = storage_path('app/public/' . $record->file_surat_path);
                                if (!file_exists($filePath)) {
                                    throw new Exception('File surat tidak ditemukan di storage.');
                                }
                            }

                            // Menyiapkan pesan
                            $message = "Notifikasi Permasalahan Kepegawaian\n" .
                                "Nomor Surat: {$pengajuanSurat->nomor_sk}\n" .
                                "Nama Pegawai: {$pegawai->nama}\n" .
                                "Perihal: {$pengajuanSurat->perihal}\n" .
                                "Status: Menunggu Tindak Lanjut";

                            // Mengirim pesan dan file
                            $whatsappNotification = new WhatsappNotification();
                            if ($whatsappNotification->send($pegawai->no_telepon, $message, $filePath)) {
                                Notification::make()
                                    ->success()
                                    ->title('Notifikasi Terkirim')
                                    ->body("Pesan WhatsApp berhasil dikirim ke {$pegawai->nama}")
                                    ->send();
                            } else {
                                throw new Exception('Gagal mengirim pesan WhatsApp.');
                            }
                        } catch (Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Gagal Mengirim Notifikasi')
                                ->body($e->getMessage())
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->visible(
                        fn(ArsipSurat $record) =>
                        $record->pengajuanSurat &&
                            $record->pengajuanSurat->permohonanCuti &&
                            $record->pengajuanSurat->permohonanCuti->pegawai
                    )
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArsipSurats::route('/'),
            'create' => Pages\CreateArsipSurat::route('/create'),
            'edit' => Pages\EditArsipSurat::route('/{record}/edit'),
        ];
    }
}
