<?php

namespace App\Filament\Resources\SectionsResource\Pages;

use App\Filament\Resources\SectionsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSections extends CreateRecord
{
    protected static string $resource = SectionsResource::class;
    protected static ?string $title = 'Area';
}
