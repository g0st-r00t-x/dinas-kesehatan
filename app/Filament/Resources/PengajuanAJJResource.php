<?php

namespace App\Filament\Resources;

use App\Filament\Exports\InventarisAJJExporter;
use App\Filament\Imports\InventarisAJJImporter;
use App\Filament\Resources\PengajuanAJJResource\Pages;
use App\Models\InventarisAJJ;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PengajuanAJJResource extends Resource implements HasShieldPermissions
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
    protected static ?string $model = InventarisAJJ::class;

    protected static ?string $navigationIcon = 'letsicon-paper-light';

    protected static ?string $label = 'Penerbitan AJJ';

    protected static ?string $pluralLabel = 'Penerbitan AJJ';

    protected static ?string $navigationGroup = 'Inventaris';

    protected static ?string $path = 'inventaris-ajj';

    
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

                DatePicker::make('tmt_pemberian_tunjangan')
                    ->label('TMT Pemberian Tunjangan')
                    ->required(),

                Select::make('sk_jabatan')
                    ->label('SK Jabatan')
                    ->options([
                        'ada' => 'Ada',
                        'tidak ada' => 'Tidak Ada',
                    ]),

                FileUpload::make('upload_berkas')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return now()->timestamp . '_' . $file->getClientOriginalName();
                    })
                    ->label('Upload Berkas')
                    ->helperText(str('**Jika Tidak memiliki SK Jabatan Fungsional Maka Upload SK Pemberian Tunjangan**')->inlineMarkdown()->toHtmlString())
                    ->directory('pengajua-ajj/upload_berkas')
                    ->disk('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(1024)
                    ->required(),

                FileUpload::make('surat_pengantar_unit_kerja')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return now()->timestamp . '_' . $file->getClientOriginalName();
                    })
                    ->label('Surat Pengantar Unit Kerja')
                    ->directory('pengajua-ajj/surat_pengantar_unit_kerja')
                    ->disk('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(512)
                    ->required(),
            ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('pegawai.nama') // Relasi pegawai berdasarkan model
                ->label('Nama Pegawai')
                ->searchable()
                ->sortable(),
            TextColumn::make('pegawai_nip') // Menggunakan pegawai_nip karena di model memakai ini
                ->label('NIP Pegawai')
                ->searchable()
                ->sortable(),
            TextColumn::make('unitKerja.nama') // Menggunakan nama unit kerja
                ->label('Unit Kerja')
                ->searchable()
                ->sortable(),
            TextColumn::make('tmt_pemberian_tunjangan')
                ->label('TMT Pemberian Tunjangan')
                ->date('Y-m-d') // Format Y-m-d
                ->sortable(),
            TextColumn::make('sk_jabatan')
                ->label('SK Jabatan'),
            TextColumn::make('upload_berkas')
                ->label('Upload Berkas')
                ->url(fn ($record) => $record->upload_berkas 
                    ? (str_starts_with($record->upload_berkas, 'http') 
                        ? $record->upload_berkas 
                        : Storage::url($record->upload_berkas))
                    : null
                )
                ->openUrlInNewTab(),
            TextColumn::make('surat_pengantar_unit_kerja')
                ->label('Surat Pengantar Unit Kerja')
                ->url(fn ($record) => $record->surat_pengantar_unit_kerja 
                    ? Storage::url($record->surat_pengantar_unit_kerja) 
                    : null
                )
                ->openUrlInNewTab(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('unit_kerja_id') // Menggunakan unit_kerja_id sesuai di database
                ->label('Unit Kerja')
                ->options(fn () => \App\Models\UnitKerja::all()->pluck('nama', 'id')->toArray())
                ->multiple()
                ->searchable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Action::make('pdf')
                ->label('PDF')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (Model $record) {
                    return response()->streamDownload(function () use ($record) {
                        echo Pdf::loadView('pdf', ['record' => $record])->stream();
                    }, $record->pegawai_nip . '.pdf');
                }),
            Action::make('wa_notif')
                ->label('Kirim Notifikasi')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->visible(fn (Model $user) => auth()->user()?->hasPermissionTo('kirim_notif_pengajuan::a::j::j'))
                ->action(function (Model $record) {
                    // Your action code
                }),
        ])
        ->headerActions([
            ImportAction::make()
                ->importer(InventarisAJJImporter::class),
            ExportAction::make()
                ->exporter(InventarisAJJExporter::class)
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPengajuanAJJS::route('/'),
            'create' => Pages\CreatePengajuanAJJ::route('/create'),
            'edit' => Pages\EditPengajuanAJJ::route('/{record}/edit'),
        ];
    }
}