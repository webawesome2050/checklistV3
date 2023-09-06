<?php

namespace App\Filament\Resources\FormTvResource\Pages;

use App\Filament\Resources\FormTvResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFormTv extends CreateRecord
{
    protected static string $resource = FormTvResource::class;

    protected static ?string $title = 'Temperature Daily Verification';

}
