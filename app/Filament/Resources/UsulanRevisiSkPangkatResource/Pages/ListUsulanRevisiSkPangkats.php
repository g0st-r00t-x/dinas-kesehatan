<?php

namespace App\Filament\Resources\UsulanRevisiSkPangkatResource\Pages;

use App\Filament\Resources\UsulanRevisiSkPangkatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsulanRevisiSkPangkats extends ListRecords
{
    protected static string $resource = UsulanRevisiSkPangkatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
