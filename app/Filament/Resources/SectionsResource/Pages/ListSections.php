<?php

namespace App\Filament\Resources\SectionsResource\Pages;

use App\Filament\Resources\SectionsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource = SectionsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
