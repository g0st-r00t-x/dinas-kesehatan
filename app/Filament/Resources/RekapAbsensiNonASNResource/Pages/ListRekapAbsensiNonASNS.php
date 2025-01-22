<?php

namespace App\Filament\Resources\RekapAbsensiNonASNResource\Pages;

use App\Filament\Resources\RekapAbsensiNonASNResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRekapAbsensiNonASNS extends ListRecords
{
    protected static string $resource = RekapAbsensiNonASNResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
