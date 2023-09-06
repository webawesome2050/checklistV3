<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSite extends EditRecord
{
    protected static string $resource = SiteResource::class;

    protected function validateFormAndUpdateRecordAndCallHooks(): void
    {
     

        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        

        $this->callHook('afterValidate');

        $data = $this->mutateFormDataBeforeSave($data);

        $this->callHook('beforeSave');

        $this->handleRecordUpdate($this->getRecord(), $data);

        $this->callHook('afterSave');
    }
    
    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
