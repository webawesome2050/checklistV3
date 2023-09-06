<?php

namespace App\Filament\Resources\SubSubSectionsResource\Pages;

use App\Filament\Resources\SubSubSectionsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubSubSections extends EditRecord
{
    protected static string $resource = SubSubSectionsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
