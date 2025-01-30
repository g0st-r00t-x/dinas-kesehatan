<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PermasalahanKepegawaianExporter;
use App\Filament\Imports\PermasalahanKepegawaianImporter;
use App\Filament\Resources\InventarisPermasalahanKepegawaianResource\Pages;
use App\Http\Controllers\PermasalahanPegawaiWA;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class InventarisPermasalahanKepegawaianResource extends Resource implements HasShieldPermissions
{

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'kirim_notif'
        ];
    }
    protected static ?string $model = \App\Models\InventarisirPermasalahanKepegawaian::class;


    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'Permasalahan Pegawai';
    protected static ?string $pluralLabel = 'Permasalahan Pegawai';

    protected static ?string $navigationGroup = 'Inventaris';

    protected static ?string $path = 'permasalahan-pegawai';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pegawai_nip')
                    ->label('Pegawai')
                    ->relationship('pegawai', 'nama')
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('permasalahan')
                    ->label('Permasalahan')
                    ->required()
                    ->rows(4),
                Forms\Components\Select::make('data_dukungan_id')
                    ->relationship('dataDukungan', 'jenis')
                    ->label('Data Dukungan')
                    ->required(),
                Forms\Components\FileUpload::make('file_upload')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return now()->timestamp . '_' . $file->getClientOriginalName();
                    })
                    ->label('File Upload')
                    ->directory('permasalahan-pegawai/permasalahan-files')
                    ->preserveFilenames(),
                Forms\Components\FileUpload::make('surat_pengantar_unit_kerja')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return now()->timestamp . '_' . $file->getClientOriginalName();
                    })
                    ->label('Surat Pengantar Unit Kerja')
                    ->directory('permasalahan-pegawai/surat-pengantar')
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ImportAction::make()
                    ->importer(PermasalahanKepegawaianImporter::class),
                Tables\Actions\ExportAction::make()
                    ->exporter(PermasalahanKepegawaianExporter::class),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pegawai.nip')
                    ->label('NIP')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('permasalahan')
                    ->label('Permasalahan')
                    ->limit(50),
                Tables\Columns\TextColumn::make('dataDukungan.jenis')
                    ->label('Data Dukungan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('send_wa')
                    ->label('Kirim Notifikasi')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn () => auth()->user()?->hasPermissionTo('kirim_notif_pengajuan::a::j::j'))
                    ->action(function (Model $record) {
                        $target = $record->pegawai->no_telepon;
                        $message = implode("\n", [
                            "Notifikasi Permasalahan Kepegawaian",
                            "Nama Pegawai: {$record->pegawai->nama}",
                            "Permasalahan: {$record->permasalahan}",
                            "Status: Menunggu Tindak Lanjut"
                        ]);

                        if (empty($target)) {
                            Notification::make()
                                ->danger()
                                ->title('Gagal Mengirim Notifikasi')
                                ->body('Nomor telepon tidak tersedia.')
                                ->send();
                            return;
                        }

                        $whatsappNotification = new PermasalahanPegawaiWA();
                        if ($whatsappNotification->send($target, $message)) {
                            Notification::make()
                                ->success()
                                ->title('Notifikasi Terkirim')
                                ->body("Pesan WhatsApp dikirim ke {$record->pegawai->nama}")
                                ->send();
                        } else {
                            Notification::make()
                                ->danger()
                                ->title('Gagal Mengirim Notifikasi')
                                ->body('Terjadi kesalahan saat mengirim pesan.')
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                BulkAction::make('send_bulk_wa')
                    ->label('Kirim Notifikasi Massal')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn () => auth()->user()?->hasPermissionTo('kirim_notif_pengajuan::a::j::j'))
                    ->action(function ($records) {
                        $whatsappNotification = new PermasalahanPegawaiWA();
                        $successCount = 0;
                        $failedCount = 0;

                        foreach ($records as $record) {
                            $target = $record->pegawai->no_telepon;
                            $message = implode("\n", [
                                "Notifikasi Permasalahan Kepegawaian",
                                "Nama Pegawai: {$record->pegawai->nama}",
                                "Permasalahan: {$record->permasalahan}",
                                "Status: Menunggu Tindak Lanjut"
                            ]);

                            if (!empty($target) && $whatsappNotification->send($target, $message)) {
                                $successCount++;
                            } else {
                                $failedCount++;
                            }
                        }

                        Notification::make()
                            ->success()
                            ->title('Notifikasi Massal Diproses')
                            ->body("Berhasil: {$successCount}, Gagal: {$failedCount}")
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventarisPermasalahanKepegawaians::route('/'),
            'create' => Pages\CreateInventarisPermasalahanKepegawaian::route('/create'),
            'edit' => Pages\EditInventarisPermasalahanKepegawaian::route('/{record}/edit'),
        ];
    }
}
