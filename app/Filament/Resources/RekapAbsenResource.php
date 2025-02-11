<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapAbsenResource\Pages;
use App\Filament\Resources\RekapAbsenResource\RelationManagers;
use App\Models\RekapAbsen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekapAbsenResource extends Resource
{
    protected static ?string $model = RekapAbsen::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?string $navigationLabel = 'Rekap Absen';
    protected static ?string $modelLabel = 'Rekap Absen';
    protected static ?string $label = 'Rekap Absen';
    protected static ?string $pluralLabel = 'Rekap Absen';
    protected static ?string $path = 'rekap-absen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRekapAbsens::route('/'),
            'create' => Pages\CreateRekapAbsen::route('/create'),
            'edit' => Pages\EditRekapAbsen::route('/{record}/edit'),
        ];
    }
}
