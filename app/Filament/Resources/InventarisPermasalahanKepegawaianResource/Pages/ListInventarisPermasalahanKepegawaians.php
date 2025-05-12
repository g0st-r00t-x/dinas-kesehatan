<?php

namespace App\Filament\Resources\InventarisPermasalahanKepegawaianResource\Pages;

use App\Filament\Resources\InventarisPermasalahanKepegawaianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventarisPermasalahanKepegawaians extends ListRecords
{
    protected static string $resource = InventarisPermasalahanKepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
