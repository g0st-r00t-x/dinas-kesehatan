<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataSerkomResource\Pages;
use App\Filament\Resources\DataSerkomResource\RelationManagers;
use App\Models\DataSerkom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataSerkomResource extends Resource
{
    protected static ?string $model = DataSerkom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Data';

    protected static ?string $label = 'Data Serkom';

    protected static ?string $pluralLabel = 'Data Serkom';

    protected static ?string $path = 'data-serkom';

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
            'index' => Pages\ListDataSerkoms::route('/'),
            'create' => Pages\CreateDataSerkom::route('/create'),
            'edit' => Pages\EditDataSerkom::route('/{record}/edit'),
        ];
    }
}
