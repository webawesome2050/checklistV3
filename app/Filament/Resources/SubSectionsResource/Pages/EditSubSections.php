<?php

namespace App\Filament\Resources\SubSectionsResource\Pages;

use App\Filament\Resources\SubSectionsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubSections extends EditRecord
{
    protected static string $resource = SubSectionsResource::class;

    protected static ?string $title = 'Machine Name / GMP QC';

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
