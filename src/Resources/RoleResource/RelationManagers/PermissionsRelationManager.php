<?php

namespace FilamentPackages\FilamentPermission\Resources\RoleResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Filament\Resources\RelationManagers\BelongsToManyRelationManager;

class PermissionsRelationManager extends BelongsToManyRelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return __('filament-permission::resources.Permissions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament-permission::permissions.name'))
                    ->unique(column: 'name')
                    ->required()
                    ->maxValue(255),

                Forms\Components\Select::make('guard_name')
                    ->label(__('filament-permission::permissions.guard_name'))
                    ->options(self::guards())
                    ->rules([Rule::in(self::guards())])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-permission::permissions.name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('guard_name')
                    ->label(__('filament-permission::permissions.guard_name'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->label(__('filament-permission::permissions.guard_name'))
                    ->options(self::guards()),
            ]);
    }

    protected static function guards(): Collection
    {
        return collect(config('auth.guards'))->mapWithKeys(function ($value, $key) {
            return [$key => $key];
        });
    }
}
