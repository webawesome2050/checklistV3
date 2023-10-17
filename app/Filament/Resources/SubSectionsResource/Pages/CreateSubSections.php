<?php

namespace App\Filament\Resources\SubSectionsResource\Pages;

use App\Filament\Resources\SubSectionsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubSections extends CreateRecord
{
    protected static string $resource = SubSectionsResource::class;
    protected static ?string $title = 'Machine Name / GMP QC';
}
