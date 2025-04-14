<?php

namespace App\Filament\Resources\UsulanPermohonanCutiResource\Pages;

use App\Filament\Resources\UsulanPermohonanCutiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsulanPermohonanCutis extends ListRecords
{
    protected static string $resource = UsulanPermohonanCutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
