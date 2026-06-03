<?php

namespace App\Filament\Resources\HypothesisResource\Pages;

use App\Filament\Resources\HypothesisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHypotheses extends ListRecords
{
    protected static string $resource = HypothesisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
