<?php

namespace App\Filament\Resources\ATPFormResource\Pages;

use Filament\Actions;
use App\Models\CheckList;
use App\Models\SubSectionItem;
use Illuminate\Support\Facades\Route;
use Filament\Resources\Pages\EditRecord;

use App\Filament\Resources\ATPFormResource;
use App\Filament\Resources\ChecklistsResource;
use App\Models\CheckListItemsEntry as Entries;

class EditATPForm extends EditRecord
{
    protected static string $resource = ATPFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    

    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();
        try {
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');
            $data = $this->mutateFormDataBeforeSave($data);
            $this->callHook('beforeSave');

                // $checkList  =  CheckList::create([
                //     'entry_detail' => $data['entry_detail'],    
                //     'next_inspection_detail' => $data['next_inspection_detail'],
                // ]);

               
                // Optional: You can also retrieve the updated record after the update
                


            foreach ($data as $fieldKey => $fieldValue) {
                $matches = [];
                if (preg_match('/^(.+)_(\d+)$/', $fieldKey, $matches)) {
                    $fieldName = $matches[1];
                    $checklistItemId = $matches[2];
                    $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;
                    // $subsectionId = $this->getSubsectionIdFromFieldName($fieldName);
                    // $dataByChecklistItem[$checklistItemId]['sub_section_item_id'] = $this->getSubsectionItemId($subsectionId);
                    
                } else {
                    // dd('No Match Found !');
                }
            }
            //  dd($dataByChecklistItem[1]['entry_id']);


          
            $checkList = CheckList::find($dataByChecklistItem[1]['entry_id']); 

            $checkList->update([
                'entry_detail' => $data['entry_detail'],
                'next_inspection_detail' => $data['next_inspection_detail'],
            ]);

            
            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                // $record = Entries::
                // where('check_list_items_id', $checklistItemId)
                // ->where('entry_id', $id)
                // ->first();
                $query = Entries::
                where('check_list_items_id', $checklistItemId)
                ->where('entry_id', $entryData['entry_id']);
                $record = $query->first();                
                if ($record) {
                    $this->handleRecordUpdate($record, $entryData);
                }
            }
            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }
    
        $this->getSavedNotification()?->send();
    
        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl);
        }
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
 
    
        public function mount($record): void
        {
    
            // dd($record);
    
            $this->record = $this->resolveRecord($record);
    
            $this->authorizeAccess();
    
            $this->fillForm();
    
            $this->fillExistingEntries(); // Add this line to fill existing entries
    
            $this->previousUrl = url()->previous();
        } 
    
    
    
        protected function fillExistingEntries(): void
        {
            $id = Route::current()->parameter('record');
            // dd($id);
            // $existingEntries = Entries::with('checkListItem')->where('entry_id', $id)->get();
            $existingEntries = Entries::where('entry_id', $id)->get();
            // dd($existingEntries);
        
            // 'visual_insp_allergen_free',
            // 'micro_SPC_swab',
            // 'chemical_residue_check',
            // 'TP_check_RLU',
            // 'comments_corrective_actions',
            // 'action_taken',
            // 'sub_section_items',

            foreach ($existingEntries as $entry) {
                $checklistItemId = $entry->check_list_items_id;
                $fieldsToUpdate = [ 
                    'entry_id',
                    'ATP_check_RLU',
                    'next_inspection_detail',
                    'entry_detail'
                ];
        
                foreach ($fieldsToUpdate as $fieldName) {
                    $fullFieldName = "{$fieldName}_$checklistItemId";
                    $this->data[$fullFieldName] = $entry->$fieldName;
                }
            }
        }
}
