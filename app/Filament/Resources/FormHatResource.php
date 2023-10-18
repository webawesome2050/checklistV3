<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use App\Models\FormHat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FormHatResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use App\Filament\Resources\FormHatResource\RelationManagers;

class FormHatResource extends Resource
{
    protected static ?string $model = FormHat::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // protected static ?string $navigationGroup = 'QC Forms';
    protected static ?string $navigationGroup = 'Forms';
    protected static ?string $navigationLabel = 'HAT';

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
                    TextInput::make('room_temperature'),
                    TextInput::make('air_flow_rate'),
                    TextInput::make('room_pressure'),
                    TextInput::make('verified_by'),
                    // Toggle::make('is_verified')
                    // ->visible(auth()->user()->hasRole(Role::ROLES['approver']))
                ])
                ->columns(2)
                ->columnSpan(['lg' => 5]),
        ])
        ->columns(12); 

        // return $form
        //     ->schema([
        //         Forms\Components\TextInput::make('name')
        //             ->maxLength(255), 
        //         DatePicker::make('date')
        //         ->required()
        //         ->native(false),
        //         TimePicker::make('time')
        //         ->required(),  
        //         Forms\Components\Textarea::make('comments')
        //             ->maxLength(65535),
        //             TableRepeater::make('hat_lines')
        //     ->relationship()
        //     ->schema([
        //         DatePicker::make('date'),
        //         TimePicker::make('time'), 
        //         TextInput::make('room_temperature'),
        //         TextInput::make('air_flow_rate'),
        //         TextInput::make('room_pressure'),
        //         Toggle::make('is_verified')
        //         ->hidden(auth()->user()->hasRole(Role::ROLES['Checker'])), 
        //     ])
        //     ->label('New Entry')
        //     ->columnSpan('full')
        //     ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('version')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('issues_by')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('room_temperature')
                ->label('Room Temperature'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\IconColumn::make('is_approved')
                //     ->boolean(),
            ])
            ->striped()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
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
            'index' => Pages\ListFormHats::route('/'),
            'create' => Pages\CreateFormHat::route('/create'),
            'edit' => Pages\EditFormHat::route('/{record}/edit'),
        ];
    }    
}
