<?php

namespace App\Filament\Resources;

use App\Filament\Imports\PermohonanCutiImporter;
use App\Filament\Resources\UsulanPermohonanCutiResource\Pages;
use App\Http\Controllers\WhatsappNotification;
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

class UsulanPermohonanCutiResource extends Resource implements HasShieldPermissions
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
    protected static ?string $model = PermohonanCuti::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?string $label = 'Permohonan Cuti';
    protected static ?string $pluralLabel = 'Permohonan Cuti';

    protected static ?string $path = 'usulan-permohonan-cuti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Permohonan Cuti')
                    ->schema([
                    Forms\Components\Select::make('pegawai_nip')
                        ->label('Pegawai')
                        ->relationship('pegawai', 'nama')
                        ->searchable()
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nip')
                                ->required()
                                ->unique(),
                            Forms\Components\TextInput::make('nama')
                                ->required(),
                            Forms\Components\TextInput::make('no_telepon')
                                ->required(),
                            Forms\Components\Select::make('unit_kerja_id')
                                ->relationship('unitKerja', 'nama')
                                ->required(),
                            Forms\Components\TextInput::make('jabatan'),
                            Forms\Components\Select::make('status_kepegawaian')
                                ->options([
                                    'PNS' => 'PNS',
                                    'PPPK' => 'PPPK',
                                    'Honorer' => 'Honorer'
                                ])
                        ]),
                        
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

                        Repeater::make('dokumenCuti')
                            ->relationship('dokumenCuti')
                            ->schema([
                                Forms\Components\TextInput::make('jenis_dokumen')
                                    ->required(),
                                Forms\Components\FileUpload::make('path_file')
                                    ->directory('dokumen-cuti')
                                    ->preserveFilenames()
                                    ->required(),
                            ])
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
            Action::make('wa_notif')
                ->label('Kirim Notifikasi')
                ->color('success')
                ->icon('heroicon-o-paper-airplane')
                ->visible(fn () => auth()->user()?->hasPermissionTo('kirim_notif_usulan::permohonan::cuti'))
                ->action(function (Model $record) {
                    $message = implode("\n", [
                        "Notifikasi Permohonan Cuti",
                        "Nama: {$record->pegawai->nama}",
                        "Jenis Cuti: {$record->jenisCuti->nama}",
                        "Tanggal: {$record->tanggal_mulai} - {$record->tanggal_selesai}",
                        "Status: {$record->status}"
                    ]);
                    
                    $target = $record->pegawai->no_telepon;
                    
                    try {
                        $whatsappNotification = new WhatsappNotification();
                        $whatsappNotification->send($target, $message);
                        
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
                    ->visible(fn () => auth()->user()?->hasPermissionTo('kirim_notif_usulan::permohonan::cuti'))
                    ->color('success')
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        foreach ($records as $record) {
                            $message = implode("\n", [
                                "Notifikasi Permohonan Cuti",
                                "Nama: {$record->pegawai->nama}",
                                "Jenis Cuti: {$record->jenisCuti->nama}",
                                "Tanggal: {$record->tanggal_mulai} - {$record->tanggal_selesai}",
                                "Status: {$record->status}"
                            ]);
                            
                            $target = $record->pegawai->no_telepon;
                            
                            $whatsappNotification = new WhatsappNotification();
                            $whatsappNotification->send($target, $message);
                        }

                        Notification::make()
                            ->success()
                            ->title('Notifikasi Massal Terkirim')
                            ->body('Semua notifikasi telah berhasil dikirim.')
                            ->send();
                    }),
            ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button()
                ->label('Pilih')
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