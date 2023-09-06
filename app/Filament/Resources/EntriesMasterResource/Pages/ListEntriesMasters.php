<?php

namespace App\Filament\Resources\EntriesMasterResource\Pages;

use App\Filament\Resources\EntriesMasterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntriesMasters extends ListRecords
{
    protected static string $resource = EntriesMasterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
