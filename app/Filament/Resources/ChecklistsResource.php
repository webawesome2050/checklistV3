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
use Filament\Resources\Resource;
use Filament\Forms\Components\Text;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Section;
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
    protected static ?string $navigationGroup = 'QC Forms';
    // protected static ?string $navigationLabel = 'Cleanliness Checklist';
    protected static ?string $navigationLabel = 'Hygiene swab and Pre';
    public static function form(Form $form): Form
    { 


        // $currentUrl = request()->url();
        // $parts = explode('/', $currentUrl);
        // $checkListId = end($parts);
        // $id = 2;
        // $id = Route::current()->parameter('record');

        $url = request()->url(); 
        preg_match('/\/checklists\/(\d+)\/edit/', $url, $matches);
        if (isset($matches[1])) {
            $id = $matches[1];
            session(['checklist_id' => $id]); 
        } else {
            $id = session('checklist_id');
        }
        
        // dd($id);
        // $currentUrl = $_SERVER['REQUEST_URI'];
            // $parts = explode('/', $currentUrl);
            // $editIndex = array_search('edit', $parts);

            // if ($editIndex !== false && $editIndex > 0) {
            //     $id = $parts[$editIndex - 1];
            //     // Now you have the extracted ID, which you can use as needed
            //     // For example, you can pass it to your Filament form section
            // }


        

        // $checklistItems = CheckListItem::all(); 
        // if(@$id) {
        //     $checklistItems = CheckListItem::where('check_list_id', $id)->get();
        // } else {
        //     $checklistItems = CheckListItem::where('check_list_id', 1)->get();
        // }


        $checklistItems = CheckListItem::where('check_list_id', 1)->get();

        if($id == 1 || $id == 10) {
            // dd($id);
        $checklistItemsBySectionAndSubsection = $checklistItems->groupBy(['section_id', 'sub_section_id']);
        foreach ($checklistItemsBySectionAndSubsection as $sectionId => $subsectionGroups) {
            $sectionName = $subsectionGroups->first()->first()->section->name;
            $itemCount = count($subsectionGroups);
           // $sectionName =   $sectionName."-".$itemCount;
            $sectionName =   $sectionName;

            $sectionComponents = [];
            $subsectionNameArray  = [];
            foreach ($subsectionGroups as $subsectionId => $checklistItemsInSubsection) {
                // dd( $subsectionId);
                $subsectionName = $checklistItemsInSubsection->first()->first()->subSection->name;
                // $formFields = [];
                // if (!in_array($subsectionName, $subsectionNameArray)) {
                //     $subsectionNameArray[] = $subsectionName;
                // }

                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                if ($matchingItem) {
                    $subsectionName = $matchingItem->subSection->name;  
                    $subsectionSection = Section::make($subsectionName)
                    // ->description('Step Description')
                    ->columns(4)
                    ->compact()
                    ->collapsed(); // Set the section to be collapsed by default
                    
                } else {
                    $subsectionSection = Section::make('Section')
                    // ->description('Step Description')
                    ->columns(4)
                    ->compact()
                    ->collapsed(); // Set the section to be collapsed by default
                }
                
                $formFields = [];
                $stepFields = [];
                foreach ($checklistItemsInSubsection as $checklistItem) {
                    $stepFields[]   =  
                 Section::make($checklistItem->name)
                //  ->aside()
                // ->description($checklistItem->is_approved ? '' : 'Pending') 
                ->schema([ 
                     $formFields[] = Select::make("visual_insp_allergen_free_{$checklistItem->id}")
                        ->label('Visual insp allergen free')
                        ->options([
                            'Accept' => 'Accept',
                            'Reject' => 'Reject',
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
            // Add the section components to the wizard steps
            $sectionStep = Wizard\Step::make($sectionName)->schema($sectionComponents);
            $wizardSteps[] = $sectionStep;
        }
        $form->schema([
            Wizard::make($wizardSteps)->skippable(),
        ])->columns(1);
        }
        else if($id == 2)
         { 
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

                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                if ($matchingItem) {
                    $subsectionName = $matchingItem->subSection->name;  
                    $subsectionSection = Section::make($subsectionName)
                    // ->description('Step Description')
                    ->columns(4)
                    ->compact()
                    ->collapsed(); // Set the section to be collapsed by default
                    
                } else {
                    $subsectionSection = Section::make('Section')
                    // ->description('Step Description')
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
                        ->label('Condition')
                        ->options([
                            'Accept' => 'Yes',
                            'Reject' => 'No',
                            'Not in Use' => 'Not in Use'
                        ]),
                        $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                        ->rows(2),  
                        $formFields[] = TextInput::make("micro_SPC_swab_$checklistItem->id")->label('Person Responsible')->name('micro_SPC_swab'),
                        // $formFields[] = TextInput::make("chemical_residue_check_$checklistItem->id")->label('Chemical Residue Check')->name('chemical_residue_check'),
                        // $formFields[] = Select::make("TP_check_RLU_$checklistItem->id")
                        //     ->label('ATP check RLU')
                        //     ->options([
                        //         'Pass' => 'Pass',
                        //         'Fail' => 'Fail'
                        //     ]),
                        $formFields[] = Select::make("action_taken_$checklistItem->id")
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
            $sectionStep = Wizard\Step::make($sectionName)->schema($sectionComponents);
            $wizardSteps[] = $sectionStep;
        }
        $form->schema([
            Wizard::make($wizardSteps)->skippable(),
        ])->columns(1);
         } 
        else {
            // dd($id); 
        } 

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
            ->filters([
                //
            ])
            ->actions([
                // auth()->user()->hasRole(Role::ROLES['approver']) ?  
                Tables\Actions\ViewAction::make()->label('View and Approve')
                ->hidden(!auth()->user()->hasRole(Role::ROLES['approver'])),
                 Tables\Actions\EditAction::make()
                 ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
                            'Reject' => 'Reject',
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
