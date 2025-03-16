<?php

namespace App\Filament\Resources\RekapAbsenResource\Pages;

use App\Filament\Resources\RekapAbsenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRekapAbsens extends ListRecords
{
    protected static string $resource = RekapAbsenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
