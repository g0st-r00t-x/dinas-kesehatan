<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarisPermasalahanKepegawaianResource\Pages;
use App\Filament\Resources\InventarisPermasalahanKepegawaianResource\RelationManagers;
use App\Models\InventarisirPermasalahanKepegawaian;
use App\Models\InventarisPermasalahanKepegawaian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventarisPermasalahanKepegawaianResource extends Resource
{
    protected static ?string $model = InventarisirPermasalahanKepegawaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Permasalahan Pegawai';
    protected static ?string $pluralLabel = 'Permasalahan Pegawai';

    protected static ?string $navigationGroup = 'Inventaris';

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
            'index' => Pages\ListInventarisPermasalahanKepegawaians::route('/'),
            'create' => Pages\CreateInventarisPermasalahanKepegawaian::route('/create'),
            'edit' => Pages\EditInventarisPermasalahanKepegawaian::route('/{record}/edit'),
        ];
    }
}
