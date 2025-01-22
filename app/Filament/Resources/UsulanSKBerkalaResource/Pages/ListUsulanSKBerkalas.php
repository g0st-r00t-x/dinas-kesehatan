<?php

namespace App\Filament\Resources\UsulanSKBerkalaResource\Pages;

use App\Filament\Resources\UsulanSKBerkalaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsulanSKBerkalas extends ListRecords
{
    protected static string $resource = UsulanSKBerkalaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
