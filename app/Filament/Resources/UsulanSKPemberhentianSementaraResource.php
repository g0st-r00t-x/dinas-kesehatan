<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanSKPemberhentianSementaraResource\Pages;
use App\Filament\Resources\UsulanSKPemberhentianSementaraResource\RelationManagers;
use App\Models\UsulanSKPemberhentianSementara;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UsulanSKPemberhentianSementaraResource extends Resource
{
    protected static ?string $model = UsulanSKPemberhentianSementara::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Usulan';

    protected static ?string $navigationLabel = 'SK Pemberhentian Sementara';
    
    protected static ?string $modelLabel = 'SK Pemberhentian Sementara';


    protected static ?string $label = 'SK Pemberhentian Sementara';

    protected static ?string $pluralLabel = 'SK Pemberhentian Sementara';

    protected static ?string $path = 'usulan-sk-pemberhentian-sementara';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pegawai_nip')
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
                DatePicker::make('tmt_sk_pangkat_terakhir')
                    ->required()
                    ->label('TMT SK Pangkat Terakhir'),
                DatePicker::make('tmt_sk_jabatan_terakhir')
                    ->required()
                    ->label('TMT SK Jabatan Terakhir'),
                Forms\Components\FileUpload::make('file_sk_jabatan_fungsional_terakhir')
                ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                ->storeFiles()    
                ->required()
                    ->directory('usulan_pemberhentian_sementara/file_sk_jabatan_fungsional_terakhir')
                    ->label('File SK Jabatan Fungsional Terakhir'),
                Forms\Components\Select::make('alasan')
                    ->required()
                    ->label('Alasan')
                    ->options([
                        'tidak_melanjutkan_pendidikan' => 'Tidak Melanjutkan Pendidikan',
                        'melanjutkan_pendidikan' => 'Melanjutkan Pendidikan',
                    ]),
                Forms\Components\FileUpload::make('file_pak')
                ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                ->storeFiles()    
                ->required()
                    ->directory('usulan_pemberhentian_sementara/file_pak')
                    ->label('File PAK'),
                Forms\Components\FileUpload::make('surat_pengantar')
                ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                ->storeFiles()    
                ->required()
                    ->directory('usulan_pemberhentian_sementara/surat_pengantar')
                    ->label('Surat Pengantar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pegawai.nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pegawai.unit_kerja')
                    ->label('Unit Kerja'),
                TextColumn::make('pegawai.pangkat_golongan')
                    ->label('Pangkat/Golongan'),
                TextColumn::make('tmt_sk_pangkat_terakhir')
                    ->label('TMT SK Pangkat Terakhir')
                    ->date(),
                TextColumn::make('tmt_sk_jabatan_terakhir')
                    ->label('TMT SK Jabatan Terakhir')
                    ->date(),
                TextColumn::make('alasan')
                    ->label('Alasan'),
               TextColumn::make('file_pak')
                ->label('File Pak')
                ->icon('heroicon-o-eye')
                ->formatStateUsing(fn ($state) => $state ? 'Lihat File' : '-')
                ->url(fn ($record) => $record->file_pak 
                    ? (str_starts_with($record->file_pak, 'http') 
                        ? $record->file_pak 
                        : Storage::url($record->file_pak))
                    : null
                )
                ->openUrlInNewTab(),

TextColumn::make('file_sk_jabatan_fungsional_terakhir')
    ->label('File SK Jabatan Fungsional Terakhir')
    ->icon('heroicon-o-eye')
    ->formatStateUsing(fn ($state) => $state ? 'Lihat File' : '-')
    ->url(fn ($record) => $record->file_sk_jabatan_fungsional_terakhir 
        ? (str_starts_with($record->file_sk_jabatan_fungsional_terakhir, 'http') 
            ? $record->file_sk_jabatan_fungsional_terakhir 
            : Storage::url($record->file_sk_jabatan_fungsional_terakhir))
        : null
    )
    ->openUrlInNewTab(),

TextColumn::make('surat_pengantar')
    ->label('Surat Pengantar')
    ->icon('heroicon-o-eye')
    ->formatStateUsing(fn ($state) => $state ? 'Lihat File' : '-')
    ->url(fn ($record) => $record->surat_pengantar 
        ? (str_starts_with($record->surat_pengantar, 'http') 
            ? $record->surat_pengantar 
            : Storage::url($record->surat_pengantar))
        : null
    )
    ->openUrlInNewTab()
            ])
            ->filters([
                SelectFilter::make('alasan')
                    ->label('Filter Alasan')
                    ->options([
                        'tidak_melanjutkan_pendidikan' => 'Tidak Melanjutkan Pendidikan',
                        'melanjutkan_pendidikan' => 'Melanjutkan Pendidikan',
                    ]),
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
            'index' => Pages\ListUsulanSKPemberhentianSementaras::route('/'),
            'create' => Pages\CreateUsulanSKPemberhentianSementara::route('/create'),
            'edit' => Pages\EditUsulanSKPemberhentianSementara::route('/{record}/edit'),
        ];
    }
}
