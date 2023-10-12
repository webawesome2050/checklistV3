<?php

namespace App\Filament\Resources\QcFormTypeTwoResource\Pages;
use App\Models\User;
use App\Models\CheckList;
use App\Filament\Resources\QcFormTypeTwoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\CheckListItemsEntry;

use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;


class CreateQcFormTypeTwo extends CreateRecord
{
    protected static string $resource = QcFormTypeTwoResource::class;

    protected static ?string $title = 'GMP';


    public function create(bool $another = false): void
    {
        $this->authorizeAccess();


        

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            // dd($data); 

            $checkList  =  CheckList::create([
                'name' => 'Form GM1- GMP checklist'.'_'.now(), 
                'site_id' => 2, 
                'entry_detail' => $data['entry_detail'],    
                'person_name' => $data['person_name'],    
                'type_id' => 2
            ]);
    
            $entryId = $checkList->id; 
            

            $this->callHook('beforeCreate'); 

            foreach ($data as $fieldKey => $fieldValue) {
                $matches = [];
               
                if (preg_match('/^(.+)_(\d+)$/', $fieldKey, $matches)) {
                    $fieldName = $matches[1];
                    $checklistItemId = $matches[2];
                    $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;
                    $dataByChecklistItem[$checklistItemId]['entry_id'] = $entryId;
                    $dataByChecklistItem[$checklistItemId]['check_list_items_id'] = $checklistItemId;


                } else {
                    // dd('No Match Found !');
                }
            }
           
            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                // $this->record = $this->handleRecordCreation($entryData);
                CheckListItemsEntry::create($entryData);
            }
            // dd($entryData);
            // $this->record = $this->handleRecordCreation($data); 
            // $this->form->model($this->record)->saveRelationships();


            $recipient = User::whereHas('sites', function ($query) {
                $query->where('site_id', 2);
            })
            ->whereHas('roles', function ($query) {
                $query->where('role_id', 3);
            })->get();
    
            \Log::info($recipient);
    
                // Notification::make()
                // ->title('Updated ATP Form, kindly view and approve')
                // ->sendToDatabase($recipient);
    
                Notification::make()
                    ->title('GMP Submitted!')
                    ->success()
                    ->body('Created GMP Form, kindly view and approve')
                    ->actions([
                        Action::make('View and Approve')
                            ->button()
                            ->url('/qc-form-type-twos/'.$entryId)
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($recipient);

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

        $this->redirect('/qc-form-type-twos');

    }

    
}
