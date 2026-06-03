<?php

namespace App\Filament\Resources\HypothesisResource\Pages;

use App\Filament\Resources\HypothesisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHypothesis extends EditRecord
{
    protected static string $resource = HypothesisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
