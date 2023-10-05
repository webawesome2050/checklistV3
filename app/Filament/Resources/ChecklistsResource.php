<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\CheckList;
use Filament\Tables\Table;
use App\Models\CheckListItem;
use App\Models\EntriesMaster;
use App\Models\SubSectionItem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Text;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CheckListItemsEntry as Entries;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ChecklistsResource\Pages;
use App\Filament\Resources\ChecklistsResource\RelationManagers;
use App\Filament\Resources\ChecklistsResource\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\ChecklistsResource\RelationManagers\TemperatureChecklistRelationManager;


class ChecklistsResource extends Resource
{
    protected static ?string $model = CheckList::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // protected static ?string $title = 'QC Forms';
    protected static ?string $navigationGroup = 'Site 1263';
    // protected static ?string $navigationLabel = 'Cleanliness Checklist';
    protected static ?string $navigationLabel = 'Hygiene swab and Pre';


    protected static ?string $breadcrumb = 'Hygiene swab';


    public static function form(Form $form): Form
    { 
 
       
        $optionsValue = [];
        for ($i = 0; $i <= 60; $i++) {
            $optionsValue[$i] = $i;
        }

        $url = request()->url(); 
        preg_match('/\/checklists\/(\d+)\/edit/', $url, $matches);
        if (isset($matches[1])) {
            $id = $matches[1];
            session(['checklist_id' => $id]); 
        } else {
            $id = session('checklist_id');
        } 

        
         
        $checklistItems = CheckListItem::where('check_list_id', 1)->get();
        $checklistItemsBySectionAndSubsection = $checklistItems->groupBy(['section_id', 'sub_section_id']);

        foreach ($checklistItemsBySectionAndSubsection as $sectionId => $subsectionGroups) {
            $sectionName = $subsectionGroups->first()->first()->section->name;
            $itemCount = count($subsectionGroups);
           // $sectionName =   $sectionName."-".$itemCount;
            $sectionName =   $sectionName;

            $sectionComponents = [];
            $subsectionNameArray  = [];
            foreach ($subsectionGroups as $subsectionId => $checklistItemsInSubsection) {
                $subsectionName = $checklistItemsInSubsection->first()->first()->subSection->name;
                // Retrieve sub-section items for the current sub-section
                $subSectionItems = SubSectionItem::where('sub_section_id', $subsectionId)->get();
                $formFields = [];
                // Check if there are sub-section items
                if ($subSectionItems->count() > 0) {
                    // There are sub-section items, so add radio buttons
                    $radioOptions = [];
                    foreach ($subSectionItems as $subSectionItem) {
                        // Add each sub-section item as an option for the radio button
                        // $radioOptions["sub_section_item_{$subSectionItem->id}"] = $subSectionItem->name;
                        $radioOptions[$subSectionItem->name] = $subSectionItem->name;
                    }
                    // \Log::info($radioOptions);
                    $itemCode = Radio::make("sub_section_items_$subsectionId")
                        ->label('')
                        ->options($radioOptions)
                        ->inline();
                } else {
                    $formFields = [];
                }
                

                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });
                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                if ($matchingItem) {
                    $subsectionName = $matchingItem->subSection->name;  
                    $subsectionSection = Section::make($subsectionName)
                    ->columns(4)
                    ->compact();
                    
                } else {
                    $subsectionSection = Section::make('Section')
                    ->columns(4)
                    ->compact()
                    ->collapsed(); // Set the section to be collapsed by default
                }
                if(count($subSectionItems) > 0) {
                    // \Log::info($formFields);
                } 
                // $formFields = [];
                $stepFields = [];
                if(count($subSectionItems) > 0) {
                    $stepFields[]   =  
                    Section::make('Select machine number')
                    ->icon('heroicon-m-megaphone')
                    // ->aside()
                   ->schema([ 
                    $formFields[] = $itemCode
                    ]);
                }

                foreach ($checklistItemsInSubsection as $checklistItem) {
                   
                    $stepFields[]   =  
                 Section::make($checklistItem->name)
                 ->aside()
                ->schema([ 
                     $formFields[] = Select::make("visual_insp_allergen_free_{$checklistItem->id}")
                        ->label('Visual insp allergen free')
                        ->options([
                            'Accept' => 'Accept',
                            'Accepted after Corrective Actions' => 'Accepted after Corrective Actions',
                            'Not in Use' => 'Not in Use'
                        ])
                        ->native(false),

                        
                        $formFields[] =  Select::make("ATP_check_RLU_{$checklistItem->id}")
                        ->label('ATP')
                        ->options($optionsValue)
                        ->native(false)
                        ->visible($sectionName == 'HIGH RISK AREA'),
                        // $formFields[] = TextInput::hidden()
                        // ->label('Chemical Residue Check')->name('chemical_residue_check'),
                        $formFields[] =  Hidden::make("entry_id_$checklistItem->id"),
                        $formFields[] = TextInput::make("chemical_residue_check_$checklistItem->id")->label('Chemical Residue Check')->name('chemical_residue_check'),
                       
                        $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                        ->rows(1),        
                        $formFields[] = Radio::make("action_taken_$checklistItem->id")
                        ->label('Action Taken')
                        ->options([
                            'Yes' => 'Yes',
                            'No' => 'No'
                        ]),                 
                    ])->columns(4)->compact(); 


                } 

                $subsectionSection->schema($stepFields); 
                $sectionComponents[] = $subsectionSection; 
            }
            $sectionStep = Tab::make($sectionName)->schema($sectionComponents);
            $wizardSteps[] = $sectionStep;
        }
        $form->schema([
            Tabs::make('Label')->tabs($wizardSteps),
        ])->columns(1);

        return $form;
    }
 

    public static function table(Table $table): Table
    { 
        // dd(Table::when('entry_id', 2));
        return $table
            ->columns([
                //
                // TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('created_at')
                ->dateTime()
                ->label('Created on'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
                TextColumn::make('')
                ->label('Status')
                ->description(function (CheckList $record) {
                    $res= $record->is_approved == false ;
                    if ($res){
                        return "Pending";
                    }
                    else{
                        return "Approved";
                    }
                }),
                TextColumn::make('site.name')
                // TextColumn::make('checklist.name'),
            ])
            ->striped()
            ->filters([
                //
            ])
            ->actions([

                Action::make('Download Report')->label('Download Report')
                ->url(fn (CheckList $record): string => route('generate.pdf', $record))
                ->openUrlInNewTab()
                ->visible(function (CheckList $record): bool {
                    return ($record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || ($record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                })
                ->icon('heroicon-m-arrow-down-on-square'),
                // ->action(fn (CheckList $record) => $record->delete()),
                

                // auth()->user()->hasRole(Role::ROLES['approver']) ?   
                // Tables\Actions\ViewAction::make()->label('View and Approve')
                // ->visible(auth()->user()->hasRole(Role::ROLES['admin'])),
                
                Tables\Actions\ViewAction::make()->label('View and Approve')
                // ->visible((fn (CheckList $record): bool => !$record->is_approved)),
                // ->visible((fn (CheckList $record): bool => !$record->is_approved) && (auth()->user()->hasRole(Role::ROLES['approver']))),
                ->visible(function (CheckList $record): bool {
                    return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                }),
                Tables\Actions\EditAction::make()
                ->visible(function (CheckList $record): bool {
                    return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                }),
                //  ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]
        )
        ->modifyQueryUsing(fn (Builder $query) => $query->where('type_id', 1));
    }








    public static function formOLD(Form $form): Form
    { 

        $checklistItems = CheckListItem::where('check_list_id', 2)->get();

        $checklistItemsBySectionAndSubsection = $checklistItems->groupBy(['section_id', 'sub_section_id']);
        foreach ($checklistItemsBySectionAndSubsection as $sectionId => $subsectionGroups) {
            $sectionName = $subsectionGroups->first()->first()->section->name;
            $itemCount = count($subsectionGroups);
            $sectionName =   $sectionName;

            $sectionComponents = [];
            $subsectionNameArray  = [];
            foreach ($subsectionGroups as $subsectionId => $checklistItemsInSubsection) {
                $subsectionName = $checklistItemsInSubsection->first()->first()->subSection->name;
                 
                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                if ($matchingItem) {
                    $subsectionName = $matchingItem->subSection->name;
                    $subsectionSection = Section::make($subsectionName)
                    ->columns(4)
                    ->compact()
                    ->collapsed(); // Set the section to be collapsed by default
                }
                
                $formFields = [];
                $stepFields = [];
                foreach ($checklistItemsInSubsection as $checklistItem) {
                    $stepFields[]   =  
                 Section::make($checklistItem->name)
                 ->aside()
                // ->description($checklistItem->is_approved ? '' : 'Pending') 
                ->schema([ 
                     $formFields[] = Select::make("visual_insp_allergen_free_{$checklistItem->id}")
                        ->label('Visual insp allergen free')
                        ->options([
                            'Accept' => 'Accept',
                            'Accepted after Corrective Actions' => 'Accepted after Corrective Actions',
                            'Not in Use' => 'Not in Use'
                        ]),
                        
                        $formFields[] = TextInput::make("micro_SPC_swab_$checklistItem->id")->label('Micro SPC Swab *')->name('micro_SPC_swab'),
                        $formFields[] = TextInput::make("chemical_residue_check_$checklistItem->id")->label('Chemical Residue Check')->name('chemical_residue_check'),
                        $formFields[] = Select::make("TP_check_RLU_$checklistItem->id")
                            ->label('ATP check RLU')
                            ->options([
                                'Pass' => 'Pass',
                                'Fail' => 'Fail'
                            ]),
                        $formFields[] = Select::make("action_taken_$checklistItem->id")
                            ->label('Action Taken')
                            ->options([
                                'Yes' => 'Yes',
                                'No' => 'No'
                            ]),
                        $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                        ->rows(2),                         
                    ])->columns(4)->compact(); 
                } 

                $subsectionSection->schema($stepFields); 
                $sectionComponents[] = $subsectionSection; 
            }
            $sectionStep = Wizard\Step::make($sectionName)->schema($sectionComponents);
            $wizardSteps[] = $sectionStep;
        }
        $form->schema([
            Wizard::make($wizardSteps)->skippable(),
        ])->columns(1);
        return $form;
    }











    public static function getRelations(): array
    {

        // $id = Route::current()->parameter('record');
        // dd($id);

        // $id = Route::current()->parameter('record');
        // dd($id);
        return [
            // TemperatureChecklistRelationManager::class,
            // DetailsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklists::route('/'),
            'create' => Pages\CreateChecklists::route('/create'),
            'edit' => Pages\EditChecklists::route('/{record}/edit'),
            'view' => Pages\ViewChecklists::route('/{record}'),
            // 'page' => Pages\ListForms::route('/page'),
            'page' => Pages\ListForms::route('/{record}/page'),
        ];
    }
}
