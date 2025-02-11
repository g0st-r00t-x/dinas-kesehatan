<?php

namespace App\Filament\Resources\JenisSuratResource\Pages;

use App\Filament\Resources\JenisSuratResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisSurats extends ListRecords
{
    protected static string $resource = JenisSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
