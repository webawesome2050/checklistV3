<?php

namespace App\Filament\Resources\SubSubSectionsResource\Pages;

use App\Filament\Resources\SubSubSectionsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubSubSections extends ListRecords
{
    protected static string $resource = SubSubSectionsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
