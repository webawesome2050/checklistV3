<?php

namespace App\Filament\Resources\FormTv1263Resource\Pages;

use App\Filament\Resources\FormTv1263Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFormTv1263 extends CreateRecord
{
    protected static string $resource = FormTv1263Resource::class;
    protected static ?string $title = 'TV: Chiller and Freezer Temperature Daily Verification';
   
    protected function getRedirectUrl(): string
        {
            return $this->getResource()::getUrl('index');
        }
        
}
