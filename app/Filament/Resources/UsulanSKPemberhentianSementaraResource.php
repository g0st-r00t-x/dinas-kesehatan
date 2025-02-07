<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanSKPemberhentianSementaraResource\Pages;
use App\Http\Controllers\PengajuanSuratController;
use App\Models\UsulanSKPemberhentianSementara;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UsulanSKPemberhentianSementaraResource extends Resource
{
    protected static ?string $model = UsulanSKPemberhentianSementara::class;

    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'view_own', 'download_file', 'create', 'update', 'delete', 'delete_any', 'kirim_notif'];
    }

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
            Forms\Components\Hidden::make('user_id')
                ->default(Auth::user()->id),
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
                TextColumn::make('pegawai.unit_kerja.nama')
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
                    ->formatStateUsing(fn($state) => $state ? 'Lihat File' : '-')
                    ->url(
                        fn($record) => $record->file_pak
                            ? (str_starts_with($record->file_pak, 'http')
                                ? $record->file_pak
                                : Storage::url($record->file_pak))
                            : null
                    )
                    ->openUrlInNewTab(),
                TextColumn::make('pengajuanSurat.status_pengajuan')
                ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Diajukan' => 'warning',
                        'Ditolak' => 'danger',
                        'Diterima' => 'success',
                        default => 'gray',
                    })
                    ->label("Status Pengajuan"),

                TextColumn::make('file_sk_jabatan_fungsional_terakhir')
                    ->label('File SK Jabatan Fungsional Terakhir')
                    ->icon('heroicon-o-eye')
                    ->formatStateUsing(fn($state) => $state ? 'Lihat File' : '-')
                    ->url(
                        fn($record) => $record->file_sk_jabatan_fungsional_terakhir
                            ? (str_starts_with($record->file_sk_jabatan_fungsional_terakhir, 'http')
                                ? $record->file_sk_jabatan_fungsional_terakhir
                                : Storage::url($record->file_sk_jabatan_fungsional_terakhir))
                            : null
                    )
                    ->openUrlInNewTab(),

                TextColumn::make('surat_pengantar')
                    ->label('Surat Pengantar')
                    ->icon('heroicon-o-eye')
                    ->formatStateUsing(fn($state) => $state ? 'Lihat File' : '-')
                    ->url(
                        fn($record) => $record->surat_pengantar
                            ? (str_starts_with($record->surat_pengantar, 'http')
                                ? $record->surat_pengantar
                                : Storage::url($record->surat_pengantar))
                            : null
                    )
                    ->openUrlInNewTab()
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('Ajukan Cuti')
                        ->icon('heroicon-o-document-plus')
                        ->action(fn(UsulanSKPemberhentianSementara $record) => (new PengajuanSuratController())->handle($record, 'SK Pemberhentian Sementara')),
                    Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (UsulanSKPemberhentianSementara $record) {
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
                        ->visible(function (UsulanSKPemberhentianSementara $record) {
                            return $record->pengajuanSurat &&
                                $record->pengajuanSurat->status_pengajuan === 'Diterima' &&
                                $record->pengajuanSurat->arsipSurat &&
                                $record->pengajuanSurat->arsipSurat->file_surat_path !== null;
                        }),
                ])
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
