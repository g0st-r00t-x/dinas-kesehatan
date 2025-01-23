<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanSKPemberhentianSementaraResource\Pages;
use App\Filament\Resources\UsulanSKPemberhentianSementaraResource\RelationManagers;
use App\Models\UsulanSKPemberhentianSementara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsulanSKPemberhentianSementaraResource extends Resource
{
    protected static ?string $model = UsulanSKPemberhentianSementara::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

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
            'index' => Pages\ListUsulanSKPemberhentianSementaras::route('/'),
            'create' => Pages\CreateUsulanSKPemberhentianSementara::route('/create'),
            'edit' => Pages\EditUsulanSKPemberhentianSementara::route('/{record}/edit'),
        ];
    }
}
