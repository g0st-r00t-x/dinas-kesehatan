<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapAbsensiNonASNResource\Pages;
use App\Filament\Resources\RekapAbsensiNonASNResource\RelationManagers;
use App\Models\RekapAbsensiNonASN;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekapAbsensiNonASNResource extends Resource
{
    protected static ?string $model = RekapAbsensiNonASN::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Data';

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
            'index' => Pages\ListRekapAbsensiNonASNS::route('/'),
            'create' => Pages\CreateRekapAbsensiNonASN::route('/create'),
            'edit' => Pages\EditRekapAbsensiNonASN::route('/{record}/edit'),
        ];
    }
}
