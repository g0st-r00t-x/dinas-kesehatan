<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisSuratResource\Pages;
use App\Models\JenisSurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class JenisSuratResource extends Resource
{
    protected static ?string $model = JenisSurat::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Jenis Surat';
    protected static ?string $modelLabel = 'Jenis Surat';
    protected static ?string $navigationGroup = 'Arsip Surat';
    protected static ?string $pluralLabel = 'Jenis Surat';
    protected static ?string $slug = 'jenis-surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Jenis Surat')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Jenis Surat')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama jenis surat'),

                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Surat')
                            ->required()
                            ->maxLength(50)
                            ->hint('Masukkan singkatan dari nama surat, seperti "Permohonan Cuti" menjadi "PC".')
                            ->placeholder('Contoh: PC, PP, dll')
                            ->unique(ignoreRecord: true),

                        Forms\Components\FileUpload::make('template_surat')
                            ->label('Template Surat')
                            ->directory('templates-surat')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(5120) // 5MB
                            ->downloadable()
                            ->openable()
                    ])
                    ->columns(1)
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Jenis Surat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode Surat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('template_surat')
                    ->label('Template')
                    ->icon('heroicon-o-document')
                    ->formatStateUsing(fn($state) => $state ? 'Lihat Template' : '-')
                    ->url(fn($record) => $record->template_surat
                        ? Storage::url($record->template_surat)
                        : null)
                    ->openUrlInNewTab()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->before(function (JenisSurat $record) {
                            // Delete the template file if exists
                            if ($record->template_surat && Storage::exists($record->template_surat)) {
                                Storage::delete($record->template_surat);
                            }
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Delete template files for all selected records
                            foreach ($records as $record) {
                                if ($record->template_surat && Storage::exists($record->template_surat)) {
                                    Storage::delete($record->template_surat);
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJenisSurats::route('/'),
            'create' => Pages\CreateJenisSurat::route('/create'),
            'edit' => Pages\EditJenisSurat::route('/{record}/edit'),
        ];
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama', 'kode'];
    }
}
