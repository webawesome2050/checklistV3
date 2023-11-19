<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QcFormTypeTwoResource\Pages;
use App\Models\CheckList;
use App\Models\CheckList as QcFormTypeTwo;
use App\Models\CheckListItem;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

// use Filament\Infolists\Components\Tabs;

class QcFormTypeTwoResource extends Resource
{
    protected static ?string $model = QcFormTypeTwo::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationGroup = 'QC Forms';
    protected static ?string $navigationGroup = 'Site 1263 Forms';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'GMP';

    protected static ?string $breadcrumb = 'GMP';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('type_id', 2)->count();
    }

    public static function form(Form $form): Form
    {

        $url = request()->url();
        preg_match('/\/checklists\/(\d+)\/edit/', $url, $matches);
        if (isset($matches[1])) {
            $id = $matches[1];
            session(['checklist_id' => $id]);
        } else {
            $id = session('checklist_id');
        }

        $checklistItems = CheckListItem::where('check_list_id', 2)->get();

        $checklistItemsBySectionAndSubsection = $checklistItems->groupBy(['section_id', 'sub_section_id']);
        foreach ($checklistItemsBySectionAndSubsection as $sectionId => $subsectionGroups) {
            $sectionName = $subsectionGroups->first()->first()->section->name;
            $itemCount = count($subsectionGroups);
            $sectionName = $sectionName;

            $sectionComponents = [];
            $subsectionNameArray = [];
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
                        ->extraAttributes([
                            'class' => 'section-portion cursor-pointer',
                        ])
                        ->columns(4)
                        ->compact()
                        ->collapsible(); // Set the section to be collapsed by default

                } else {
                    $subsectionSection = Section::make($sectionName)
                        ->extraAttributes([
                            'class' => 'section-portion cursor-pointer',
                        ])
                    // ->description('Step Description')
                        ->columns(4)
                        ->collapsible()
                        ->compact();
                    // ->collapsed(); // Set the section to be collapsed by default
                }

                $formFields = [];
                $stepFields = [];
                foreach ($checklistItemsInSubsection as $checklistItem) {

                    $description = $checklistItem->m_frequency ? 'M Frequency =>'.$checklistItem->m_frequency : '';
                    $description .= $checklistItem->c_frequency ? ' ---- C. Frequency =>'.$checklistItem->c_frequency : '';
                    $description .= $checklistItem->a_frequency ? ' ---- A. Frequency =>'.$checklistItem->a_frequency : '';

                    $stepFields[] =
                 Section::make($checklistItem->name)
                     ->aside()
                //  ->description($description)
                // ->description($checklistItem->is_approved ? '' : 'Pending')
                     ->schema([
                         $formFields[] = Select::make("visual_insp_allergen_free_{$checklistItem->id}")
                             ->label('Condition')
                             ->options([
                                 'Accept' => 'Accepted',
                                 'Accepted after Corrective Actions' => 'Accepted after Corrective Actions',
                             ])
                             ->native(false),
                         $formFields[] = Hidden::make("entry_id_$checklistItem->id"),
                         $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                             ->rows(1),
                         // $formFields[] = TextInput::make("micro_SPC_swab_$checklistItem->id")->label('Person Responsible')->name('micro_SPC_swab'),
                         // $formFields[] = TextInput::make("chemical_residue_check_$checklistItem->id")->label('Chemical Residue Check')->name('chemical_residue_check'),
                         // $formFields[] = Select::make("TP_check_RLU_$checklistItem->id")
                         //     ->label('ATP check RLU')
                         //     ->options([
                         //         'Pass' => 'Pass',
                         //         'Fail' => 'Fail'
                         //     ]),
                         // $formFields[] = Select::make("action_taken_$checklistItem->id")
                         //     ->label('Action Taken')
                         //     ->options([
                         //         'Yes' => 'Yes',
                         //         'No' => 'No'
                         //     ]),

                         $formFields[] = Radio::make("action_taken_$checklistItem->id")
                             ->label('Action Taken')
                             ->inline()
                             ->options([
                                 'Yes' => 'Yes',
                                 'No' => 'No',
                             ]),
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
                            ->label('Start Time')
                            ->required(),
                        TimePicker::make('finish_time')
                            ->label('End Time'),
                        Forms\Components\TextInput::make('inspected_by')
                            ->label('Verified By'),
                        // DateTimePicker::make('entry_detail')
                        // ->label('Entry Date Detail')
                        // ->required()
                        // ->native(false),
                    ])
                    ->columns(3)
                    ->columnSpan(['lg' => 5]),

                Forms\Components\Section::make()
                    ->schema([
                        Tabs::make('Label')->tabs($wizardSteps),
                    ])
                    // ->columns(4)
                    ->columnSpan(['lg' => 12]),
            ])
            ->columns(12);

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Hidden::make('id'),
                        DateTimePicker::make('entry_detail')
                            ->label('Entry Detail')
                            ->native(false),
                        // DateTimePicker::make('next_inspection_detail')
                        // ->label('Next Inspectin Date')
                        // ->native(false),
                    ])
                    ->columnSpan(['lg' => 3]),

                Forms\Components\Section::make()
                    ->schema([
                        Tabs::make('Label')->tabs($wizardSteps),

                    ])
                    ->columnSpan(['lg' => 9]),
            ])
            ->columns(3);

        // return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                // TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created on')->searchable(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
                TextColumn::make('')
                    ->label('Approval Status')
                    ->description(function (CheckList $record) {
                        $res = $record->is_approved == false;
                        if ($res) {
                            return 'Pending';
                        } else {
                            return 'Approved';
                        }
                    }),

                TextColumn::make('status1')
                    ->label('Submission Status')
                    ->badge()
                    ->description(function (CheckList $record) {
                        $res = $record->status == false;
                        if ($res) {
                            return 'In Progress';
                        } else {
                            return 'Submitted';
                        }
                    }),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->label('Created on')
                //     ->sortable(),
            ])
            ->striped()
            ->filters([
                //
            ])
            // ->actions([
            //     Tables\Actions\ViewAction::make()->label('View and Approve')
            //         ->hidden(!auth()->user()->hasRole(Role::ROLES['approver'])),
            //          Tables\Actions\EditAction::make()
            //          ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
            // ])

            ->actions([

                Action::make('Download Report')->label('Download Report')
                    ->url(fn (CheckList $record): string => route('generate.gmp', $record))
                    ->openUrlInNewTab()
                    ->visible(function (CheckList $record): bool {
                        return ($record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || ($record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                    })
                    ->icon('heroicon-m-arrow-down-on-square'),

                // Tables\Actions\ViewAction::make()->label('View and Approve')
                //     ->visible(function (CheckList $record): bool {
                //         return (! $record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (! $record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                //     }),
                // Tables\Actions\EditAction::make()
                //     ->visible(function (CheckList $record): bool {
                //         return (! $record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (! $record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                //     }),

                Tables\Actions\ViewAction::make()->label('View and Approve')
                    ->visible(function (CheckList $record): bool {
                        return ($record->status && ! $record->is_approved) && (auth()->user()->hasRole(Role::ROLES['approver']) || auth()->user()->hasRole(Role::ROLES['admin']));
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(function (CheckList $record): bool {
                        return (! $record->status && ! $record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (! $record->status && ! $record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                    }),

            ])

            // ->actions([
            //     Tables\Actions\ViewAction::make()->label('View and Approve')
            //     ->hidden(function (QcFormTypeTwo $record) {
            //         return $record->is_approved == true && auth()->user()->hasRole(Role::ROLES['approver']);
            //     }),
            //          Tables\Actions\EditAction::make()
            //          ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
            // ])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]
            )
            ->modifyQueryUsing(fn (Builder $query) => $query->where('type_id', 2));
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
            'index' => Pages\ListQcFormTypeTwos::route('/'),
            'create' => Pages\CreateQcFormTypeTwo::route('/create'),
            'edit' => Pages\EditQcFormTypeTwo::route('/{record}/edit'),
            'view' => Pages\ViewQcFormTypeTwo::route('/{record}'),
        ];
    }
}
