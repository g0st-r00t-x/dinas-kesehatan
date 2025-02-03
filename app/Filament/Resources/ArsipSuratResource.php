<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArsipSuratResource\Pages;
use App\Filament\Resources\ArsipSuratResource\RelationManagers;
use App\Models\ArsipSurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArsipSuratResource extends Resource
{
    protected static ?string $model = ArsipSurat::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationGroup = 'Manajemen Data';

    protected static ?string $navigationLabel = 'Arsip Surat';
    
    protected static ?string $modelLabel = 'Arsip Surat';
    protected static ?string $label = 'Arsip Surat';
    protected static ?string $pluralLabel = 'Arsip Surat';
    protected static ?string $path = 'arsip-surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Arsip')
                    ->schema([
                        Forms\Components\Select::make('id_pengajuan_surat')
                            ->relationship('pengajuanSurat', 'nomor_sk')
                            ->label('Nomor Surat')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\FileUpload::make('file_surat_path')
                            ->label('File Surat')
                            ->required()
                            ->directory('arsip-surat')
                            ->preserveFilenames()
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['application/pdf'])
                            ->downloadable(),

                        Forms\Components\DateTimePicker::make('tgl_arsip')
                            ->label('Tanggal Arsip')
                            ->required()
                            ->default(now()),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengajuanSurat.nomor_sk')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pengajuanSurat.perihal')
                    ->label('Perihal')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('tgl_arsip')
                    ->label('Tanggal Arsip')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dibuat Dari'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Dibuat Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn($query) => $query->whereDate('created_at', '>=', $data['created_from'])
                            )
                            ->when(
                                $data['created_until'],
                                fn($query) => $query->whereDate('created_at', '<=', $data['created_until'])
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    // ->url(fn (ArsipSurat $record) => storage_url($record->file_surat_path))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListArsipSurats::route('/'),
            'create' => Pages\CreateArsipSurat::route('/create'),
            'edit' => Pages\EditArsipSurat::route('/{record}/edit'),
        ];
    }
}
