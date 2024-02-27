<?php

namespace App\Filament\Resources\ConsentOptionResource\Pages;

use App\Filament\Resources\ConsentOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsentOption extends EditRecord
{
    protected static string $resource = ConsentOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
