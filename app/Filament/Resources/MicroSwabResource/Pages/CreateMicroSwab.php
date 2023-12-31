<?php

namespace App\Filament\Resources\MicroSwabResource\Pages;

use App\Filament\Resources\MicroSwabResource;
use App\Models\CheckList;
use App\Models\CheckListItemsEntry;
use App\Models\SubSectionItem;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Actions\Action as Action2;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMicroSwab extends CreateRecord
{
    protected static string $resource = MicroSwabResource::class;

    protected static ?string $title = 'Micro SPC Swab Check Check';

    public function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            Action::make('saveAnother')
                ->label('Submit and Continue')
                ->action('createAnother')
                ->keyBindings(['mod+shift+s'])
                ->color('gray'),
            // $this->getCancelFormAction(),
        ];
    }

    public function getCreateFormAction(): Action
    {
        return Action::make('create')
        // ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->label('Submit')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    public function mount(): void
    {
        // dd('form');

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    // Helper function to extract subsection ID from field name
    private function getSubsectionIdFromFieldName($fieldName)
    {
        // Extract subsection ID from the field name using a regular expression
        $matches = [];
        if (preg_match('/^subsection_(\d+)_/', $fieldName, $matches)) {
            return $matches[1];
        }

        return null; // Return null if no match is found
    }

    // Helper function to retrieve subsection item ID
    private function getSubsectionItemId($subsectionId)
    {
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
            if (! $another) {
                $checkList = CheckList::create([
                    'name' => 'Micro SPC Swab Check'.'_'.now(),
                    'site_id' => 2,
                    'type_id' => 5,
                    'entry_detail' => $data['person_name'],
                    'entry_detail' => $data['entry_detail'],
                    'person_name' => $data['person_name'],
                    'status' => 1,
                    // 'next_inspection_detail' => $data['next_inspection_detail'],
                ]);
            } else {
                $checkList = CheckList::create([
                    'name' => 'Micro SPC Swab Check'.'_'.now(),
                    'site_id' => 2,
                    'type_id' => 5,
                    'entry_detail' => $data['person_name'],
                    'entry_detail' => $data['entry_detail'],
                    'person_name' => $data['person_name'],
                    'status' => 0,
                    // 'next_inspection_detail' => $data['next_inspection_detail'],
                ]);
            }

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
            if (! $another) {
                Notification::make()
                    ->title('Micro SPC Swab Check Submitted!')
                    ->success()
                        // ->url(fn (CheckList $record): string => route('generate.pdf', $record))
                    ->body('Updated Micro SPC Swab Check Form, kindly view and approve')
                    ->actions([
                        Action2::make('View and Approve')
                            ->button()
                            ->url('/micro-swabs/'.$entryId)
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($recipient);
            }

            $this->callHook('afterCreate');

        } catch (Halt $exception) {
            return;
        }

        $this->getCreatedNotification()?->send();

        // if ($another) {
        //     $this->form->model($this->record::class);
        //     $this->record = null;
        //     $this->fillForm();

        //     return;
        // }

        // $this->redirect($this->getRedirectUrl());

        $this->redirect('/micro-swabs');

    }
}
