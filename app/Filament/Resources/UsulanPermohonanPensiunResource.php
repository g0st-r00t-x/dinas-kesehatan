<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PermohonanPensiunExporter;
use App\Filament\Imports\PermohonanPensiunImporter;
use App\Filament\Resources\UsulanPermohonanPensiunResource\Pages;
use App\Http\Controllers\PengajuanSuratController;
use App\Models\UsulanPermohonanPensiun;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UsulanPermohonanPensiunResource extends Resource
{
    protected static ?string $model = UsulanPermohonanPensiun::class;


    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'view_own', 'download_file', 'create', 'update', 'delete', 'delete_any', 'kirim_notif'];
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?string $navigationLabel = 'Permohonan Pensiun';
    protected static ?string $modelLabel = 'Permohonan Pensiun';
    protected static ?string $pluralModelLabel = 'Permohonan Pensiun';
    protected static ?string $path = 'usulan-permohonan-pensiun';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Hidden::make('user_id')
                ->default(Auth::user()->id),
                Forms\Components\Section::make('Data Pribadi')
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
                    ])->columns(2),

                Forms\Components\Section::make('Dokumen Wajib')
                    ->schema([
                        Forms\Components\FileUpload::make('surat_pengantar_unit')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/surat-pengantar'),
                        Forms\Components\FileUpload::make('sk_pangkat_terakhir')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/sk-pangkat'),
                        Forms\Components\FileUpload::make('sk_cpcns')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/sk-cpcns'),
                        Forms\Components\FileUpload::make('sk_pns')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/sk-pns'),
                        Forms\Components\FileUpload::make('sk_berkala_terakhir')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/sk-berkala'),
                        Forms\Components\FileUpload::make('skp_terakhir')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/skp'),
                    ])->columns(2),

                Forms\Components\Section::make('Dokumen Pendukung')
                    ->schema([
                        Forms\Components\FileUpload::make('akte_nikah')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/akte-nikah'),
                        Forms\Components\FileUpload::make('ktp_pasangan')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/ktp'),
                        Forms\Components\FileUpload::make('karis_karsu')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('pensiun_files/karis-karsu'),
                        Forms\Components\FileUpload::make('akte_anak')
                            ->directory('pensiun_files/akte-anak'),
                        Forms\Components\FileUpload::make('surat_kuliah')
                            ->directory('pensiun_files/surat-kuliah'),
                        Forms\Components\FileUpload::make('akte_kematian')
                            ->directory('pensiun_files/akte-kematian'),
                    ])->columns(2),

                Forms\Components\Section::make('Data Bank')
                    ->schema([
                        Forms\Components\TextInput::make('nama_bank')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nomor_rekening')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('npwp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('foto')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->previewable()
                            ->directory('pensiun_files/foto'),
                        Forms\Components\TextInput::make('nomor_telepon')
                            ->tel()
                            ->maxLength(15)
                            ->required()
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(PermohonanPensiunImporter::class),
                ExportAction::make()

                    ->exporter(PermohonanPensiunExporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pangkat_golongan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('surat_pengantar_unit')
                    ->url(fn($record) => Storage::url($record->surat_pengantar_unit))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('Ajukan Cuti')
                        ->icon('heroicon-o-document-plus')
                        ->action(fn(UsulanPermohonanPensiun $record) => (new PengajuanSuratController())->handle($record, 'Permohonan Pensiun')),
                    Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($record) {
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
                        ->visible(function ($record) {
                            return $record->pengajuanSurat &&
                                $record->pengajuanSurat->status_pengajuan === 'Diterima' &&
                                $record->pengajuanSurat->arsipSurat &&
                                $record->pengajuanSurat->arsipSurat->file_surat_path !== null;
                        }),
                ])
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListUsulanPermohonanPensiuns::route('/'),
            'create' => Pages\CreateUsulanPermohonanPensiun::route('/create'),
            'edit' => Pages\EditUsulanPermohonanPensiun::route('/{record}/edit'),
        ];
    }
}
