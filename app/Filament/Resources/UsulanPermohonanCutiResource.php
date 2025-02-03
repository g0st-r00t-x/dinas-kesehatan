<?php

namespace App\Filament\Resources;

use App\Filament\Imports\PermohonanCutiImporter;
use App\Filament\Resources\UsulanPermohonanCutiResource\Pages;
use App\Http\Controllers\AjukanCutiController;
use App\Http\Controllers\WhatsappNotification;
use App\Models\PengajuanSurat;
use Filament\Tables\Actions\ImportAction;
use App\Models\PermohonanCuti;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UsulanPermohonanCutiResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PermohonanCuti::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?string $label = 'Permohonan Cuti';
    protected static ?string $pluralLabel = 'Permohonan Cuti';
    protected static ?string $path = 'usulan-permohonan-cuti';

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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Permohonan Cuti')
                ->schema([
                    Forms\Components\Hidden::make('user_id')
                        ->default(Auth::id()),
                    Forms\Components\Select::make('pegawai_nip')
                        ->label('Pegawai')
                        ->relationship('pegawai', 'nama')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('jenis_cuti_id')
                        ->label('Jenis Cuti')
                        ->relationship('jenisCuti', 'nama')
                        ->required(),
                    Forms\Components\DatePicker::make('tanggal_mulai')
                        ->required(),
                    Forms\Components\DatePicker::make('tanggal_selesai')
                        ->required(),
                    Forms\Components\Textarea::make('alasan')
                        ->maxLength(500),
                    Forms\Components\Select::make('status')
                        ->options([
                            'diajukan' => 'Diajukan',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak'
                        ])
                        ->default('diajukan')
                        ->required(),
                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenisCuti.nama')
                    ->label('Jenis Cuti'),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_cuti_id')
                    ->relationship('jenisCuti', 'nama')
                    ->label('Jenis Cuti'),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(PermohonanCutiImporter::class),
            ])
            ->actions([
                Action::make('Ajukan Cuti')
                    ->icon('heroicon-o-document-plus')
                    ->action(fn (Model $record) => (new AjukanCutiController())->handle($record)),
                Action::make('wa_notif')
                    ->label('Kirim Notifikasi')
                    ->color('success')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(fn () => auth()->user()?->hasPermissionTo('kirim_notif_usulan::permohonan::cuti'))
                    ->action(function (Model $record) {
                        $message = "Notifikasi Permohonan Cuti\n" .
                            "Nama: {$record->pegawai->nama}\n" .
                            "Jenis Cuti: {$record->jenisCuti->nama}\n" .
                            "Tanggal: {$record->tanggal_mulai} - {$record->tanggal_selesai}\n" .
                            "Status: {$record->status}";

                        try {
                            (new WhatsappNotification())->send($record->pegawai->no_telepon, $message);
                            Notification::make()
                                ->success()
                                ->title('Notifikasi Terkirim')
                                ->body("Pesan WhatsApp dikirim ke {$record->pegawai->nama}")
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Gagal Mengirim Notifikasi')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                ActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                    Tables\Actions\BulkAction::make('kirim_notifikasi_massal')
                        ->label('Kirim Notifikasi Massal')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->visible(fn () => auth()->user()?->hasPermissionTo('kirim_notif_usulan::permohonan::cuti'))
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $message = "Notifikasi Permohonan Cuti\n" .
                                    "Nama: {$record->pegawai->nama}\n" .
                                    "Jenis Cuti: {$record->jenisCuti->nama}\n" .
                                    "Tanggal: {$record->tanggal_mulai} - {$record->tanggal_selesai}\n" .
                                    "Status: {$record->status}";
                                (new WhatsappNotification())->send($record->pegawai->no_telepon, $message);
                            }
                            Notification::make()
                                ->success()
                                ->title('Notifikasi Massal Terkirim')
                                ->body('Semua notifikasi telah berhasil dikirim.')
                                ->send();
                        })
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsulanPermohonanCutis::route('/'),
            'create' => Pages\CreateUsulanPermohonanCuti::route('/create'),
            'edit' => Pages\EditUsulanPermohonanCuti::route('/{record}/edit'),
        ];
    }
}
