<?php

namespace App\Filament\Resources\SubSubSectionsResource\Pages;

use App\Filament\Resources\SubSubSectionsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubSubSections extends CreateRecord
{
    protected static string $resource = SubSubSectionsResource::class;
    protected static ?string $title = 'Machinery Parts';
}
