<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use App\Models\FormTv;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FormTvResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormTvResource\RelationManagers;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class FormTvResource extends Resource
{
    protected static ?string $model = FormTv::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'QC Forms';

    protected static ?string $navigationLabel = 'TV';


    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('version')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('issues_by')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\RichEditor::make('notes')
                    ->maxLength(65535)
                    ->required()
                    ->columnSpanFull(),
            TableRepeater::make('tv_lines')
            ->relationship()
            ->schema([
                DatePicker::make('date'),
                TimePicker::make('time'), 
                TextInput::make('temp_storage_1'),
                TextInput::make('temp_storage_2'),
                TextInput::make('temp_storage_3'),
                // TextInput::make('is_approved'),
                // TextInput::make('is_verified'),
                // TextInput::make('is_verified'),
            ])
            ->label('Temperature Daily Verification')
            ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {

        //   dd(auth()->user()->hasRole(Role::ROLES['approver']));


        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('issues_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('')
                ->label('Status')
                ->description(function (FormTv $record) {
                    $res= $record->is_approved == false ;
                    if ($res){
                        return "Pending";
                    }
                    else{
                        return "Approved";
                    }
                }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([ 
                    Tables\Actions\ViewAction::make()->label('View and Approve')
                    ->hidden(!auth()->user()->hasRole(Role::ROLES['approver'])),
                     Tables\Actions\EditAction::make()
                     ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])), 
            ])

            // ->actions([
            //     Tables\Actions\ViewAction::make()->label('View and Approve')
            //     ->hidden(function (FormTv $record) {
            //         return $record->is_approved == true && auth()->user()->hasRole(Role::ROLES['approver']);
            //     }),
            //          Tables\Actions\EditAction::make()
            //          ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
            // ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('New Temperature Verification'),
            ]);
    }
    


    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             \Filament\Infolists\Components\Section::make()
    //                 ->schema([
    //                     \Filament\Infolists\Components\Split::make([
    //                         \Filament\Infolists\Components\Grid::make(3)
    //                             ->schema([
    //                                 \Filament\Infolists\Components\Group::make([
    //                                     \Filament\Infolists\Components\TextEntry::make('name'),
                                      
    //                                 ]),
    //                                 \Filament\Infolists\Components\TextEntry::make('version'),
    //                                 \Filament\Infolists\Components\TextEntry::make('issued_by')
    //                                     ->badge()
    //                                     ->date()
    //                                     ->color('success'), 
    //                             ]), 
    //                     ])->from('lg'),
    //                 ]),
    //             \Filament\Infolists\Components\Section::make('Content')
    //                 ->schema([
    //                     \Filament\Infolists\Components\TextEntry::make('content')
    //                         ->prose()
    //                         ->markdown()
    //                         ->hiddenLabel(),
    //                 ])
    //                 ->collapsible(),
    //         ]);
    // }

    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormTvs::route('/'),
            'create' => Pages\CreateFormTv::route('/create'),
            'edit' => Pages\EditFormTv::route('/{record}/edit'),
            'view' => Pages\ViewFormTvs::route('/{record}'),
        ];
    }    
}
