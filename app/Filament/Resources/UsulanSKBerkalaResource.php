<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanSKBerkalaResource\Pages;
use App\Filament\Resources\UsulanSKBerkalaResource\RelationManagers;
use App\Http\Controllers\PengajuanSuratController;
use App\Models\UsulanSKBerkala;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UsulanSKBerkalaResource extends Resource
{
    protected static ?string $model = UsulanSKBerkala::class;

    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'view_own', 'download_file', 'create', 'update', 'delete', 'delete_any', 'kirim_notif'];
    }


    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Usulan';
    protected static ?string $navigationLabel = 'SK Berkala';
    
    protected static ?string $modelLabel = 'SK Berkala';


    protected static ?string $label = 'SK Berkala';

    protected static ?string $pluralLabel = 'SK Berkala';

    protected static ?string $path = 'usulan-sk-pangkat';

    public static function form(Form $form): Form
    {
         return $form
            ->schema([
            Forms\Components\Hidden::make('user_id')
                ->default(Auth::id()),
                Select::make('pegawai_nip')
                        ->label('Pegawai')
                        ->relationship('pegawai', 'nama')
                        ->searchable()
                        ->required()
                        ->createOptionForm([
                            TextInput::make('nip')
                                ->required()
                                ->unique(),
                            TextInput::make('nama')
                                ->required(),
                            TextInput::make('no_telepon')
                                ->required(),
                            Select::make('unit_kerja_id')
                                ->relationship('unitKerja', 'nama')
                                ->required(),
                            TextInput::make('jabatan'),
                            Select::make('status_kepegawaian')
                                ->options([
                                    'PNS' => 'PNS',
                                    'PPPK' => 'PPPK',
                                    'Honorer' => 'Honorer'
                                ])
                        ]),
                DatePicker::make('tmt_sk_pangkat_terakhir')
                    ->required()
                    ->label('TMT SK Pangkat Terakhir'),
                DatePicker::make('tanggal_penerbitan_pangkat_terakhir')
                    ->required()
                    ->label('Tanggal Penerbitan Pangkat Terakhir'),
                DatePicker::make('tmt_sk_berkala_terakhir')
                    ->required()
                    ->label('TMT SK Berkala Terakhir'),
                DatePicker::make('tanggal_penerbitan_sk_berkala_terakhir')
                    ->required()
                    ->label('Tanggal Penerbitan SK Berkala Terakhir'),
                FileUpload::make('upload_sk_pangkat_terakhir')
                    ->required()
                    ->label('Upload SK Pangkat Terakhir'),
                FileUpload::make('upload_sk_berkala_terakhir')
                    ->required()
                    ->label('Upload SK Berkala Terakhir'),
                FileUpload::make('upload_surat_pengantar')
                    ->required()
                    ->label('Upload Surat Pengantar'),
            ]);
    }

    public static function table(Table $table): Table
    {
       return $table
            ->columns([
            Forms\Components\Hidden::make('user_id')
                ->default(Auth::user()->id),
                TextColumn::make('pegawai.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pegawai.nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pegawai.unit_kerja.nama')
                    ->label('Unit Kerja')
                    ->sortable(),
                TextColumn::make('pegawai.pangkat_golongan')
                    ->label('Pangkat/Golongan'),
                TextColumn::make('pegawai.jabatan')
                    ->label('Jabatan'),
            TextColumn::make('pengajuanSurat.status_pengajuan')
            ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Diajukan' => 'warning',
                    'Ditolak' => 'danger',
                    'Diterima' => 'success',
                    default => 'gray',
                })
                ->label("Status Pengajuan"),
                TextColumn::make('tmt_sk_pangkat_terakhir')
                    ->label('TMT SK Pangkat Terakhir')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tanggal_penerbitan_pangkat_terakhir')
                    ->label('Tanggal Penerbitan Pangkat Terakhir')
                    ->date(),
                TextColumn::make('tmt_sk_berkala_terakhir')
                    ->label('TMT SK Berkala Terakhir')
                    ->date(),
                TextColumn::make('tanggal_penerbitan_sk_berkala_terakhir')
                    ->label('Tanggal Penerbitan SK Berkala Terakhir')
                    ->date(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    ForceDeleteAction::make(),
                    Action::make('Ajukan Cuti')
                        ->icon('heroicon-o-document-plus')
                        ->action(fn(UsulanSkBerkala $record) => (new PengajuanSuratController())->handle($record, 'SK Berkala')),
                    Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (UsulanSkBerkala $record) {
                            // Mengambil arsip surat melalui relasi
                            $arsipSurat = $record->pengajuanSurat->arsipSurat;

                            if (!$arsipSurat || !$arsipSurat->file_surat_path) {
                                return;
                            }

                            if (str_starts_with($arsipSurat->file_surat_path, 'http')) {
                                // Untuk file dengan URL eksternal
                                return redirect($arsipSurat->file_surat_path);
                            } else {
                                // Untuk file yang disimpan lokal
                                return response()->download(storage_path('app/public/' . $arsipSurat->file_surat_path));
                            }
                        })
                        ->visible(function (UsulanSkBerkala $record) {
                            return $record->pengajuanSurat &&
                                $record->pengajuanSurat->status_pengajuan === 'Diterima' &&
                                $record->pengajuanSurat->arsipSurat &&
                                $record->pengajuanSurat->arsipSurat->file_surat_path !== null;
                        }),
                ])
            ])
            ->filters([
                SelectFilter::make('pangkat_golongan')
                    ->label('Filter Pangkat/Golongan')
            ])
            ->defaultSort('pegawai.nama');
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
            'index' => Pages\ListUsulanSKBerkalas::route('/'),
            'create' => Pages\CreateUsulanSKBerkala::route('/create'),
            'edit' => Pages\EditUsulanSKBerkala::route('/{record}/edit'),
        ];
    }
}
