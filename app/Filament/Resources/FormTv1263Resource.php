<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormTv1263Resource\Pages;
use App\Models\FormTvSite2 as FormTv;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class FormTv1263Resource extends Resource
{
    protected static ?string $model = FormTv::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Site 1263 Forms';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'TV Chiller form ';

    protected static ?string $breadcrumb = 'Site 1263 - TV';

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
                            ->maxLength(123),
                        // ->columnSpanFull(),
                    ])
                    ->columnSpan(['lg' => 3]),

                Forms\Components\Section::make()
                    ->schema([
                        TextInput::make('base_area_f1')->label('Base Area Finished Goods Freezer 1 & Freezer 2 new  F1'),
                        TextInput::make('base_area_f2')->label('Base Area Finished Goods Freezer 1 & Freezer 2 new  F2'),
                        TextInput::make('base_area_cool_room')->label('Base Area Main Cool Room'),
                        TextInput::make('base_area_raw_chiller')->label('Base Area Raw Material Chiller'),

                        TextInput::make('medium_area_cool_freezer')->label('Medium Area WIP Freezer'),
                        TextInput::make('medium_area_cool_chiller1')->label('Medium Area Blast Chiller 1'),
                        TextInput::make('medium_area_cool_chiller2')->label('Medium Area Blast Chiller 2'),
                        TextInput::make('medium_area_cool_cooked_wip_chiller')->label('Medium Area Cooked WIP Chiller'),
                        TextInput::make('medium_area_cool_wip_chiller')->label('Medium Area Raw WIP Chiller'),

                        TextInput::make('high_area_cool_freezer')->label('High Risk Area- Blast Freezer'),
                        TextInput::make('high_area_cool_chiller')->label('High Risk Area- Chiller'),

                        TextInput::make('outer_cartooning_room')->label('Outer cartooning room'),
                        TextInput::make('factory_lunch_room_fridge')->label('Factory Lunch Room Fridge'),
                        TextInput::make('office_staff_lunch_room_fridge')->label('Office Staff Lunch Room Fridge'),

                        TextInput::make('verified_by')->label('Verified By'),
                        // Toggle::make('is_verified')
                        // ->visible(auth()->user()->hasRole(Role::ROLES['approver'])),
                    ])
                    ->columns(4)
                    ->columnSpan(['lg' => 9]),
            ])
            ->columns(12);

    }

    public static function table(Table $table): Table
    {

        //   dd(auth()->user()->hasRole(Role::ROLES['approver']));

        return $table
            ->columns([

                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('time')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                // Tables\Columns\IconColumn::make('is_approved')
                //     ->boolean(),
                //     Tables\Columns\TextColumn::make('')
                //     ->label('Status')
                //     ->description(function (FormTv $record) {
                //         $res= $record->is_approved == false ;
                //         if ($res){
                //             return "Pending";
                //         }
                //         else{
                //             return "Approved";
                //         }
                //     })
                // Tables\Columns\TextColumn::make('issues_by')
                //     ->searchable(),
                //     Tables\Columns\IconColumn::make('is_approved')
                //     ->boolean(),

                // Tables\Columns\TextColumn::make('')
                // ->label('Status')
                // ->description(function (FormTv $record) {
                //     $res= $record->is_approved == false ;
                //     if ($res){
                //         return "Pending";
                //     }
                //     else{
                //         return "Approved";
                //     }
                // }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('date')
            ->striped()
            ->filters([
                //
            ])
            ->actions([

                Action::make('Download Report')->label('Download Report')
                    ->url(fn (FormTv $record): string => route('generate.tvc', $record))
                    ->openUrlInNewTab()
                // ->visible(function (FormTv $record): bool {
                //     return ($record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || ($record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                // })
                    ->icon('heroicon-m-arrow-down-on-square'),
                // Tables\Actions\ViewAction::make()->label('View and Approve')
                // ->visible(function (FormTv $record): bool {
                //     return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                // }),
                Tables\Actions\EditAction::make(),
                // ->visible(function (FormTv $record): bool {
                //     return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                // })

                // Tables\Actions\EditAction::make()->label('View and Approve')
                // ->hidden(!auth()->user()->hasRole(Role::ROLES['approver'])),
                //  Tables\Actions\EditAction::make()
                //  ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
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
                Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make()->label('New Temperature Verification'),
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
            'index' => Pages\ListFormTv1263s::route('/'),
            'create' => Pages\CreateFormTv1263::route('/create'),
            'edit' => Pages\EditFormTv1263::route('/{record}/edit'),
            'view' => Pages\ViewFormTv1263::route('/{record}'),
        ];
    }
}
