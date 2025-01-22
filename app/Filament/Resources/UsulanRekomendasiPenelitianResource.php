<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanRekomendasiPenelitianResource\Pages;
use App\Filament\Resources\UsulanRekomendasiPenelitianResource\RelationManagers;
use App\Models\UsulanRekomendasiPenelitian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsulanRekomendasiPenelitianResource extends Resource
{
    protected static ?string $model = UsulanRekomendasiPenelitian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Usulan';

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
            'index' => Pages\ListUsulanRekomendasiPenelitians::route('/'),
            'create' => Pages\CreateUsulanRekomendasiPenelitian::route('/create'),
            'edit' => Pages\EditUsulanRekomendasiPenelitian::route('/{record}/edit'),
        ];
    }
}
