<?php

namespace App\Filament\Resources\EntriesMasterResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ChecklistsRelationManager extends RelationManager
{
    protected static string $relationship = 'checklists';

    protected static ?string $title = 'QC Form Approval';


    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('name')
                //     ->required()
                //     ->maxLength(255),


                    //    Select::make("visual_insp_allergen_free")
                    //         ->label("Visual Insp. Allergen free")
                    //         ->options([
                    //             'A' => 'Accept',
                    //             'R' => 'Reject',
                    //             'NA' => 'Not in Use'
                    //         ]),

                    //         Select::make("action_taken")
                    //         ->label('Action Taken')
                    //         ->options([
                    //             'Yes' => 'Yes',
                    //             'no' => 'No'
                    //         ]),

                    //     TextInput::make("micro_SPC_swab")->label('Micro SPC Swab')->name('micro_SPC_swab'),
                    //     TextInput::make("chemical_residue_check")->label('Chemical Residue Check')->name('chemical_residue_check'),
                    //     // TextInput::make("TP_check_RLU")->label('ATP check RLU Pass/Fail')->name('TP_check_RLU'),
                    //     // TextInput::make("action_taken")->label('Action Taken (Yes/ No)')->name('action_taken'),
                    //     Select::make("TP_check_RLU")
                    //     ->label('ATP check RLU')
                    //         ->options([
                    //             'Pass' => 'Pass',
                    //             'Fail' => 'Fail'
                    //         ]),
                    //     Select::make("action_taken")
                    //     ->label('Action Taken')
                    //         ->options([
                    //             'Yes' => 'Yes',
                    //             'No' => 'No'
                    //         ]),

                            Select::make("is_approved")
                            ->label('Approve')
                                ->options([
                                    'Approved' => 'Approved',
                                    'Pending' => 'Pending'
                                ]),
                        // Textarea::make("comments_corrective_actions")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                        // ->rows(2), 
            ]);
    }

    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('checkListItem.name'),
                //->label("Visual Insp. Allergen free"),
                Tables\Columns\TextColumn::make('visual_insp_allergen_free'),
                // Tables\Columns\TextColumn::make('micro_SPC_swab'),
                // Tables\Columns\TextColumn::make('chemical_residue_check'),
                // Tables\Columns\TextColumn::make('TP_check_RLU'),
                // Tables\Columns\TextColumn::make('comments_corrective_actions'),
                Tables\Columns\TextColumn::make('action_taken'),
                Tables\Columns\TextColumn::make('is_approved')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
