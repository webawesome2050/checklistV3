<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreOpFormsResource\Pages;
use App\Filament\Resources\PreOpFormsResource\RelationManagers;
use App\Models\CheckList as PreOpForms;
 
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
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use App\Models\CheckListItemsEntry as Entries;

use Illuminate\Database\Eloquent\SoftDeletingScope;



class PreOpFormsResource extends Resource
{
    protected static ?string $model = PreOpForms::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Site 34 Forms';
    protected static ?string $navigationLabel = 'Pre-Op forms';

    protected static ?string $breadcrumb = 'Pre-Op forms ';

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
        $checklistItems = CheckListItem::where('check_list_id', 20)
        ->where('site_id', 1)
        ->get();
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
                    $itemCode = CheckboxList::make("sub_section_items_$subsectionId")
                        ->label('')
                        ->options($radioOptions);
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

                   if($sectionName == 'HIGH RISK AREA') {  
                    $subsectionName = $matchingItem->subSection->name;  
                    $subsectionSection = Section::make($subsectionName)
                    ->description($matchingItem->subSection->atp_frequency ? 'ATP check RLU Frequency => '.$matchingItem->subSection->atp_frequency : '' )
                    ->columns(4)
                    ->compact();
                } else {
                    $subsectionName = $matchingItem->subSection->name;  
                    $subsectionSection = Section::make($subsectionName)
                    // ->description($matchingItem->subSection->atp_frequency ? 'ATP check RLU Frequency => '.$matchingItem->subSection->atp_frequency : '' )
                    ->columns(4)
                    ->compact(); 
                }
                    
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
                   
                    // $description = $checklistItem->m_frequency ? 'Micro SPC Swab Frequency'.$checklistItem->m_frequency : '';
                    // $description.=$checklistItem->c_frequency ? '\n'.'Chemical Residue Check Frequency'.$checklistItem->c_frequency : '';
                    // $description.=$checklistItem->a_frequency ? '\n'.'ATP check RLU Frequency'.$checklistItem->a_frequency : '';

                    if($sectionName == 'HIGH RISK AREA') { 
                    $description = $checklistItem->m_frequency ? 'Micro SPC Swab =>'.$checklistItem->m_frequency : '';
                    $description.=$checklistItem->c_frequency ? ' ---- C. Frequency =>'.$checklistItem->c_frequency : '';
                    $description.=$checklistItem->a_frequency ? ' ---- ATP check RLU. Frequency =>'.$checklistItem->a_frequency : '';
                    } else {
                        $description = '';    
                    }
                    $stepFields[]   =  
                 Section::make($checklistItem->name)
                 ->description($description)
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
                        // ->label('ChemicalResidueCheck')->name('chemical_residue_check'),
                        $formFields[] =  Hidden::make("entry_id_$checklistItem->id"),
                        // $formFields[] = TextInput::make("chemical_residue_check_$checklistItem->id")->label('Chemical Residue Check')->name('chemical_residue_check'),
                       
                        $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                        ->rows(1),        
                        $formFields[] = Radio::make("action_taken_$checklistItem->id")
                        ->label('Action Taken')
                        ->options([
                            'Yes' => 'Yes',
                            'No' => 'No'
                        ])
                        ->inline(),                 
                    ])->columns(4)->compact(); 


                } 

                $subsectionSection->schema($stepFields); 
                $sectionComponents[] = $subsectionSection; 
            }
            $sectionStep = Tab::make($sectionName)->schema($sectionComponents);
            $wizardSteps[] = $sectionStep;
        }
        // $form->schema([
        //     Tabs::make('Label')->tabs($wizardSteps),
        // ])->columns(1);

        return $form
        ->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('person_name')
                    ->label('Person Name')
                    ->maxLength(255)
                    ->required(), 
                    Hidden::make('id'), 
                    DatePicker::make('date')
                    ->required()
                    ->native(false),
                    TimePicker::make('time')
                    ->required(),  
                    // DateTimePicker::make('entry_detail')
                    // ->label('Entry Date Detail')
                    // ->required()
                    // ->native(false),
                ])
               ->columns(3)
                ->columnSpan(['lg' =>5]),

            Forms\Components\Section::make()
                ->schema([
                    Tabs::make('Label')->tabs($wizardSteps)
                ])
                // ->columns(4)
                ->columnSpan(['lg' => 12]),
        ])
        ->columns(12); 
        

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
        ->modifyQueryUsing(fn (Builder $query) => $query->where('site_id', 1));
    }
 
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreOpForms::route('/'),
            'create' => Pages\CreatePreOpForms::route('/create'),
            'view' => Pages\ViewPreOpForms::route('/{record}'),
            'edit' => Pages\EditPreOpForms::route('/{record}/edit'),
        ];
    }    
}
