<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PermasalahanKepegawaianExporter;
use App\Filament\Imports\PermasalahanKepegawaianImporter;
use App\Filament\Resources\InventarisPermasalahanKepegawaianResource\Pages;
use App\Filament\Resources\InventarisPermasalahanKepegawaianResource\RelationManagers;
use App\Models\InventarisirPermasalahanKepegawaian;
use App\Models\InventarisPermasalahanKepegawaian;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class InventarisPermasalahanKepegawaianResource extends Resource
{
    protected static ?string $model = InventarisirPermasalahanKepegawaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'Permasalahan Pegawai';
    protected static ?string $pluralLabel = 'Permasalahan Pegawai';

    protected static ?string $navigationGroup = 'Inventaris';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nip')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('pangkat_golongan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('jabatan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('unit_kerja')
                    ->required()
                    ->maxLength(255),
                Textarea::make('permasalahan')
                    ->required()
                    ->rows(4),
                Select::make('data_dukungan_id')
                    ->relationship('dataDukungan', 'jenis')
                    ->required(),
                FileUpload::make('file_upload')
                    ->directory('permasalahan-pegawai/permasalahan-files')
                    ->preserveFilenames(),
                FileUpload::make('surat_pengantar_unit_kerja')
                    ->directory('permasalahan-pegawai/surat-pengantar')
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->headerActions([
            ImportAction::make()
                ->importer(PermasalahanKepegawaianImporter::class),
            ExportAction::make()
                ->exporter(PermasalahanKepegawaianExporter::class)
        ])
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nip')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pangkat_golongan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jabatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit_kerja')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('permasalahan')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('dataDukungan.jenis')
                    ->label('Data Dukungan')
                    ->sortable(),
                TextColumn::make('file_upload')
                    ->url(fn ($record) => Storage::url($record->file_upload))
                    ->openUrlInNewTab()
                    ->label('File Upload'),
                TextColumn::make('surat_pengantar_unit_kerja')
                    ->url(fn ($record) => Storage::url($record->surat_pengantar_unit_kerja))
                    ->openUrlInNewTab()
                    ->label('Surat Pengantar'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListInventarisPermasalahanKepegawaians::route('/'),
            'create' => Pages\CreateInventarisPermasalahanKepegawaian::route('/create'),
            'edit' => Pages\EditInventarisPermasalahanKepegawaian::route('/{record}/edit'),
        ];
    }
}
