<?php

namespace App\Filament\Resources\FormTv1263Resource\Pages;

use App\Filament\Resources\FormTv1263Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormTv1263s extends ListRecords
{
    protected static string $resource = FormTv1263Resource::class;

    protected static ?string $title = 'TV: Chiller and Freezer Temperature Daily Verification';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create New')
            ->createAnother(false),
        ];
    }
}
