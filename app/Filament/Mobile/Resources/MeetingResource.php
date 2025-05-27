<?php

namespace App\Filament\Mobile\Resources;

use App\Filament\Mobile\Resources\MeetingResource\Pages;
use App\Filament\Mobile\Resources\MeetingResource\RelationManagers;
use App\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Basic Information
                Forms\Components\Section::make('Meeting Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(191)
                            ->placeholder('Enter meeting title')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'E.g., Annual Conference 2023' : null),

                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->placeholder('Describe the meeting agenda')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'Max 500 characters' : null)
                            ->maxLength(500),
                    ])
                    ->columns(2),

                // Section 2: Logistics
                Forms\Components\Section::make('Logistics')
                    ->schema([
                        Forms\Components\TextInput::make('venue')
                            ->required()
                            ->maxLength(191)
                            ->placeholder('Enter venue address')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'Include building and room number if applicable' : null),

                        Forms\Components\TextInput::make('presenter')
                            ->required()
                            ->maxLength(191)
                            ->placeholder('Main speaker/organizer name')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'Primary contact for this meeting' : null),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->native(false)
                            ->placeholder('Select date')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'When will the meeting occur?' : null),

                        Forms\Components\DateTimePicker::make('time')
                            ->label('Start Time')
                            ->required()
                            ->withoutSeconds()
                            ->native(false)
                            ->placeholder('Select start time')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? '24-hour format' : null)
                            ->format('H:i'),
                    ])
                    ->columns(2),

                // Section 3: Financials & Capacity
                Forms\Components\Section::make('Attendance Information')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Attendance Fee')
                            ->required()
                            ->numeric()
                            ->default(0.00)
                            ->prefix('UGX')
                            ->placeholder('0.00')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'Set to 0 for free events' : null),

                        Forms\Components\TextInput::make('capacity')
                            ->numeric()
                            ->placeholder('Unlimited if empty')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'Maximum number of attendees' : null),

                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'upcoming' => 'Upcoming',
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->native(false)
                            ->placeholder('Select status')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'Published events are visible to users' : null),
                    ])
                    ->columns(3),

                // Section 4: Relationship
                Forms\Components\Section::make('Related Service')
                    ->schema([
                        Forms\Components\Select::make('extension_service_id')
                            ->label('Parent Service')
                            ->relationship('extensionService', 'name')
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->placeholder('Select associated service')
                            ->helperText(fn() => $form->getOperation() !== 'view' ? 'Which service does this meeting belong to?' : null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Attendance Fee')
                    ->prefix('Ugx ')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue')
                    ->searchable(),
                Tables\Columns\TextColumn::make('presenter')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\TextColumn::make('extensionService.name')
                    ->label('Parent Service')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'view' => Pages\ViewMeeting::route('/{record}'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
        ];
    }
}
