<?php

namespace App\Filament\Resources\MicroSwabResource\Pages;

use App\Filament\Resources\MicroSwabResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use App\Models\User;
use App\Models\CheckList;
use App\Models\SubSectionItem;
use Illuminate\Support\Facades\Route;

use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\ATPFormResource;
use App\Filament\Resources\ChecklistsResource;
use App\Models\CheckListItemsEntry as Entries;

class EditMicroSwab extends EditRecord
{
    protected static string $resource = MicroSwabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
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
 

            foreach ($data as $fieldKey => $fieldValue) {
                $matches = [];
                if (preg_match('/^(.+)_(\d+)$/', $fieldKey, $matches)) {
                    $fieldName = $matches[1];
                    $checklistItemId = $matches[2];
                    $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;
                    
                } else {
                    $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;
                }
            }
            

          
            $checkList = CheckList::find($data['id']); 
            $checkList->update([
                'entry_detail' => $data['entry_detail'],
                // 'next_inspection_detail' => $data['next_inspection_detail'],
            ]);

            
            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                $query = Entries::
                where('check_list_items_id', $checklistItemId)
                ->where('entry_id', $entryData['entry_id']);
                $record = $query->first();                
                if ($record) {
                    $this->handleRecordUpdate($record, $entryData);
                }
            }

          


            $recipient = User::whereHas('sites', function ($query) {
                $query->where('site_id', 2);
            })
            ->whereHas('roles', function ($query) {
                $query->where('role_id', 3);
            })->get();


              

                Notification::make()
                    ->title('ATP Submitted!')
                    ->success()
                    ->body('Updated ATP Form, kindly view and approve')
                    ->actions([
                        Action::make('View and Approve')
                            ->button()
                            ->url('/atp-check/'.$this->record->id)
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($recipient);

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
    

     private function getSubsectionIdFromFieldName($fieldName) {
        $matches = [];
        if (preg_match('/^subsection_(\d+)_/', $fieldName, $matches)) {
            return $matches[1];
        }
        return null; // Return null if no match is found
    }


    // Helper function to retrieve subsection item ID
    private function getSubsectionItemId($subsectionId) {
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

            // 'entry_id',


            foreach ($existingEntries as $entry) {
                $checklistItemId = $entry->check_list_items_id;
                $fieldsToUpdate = [ 
                    'entry_id',
                    'micro_SPC_swab',
                    // 'next_inspection_detail',
                    'entry_detail',
                    'sub_section_items'
                ];
        
                foreach ($fieldsToUpdate as $fieldName) {
                    $fullFieldName = "{$fieldName}_$checklistItemId";
                    $this->data[$fullFieldName] = $entry->$fieldName;
                }
            }

            // dd($this->data);
        }

}