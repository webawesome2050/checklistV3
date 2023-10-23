<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use App\Models\FormTv;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FormTvResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormTvResource\RelationManagers;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

class FormTvResource extends Resource
{
    protected static ?string $model = FormTv::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationGroup = 'QC Forms';
    protected static ?string $navigationGroup = 'Site 1263 Forms';

    protected static ?string $navigationLabel = 'TV - Storage Temp';


    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('name')->label('Person name')
                    ->maxLength(255)
                    ->required(),
                    DatePicker::make('date')
                    ->required()
                    ->native(false),
                    TimePicker::make('time')
                    ->required(),  
                Forms\Components\Textarea::make('notes')
                    ->label('Comments')
                    ->rows(1)
                    // ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpan(['lg' =>4]),

            Forms\Components\Section::make()
                ->schema([ 
                    // DatePicker::make('date'),
                    // TimePicker::make('time'), 
                    TextInput::make('temp_storage_1'),
                    TextInput::make('temp_storage_2'),
                    TextInput::make('temp_storage_3'),
                    TextInput::make('verified_by'),
                    // Toggle::make('is_verified')
                    // ->visible(auth()->user()->hasRole(Role::ROLES['approver']))
                ])
                ->columns(2)
                ->columnSpan(['lg' => 5]),
        ])
        ->columns(12); 

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
                TextInput::make('temp_storage_1'),
                TextInput::make('temp_storage_2'),
                TextInput::make('temp_storage_3'),
                Toggle::make('is_verified')
                ->visible(auth()->user()->hasRole(Role::ROLES['approver'])), 
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
                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('time')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Person Name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
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
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->striped()
            ->filters([
                //
            ])
            ->actions([ 
                    // Tables\Actions\EditAction::make()->label('View and Approve')
                    // ->hidden(!auth()->user()->hasRole(Role::ROLES['approver'])),
                    //  Tables\Actions\EditAction::make()
                    //  ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])), 

                    Tables\Actions\ViewAction::make()->label('View and Approve')
                    ->visible(function (FormTv $record): bool {
                        return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                    }),
                    Tables\Actions\EditAction::make()
                    ->visible(function (FormTv $record): bool {
                        return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                    }),
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
