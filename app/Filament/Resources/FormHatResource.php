<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormHatResource\Pages;
use App\Models\FormHat;
use App\Models\Role;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class FormHatResource extends Resource
{
    protected static ?string $model = FormHat::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // protected static ?string $navigationGroup = 'QC Forms';
    protected static ?string $navigationGroup = 'Site 1263 Forms';

    protected static ?string $navigationLabel = 'HAT';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

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
                            ->rows(1),
                        // ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(['lg' => 4]),

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
                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('time')
                    ->searchable(),
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
            ->defaultGroup('date')
            ->striped()
            ->filters([
                //
            ])
            ->actions([

                Action::make('Download Report')->label('Download Report')
                    ->url(fn (FormHat $record): string => route('generate.hat', $record))
                    ->openUrlInNewTab()
                // ->visible(function (FormHat $record): bool {
                //     return ($record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || ($record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                // })
                    ->icon('heroicon-m-arrow-down-on-square'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
