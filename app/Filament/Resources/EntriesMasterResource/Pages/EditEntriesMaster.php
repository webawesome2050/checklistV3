<?php

namespace App\Filament\Resources\EntriesMasterResource\Pages;

use App\Filament\Resources\EntriesMasterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\CheckListItemsEntry as Entries;

class EditEntriesMaster extends EditRecord
{
    protected static string $resource = EntriesMasterResource::class;

    protected static ?string $title = 'Approver - QC Forms';

    // public function save(bool $shouldRedirect = true): void
    // {
        
    //     $this->authorizeAccess();
    //     try {
    //         $this->callHook('beforeValidate');
    //         $data = $this->form->getState();
    //         $this->callHook('afterValidate');
    //         $data = $this->mutateFormDataBeforeSave($data);
    //         // dd($data);
    //         $this->callHook('beforeSave'); 
    //          $recordId = 1; 
    //         $existingRecords = Entries::where('entry_id', $recordId)->get(); 
    //         foreach ($data as $fieldKey => $fieldValue) {
 
    //             $matches = [];
    //             if (preg_match('/^comments_corrective_actions_(\d+)$/', $fieldKey, $matches)) {
    //                 $checklistItemId = $matches[1]; 
    //                 $existingRecord = $existingRecords->firstWhere('check_list_items_id', $checklistItemId); 

    //                  $entryData = [
    //                     // Populate the necessary data for each checklist item entry
    //                     'check_list_items_id' => $checklistItemId,
    //                     'comments_corrective_actions' => $fieldValue,
    //                 ];

    //                  if ($existingRecord) {
    //                     $existingRecord->update($entryData);
    //                 } else {
    //                     // dd($entryData);
    //                     Entries::create($entryData);
    //                 } 
    //             } else {
    //                 dd('No Match Found !');
    //             }
    //         } 
    //         $this->callHook('afterSave');
    //     } catch (Halt $exception) {
    //         return;
    //     }

    //     $this->getSavedNotification()?->send();

    //     if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
    //         $this->redirect($redirectUrl);
    //     }
    // }

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
