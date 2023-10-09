<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use App\Models\CheckList;

use Filament\Pages\Actions;
use App\Models\SubSectionItem;
use App\Models\CheckListItemsEntry;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ChecklistsResource;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Models\User;

class CreateChecklists extends CreateRecord
{
    protected static string $resource = ChecklistsResource::class;

    protected static ?string $title = 'Hygiene swab';


    public function mount(): void
    {
        // dd('form'); 

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }


            // Helper function to extract subsection ID from field name
            private function getSubsectionIdFromFieldName($fieldName) {
                // Extract subsection ID from the field name using a regular expression
                $matches = [];
                if (preg_match('/^subsection_(\d+)_/', $fieldName, $matches)) {
                    return $matches[1];
                }
                return null; // Return null if no match is found
            }


            // Helper function to retrieve subsection item ID
            private function getSubsectionItemId($subsectionId) {
                // Query your database to retrieve the subsection item ID based on the subsection ID
                // Replace 'SubSectionItem' with the actual model name for your subsection items
                $subsectionItem = SubSectionItem::where('sub_section_id', $subsectionId)->first();
                
                if ($subsectionItem) {
                    return $subsectionItem->id;
                }
                
                return null; // Return null if no subsection item is found for the subsection ID
            }


   


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
                'name' => 'Form SW1 - Hygiene swab and Pre - Op checklist'.'_'.now(), 
                'site_id' => 2, 
                'type_id' => 1,
                'entry_detail' => $data['entry_detail'],    
                'person_name' => $data['person_name']  
            ]);
    
            $entryId = $checkList->id;
            
            $this->callHook('beforeCreate'); 

            foreach ($data as $fieldKey => $fieldValue) {
                $matches = [];
               
                if (preg_match('/^(.+)_(\d+)$/', $fieldKey, $matches)) {
                    $fieldName = $matches[1];
                    $checklistItemId = $matches[2];
                    $dataByChecklistItem[$checklistItemId]['check_list_items_id'] = $checklistItemId;
                    $dataByChecklistItem[$checklistItemId]['entry_id'] = $entryId;
                    $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;

                    // Retrieve and include subsection item information
                    $subsectionId = $this->getSubsectionIdFromFieldName($fieldName);
                    $dataByChecklistItem[$checklistItemId]['sub_section_item_id'] = $this->getSubsectionItemId($subsectionId);


                } else {
                   // dd('No Match Found !');
                }
            }
           
            // \Log::info('dataByChecklistItem');
            // \Log::info($dataByChecklistItem);
            // \Log::info('dataByChecklistItem');
            
            // dd('Create Flow');
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
            
            Notification::make()
                    ->title('Hygiene swab Submitted!')
                    ->success()
                    // ->url(fn (CheckList $record): string => route('generate.pdf', $record))
                    ->body('Created Hygiene swab Form, kindly view and approve')
                    ->actions([
                        Action::make('View and Approve')
                            ->button()
                            ->url('/checklists/'.$entryId)
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

        $this->redirect('/checklists');

    }


  

}
