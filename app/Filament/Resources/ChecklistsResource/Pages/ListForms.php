<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use App\Filament\Resources\ChecklistsResource;
use Filament\Resources\Pages\Page;

class ListForms extends Page
{
    protected static string $resource = ChecklistsResource::class;

    protected static string $view = 'filament.resources.checklists-resource.pages.list-forms';
    protected static ?string $title = 'QC Forms';

    public function mount($record): void
    {
             // dd($record);
    }

}
