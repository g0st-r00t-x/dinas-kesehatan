<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanPermohonanCutiResource\Pages;
use App\Filament\Resources\UsulanPermohonanCutiResource\RelationManagers;
use App\Models\UsulanPermohonanCuti;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsulanPermohonanCutiResource extends Resource
{
    protected static ?string $model = UsulanPermohonanCuti::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

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
            'index' => Pages\ListUsulanPermohonanCutis::route('/'),
            'create' => Pages\CreateUsulanPermohonanCuti::route('/create'),
            'edit' => Pages\EditUsulanPermohonanCuti::route('/{record}/edit'),
        ];
    }
}
