<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use App\Models\CheckList;

use Filament\Pages\Actions;
use App\Models\CheckListItemsEntry;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ChecklistsResource;

class CreateChecklists extends CreateRecord
{
    protected static string $resource = ChecklistsResource::class;

    protected static ?string $getTitle = 'QC Forms - Type I';


    public function mount(): void
    {
        // dd('form'); 

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

   


    public function create(bool $another = false): void
    {
        $this->authorizeAccess();


        $checkList  =  CheckList::create([
            'name' => 'Form SW1 - Hygiene swab and Pre - Op checklist'.'_'.now(), 
            'site_id' => 2, 
            'type_id' => 1
        ]);

        $entryId = $checkList->id;




        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            // dd($data); 

            $this->callHook('beforeCreate'); 

            foreach ($data as $fieldKey => $fieldValue) {
                $matches = [];
               
                if (preg_match('/^(.+)_(\d+)$/', $fieldKey, $matches)) {
                    $fieldName = $matches[1];
                    $checklistItemId = $matches[2];
                    $dataByChecklistItem[$checklistItemId]['check_list_items_id'] = $checklistItemId;
                    $dataByChecklistItem[$checklistItemId]['entry_id'] = $entryId;
                    $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;
                } else {
                    dd('No Match Found !');
                }
            }
           
            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                // $this->record = $this->handleRecordCreation($entryData);
                CheckListItemsEntry::create($entryData);
            }
            // dd($entryData);
            // $this->record = $this->handleRecordCreation($data); 
            // $this->form->model($this->record)->saveRelationships();
            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->record::class);
            $this->record = null;
            $this->fillForm();
            return;
        }

        // $this->redirect($this->getRedirectUrl());

        $this->redirect('/checklists');

    }


  

}
