<?php

namespace App\Filament\Resources\DataSerkomResource\Pages;

use App\Filament\Resources\DataSerkomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataSerkom extends EditRecord
{
    protected static string $resource = DataSerkomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
