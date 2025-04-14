<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanRevisiSkPangkatResource\Pages;
use App\Http\Controllers\PengajuanSuratController;
use App\Models\UsulanRevisiSkPangkat;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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

class UsulanRevisiSkPangkatResource extends Resource
{
    protected static ?string $model = UsulanRevisiSkPangkat::class;

    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'view_own', 'download_file', 'create', 'update', 'delete', 'delete_any', 'kirim_notif'];
    }


    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    protected static ?string $navigationLabel = 'Revisi SK Pangkat';
    
    protected static ?string $modelLabel = 'Revisi SK Pangkat';

    protected static ?string $navigationGroup = 'Usulan';

    protected static ?string $label = 'Revisi SK Pangkat';

    protected static ?string $pluralLabel = 'Revisi SK Pangkat';

    protected static ?string $path = 'usulan-revisi-sk-pangkat';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Hidden::make('user_id')
                ->default(Auth::user()->id),
                Section::make('Informasi Pegawai')
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
                                ->tel()
                                ->maxLength(20)
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

                    Section::make('Detail Revisi')
                    ->schema([
                        Forms\Components\Textarea::make('alasan_revisi_sk')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('kesalahan_tertulis_sk')
                            ->required()
                            ->rows(3),
                    ])->columns(1),

                Section::make('Upload Dokumen')
                    ->schema([
                        Forms\Components\FileUpload::make('upload_sk_salah')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('revisi_sk_pangkat/sk_salah')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120), // 5MB
                        
                        Forms\Components\FileUpload::make('upload_data_dukung')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('revisi_sk_pangkat/data_dukungan')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120),
                            
                        Forms\Components\FileUpload::make('surat_pengantar')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return now()->timestamp . '_' . $file->getClientOriginalName();
                            })
                            ->required()
                            ->directory('revisi_sk_pangkat/surat_pengantar')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nip')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('pangkat_golongan')
                    ->sortable(),
                TextColumn::make('upload_sk_salah')
                    ->label('SK Salah')
                    ->url(fn ($record) => Storage::url($record->sk_jabatan))
                    ->openUrlInNewTab(),
                               
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Pengajuan'),
                    
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('Ajukan Cuti')
                        ->icon('heroicon-o-document-plus')
                        ->action(fn(UsulanRevisiSkPangkat $record) => (new PengajuanSuratController())->handle($record, 'SK Pemberhentian Sementara')),
                    Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (UsulanRevisiSkPangkat $record) {
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
                        ->visible(function (UsulanRevisiSkPangkat $record) {
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
            'index' => Pages\ListUsulanRevisiSkPangkats::route('/'),
            'create' => Pages\CreateUsulanRevisiSkPangkat::route('/create'),
            'edit' => Pages\EditUsulanRevisiSkPangkat::route('/{record}/edit'),
        ];
    }
}
