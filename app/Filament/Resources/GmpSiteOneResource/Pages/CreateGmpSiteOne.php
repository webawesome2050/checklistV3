<?php

namespace App\Filament\Resources\GmpSiteOneResource\Pages;

use App\Filament\Resources\GmpSiteOneResource;
use App\Models\CheckList;
use App\Models\CheckListItemsEntry;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Actions\Action as Action2;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGmpSiteOne extends CreateRecord
{
    protected static string $resource = GmpSiteOneResource::class;

    protected static ?string $title = 'GMP';

    public function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            Action::make('saveAnother')
                ->label('Save and Continue')
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

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            // dd($data);
            if (! $another) {
                $checkList = CheckList::create([
                    'name' => 'Form GM1- GMP checklist'.'_'.now(),
                    'site_id' => 1,
                    // 'entry_detail' => $data['entry_detail'],
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'person_name' => $data['person_name'],
                    'type_id' => 7,
                    'status' => 1,
                ]);
            } else {
                $checkList = CheckList::create([
                    'name' => 'Form GM1- GMP checklist'.'_'.now(),
                    'site_id' => 1,
                    // 'entry_detail' => $data['entry_detail'],
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'person_name' => $data['person_name'],
                    'type_id' => 7,
                    'status' => 0,
                ]);
            }

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

            if (! $another) {
                $recipient = User::whereHas('sites', function ($query) {
                    $query->where('site_id', 1);
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
                        Action2::make('View and Approve')
                            ->button()
                            ->url('/gmp-site-ones/'.$entryId)
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
        //     // Ensure that the form record is anonymized so that relationships aren't loaded.
        //     $this->form->model($this->record::class);
        //     $this->record = null;
        //     $this->fillForm();

        //     return;
        // }

        // $this->redirect($this->getRedirectUrl());

        $this->redirect('/gmp-site-ones');

    }
}
