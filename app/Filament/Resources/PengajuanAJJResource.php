<?php

namespace App\Filament\Resources;

use App\Filament\Exports\InventarisAJJExporter;
use App\Filament\Imports\InventarisAJJImporter;
use App\Filament\Resources\PengajuanAJJResource\Pages;
use App\Models\InventarisAJJ;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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

class PengajuanAJJResource extends Resource
{
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
                TextInput::make('nama')
                    ->label('Nama')
                    ->required(),

                TextInput::make('nip')
                    ->label('NIP')
                    ->required(),

                TextInput::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->required(),

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
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tmt_pemberian_tunjangan')
                    ->label('TMT Pemberian Tunjangan')
                    ->date()
                    ->sortable(),
                TextColumn::make('sk_jabatan')
                    ->label('SK Jabatan'),
                TextColumn::make('upload_berkas')
                    ->label('Upload Berkas')
                    ->url(fn ($record) => Storage::url($record->upload_berkas))
                    ->openUrlInNewTab(),
                TextColumn::make('surat_pengantar_unit_kerja')
                    ->label('Surat Pengantar Unit Kerja')
                    ->url(fn ($record) => Storage::url($record->surat_pengantar_unit_kerja))
                    ->openUrlInNewTab(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->options(function () {
                        return InventarisAJJ::distinct()
                            ->orderBy('unit_kerja')
                            ->pluck('unit_kerja', 'unit_kerja')
                            ->toArray();
                    })
                    ->multiple()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                                Tables\Actions\Action::make('pdf') 
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Model $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo Pdf::loadHtml(
                                Blade::render('pdf', ['record' => $record])
                            )->stream();
                        }, $record->nip . '.pdf');
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