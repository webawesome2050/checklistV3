<?php

namespace App\Filament\Resources\SubSectionsResource\Pages;

use App\Filament\Resources\SubSectionsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubSections extends ListRecords
{
    protected static string $resource = SubSectionsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
