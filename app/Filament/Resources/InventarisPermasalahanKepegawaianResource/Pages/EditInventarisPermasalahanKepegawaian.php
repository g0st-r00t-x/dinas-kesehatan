<?php

namespace App\Filament\Resources\InventarisPermasalahanKepegawaianResource\Pages;

use App\Filament\Resources\InventarisPermasalahanKepegawaianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventarisPermasalahanKepegawaian extends EditRecord
{
    protected static string $resource = InventarisPermasalahanKepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
