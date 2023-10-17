<?php

namespace App\Filament\Resources\ChemicalResidueCheckResource\Pages;

use App\Filament\Resources\ChemicalResidueCheckResource;
use Filament\Actions;
use App\Models\User;
use App\Models\CheckList;
use App\Models\SubSectionItem;
use App\Models\CheckListItemsEntry;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

use Filament\Resources\Pages\CreateRecord;
    

class CreateChemicalResidueCheck extends CreateRecord
{
    protected static string $resource = ChemicalResidueCheckResource::class;

    protected static ?string $title = 'Chemical Residue Check';


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

            // dd($data['entry_detail']);

            $checkList  =  CheckList::create([
                'name' => 'Chemical Residue Check'.'_'.now(), 
                'site_id' => 2, 
                'type_id' => 4,
                'entry_detail' => $data['entry_detail'],
                'person_name' => $data['person_name']      
                // 'next_inspection_detail' => $data['next_inspection_detail'],
            ]); 
            $entryId = $checkList->id;

            $this->callHook('beforeCreate');  
            // dd($data);


            foreach ($data as $fieldKey => $fieldValue) {
                $matches = [];
               
                // if(($fieldKey !=='entry_detail') || ($fieldKey !=='next_inspection_detail')) {
                    if (preg_match('/^(.+)_(\d+)$/', $fieldKey, $matches)) {
                        $fieldName = $matches[1];
                        $checklistItemId = $matches[2];
                        $dataByChecklistItem[$checklistItemId]['check_list_items_id'] = $checklistItemId;
                        $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;
                        // Retrieve and include subsection item information
                        $subsectionId = $this->getSubsectionIdFromFieldName($fieldName);
                        $dataByChecklistItem[$checklistItemId]['sub_section_item_id'] = $this->getSubsectionItemId($subsectionId);
                        $dataByChecklistItem[$checklistItemId]['entry_id'] = $entryId;

                    } else {
                        // dd('No Match Found !');
                        // $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;
                    }
                // }
            }
            // dd($dataByChecklistItem); 
            
            // dd('Create Flow');
            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                // $this->record = $this->handleRecordCreation($entryData);
                if (is_array($entryData) && array_key_exists('sub_section_items', $entryData)) {
                    // \Log::info('$entryData[sub_section_items]',$entryData['sub_section_items']);
                    $entryData['sub_section_items'] = implode(', ', $entryData['sub_section_items']);
                }  
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
                    ->title('Chemical Residue Check Submitted!')
                    ->success()
                    // ->url(fn (CheckList $record): string => route('generate.pdf', $record))
                    ->body('Updated Chemical Residue Check Form, kindly view and approve')
                    ->actions([
                        Action::make('View and Approve')
                            ->button()
                            ->url('/chemical-residue-checks/'.$entryId)
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

        $this->redirect('/chemical-residue-checks');

    }

}
