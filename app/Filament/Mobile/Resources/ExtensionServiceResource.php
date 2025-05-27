<?php

namespace App\Filament\Mobile\Resources;

use App\Filament\Mobile\Resources\ExtensionServiceResource\Pages;
use App\Filament\Mobile\Resources\ExtensionServiceResource\RelationManagers;
use App\Models\ExtensionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExtensionServiceResource extends Resource
{
    protected static ?string $model = ExtensionService::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->placeholder('Enter the name of the service')
                    ->helperText('Please use a good descriptive name like agro advisory services.')
                    ->maxLength(191),
                Forms\Components\Textarea::make('description')
                    ->placeholder('Provide a good description of the service here.')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('icon')
                    ->required()
                    ->maxLength(191)
                    ->default('help-circle'),
                Forms\Components\TextInput::make('color')
                    ->required()
                    ->maxLength(191)
                    ->default('#4CAF50'),

                Forms\Components\Select::make('status')
                    ->label('Service')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Not Active',

                    ])
                    ->default('active')
                    ->required(),

                Forms\Components\Select::make('assigned_worker_id')
                    ->label('Assigned Worker')
                    ->relationship('assignedWorker', 'name') // Ensure 'assignedWorker' is the correct relation
                    ->options(
                        \App\Models\User::whereHas('roles', function ($query) {
                            $query->where('name', 'extension officer');
                        })->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload(), // Preloads options to improve UX

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedWorker.name')
                    ->label('Assigned Worker')
                    ->searchable()
                    ->placeholder('No worker assigned') // Displays when null
                    ->formatStateUsing(fn($state) => $state ?? 'No worker assigned'),

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
            'index' => Pages\ListExtensionServices::route('/'),
            'create' => Pages\CreateExtensionService::route('/create'),
            'view' => Pages\ViewExtensionService::route('/{record}'),
            'edit' => Pages\EditExtensionService::route('/{record}/edit'),
        ];
    }
}
