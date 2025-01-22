<?php

namespace App\Filament\Resources\DataSerkomResource\Pages;

use App\Filament\Resources\DataSerkomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataSerkoms extends ListRecords
{
    protected static string $resource = DataSerkomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
