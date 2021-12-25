<?php

namespace FilamentPackages\FilamentPermission\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use FilamentPackages\FilamentPermission\Resources\PermissionResource\Pages;
use FilamentPackages\FilamentPermission\Resources\PermissionResource\RelationManagers;

class PermissionResource extends Resource
{
    public static function getModel(): string
    {
        return config('filament-permission.resources.permissions.model');
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return config('filament-permission.resources.permissions.titleAttribute', 'name');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-permission::resources.Permissions');
    }

    protected static function getNavigationIcon(): string
    {
        return config('filament-permission.resources.permissions.navigationIcon', 'heroicon-o-collection');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('filament-permission::navigation.sidebar-group-label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament-permission::resources.Permission');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-permission::permissions.name'))
                            ->unique(self::getModel(), 'name', fn ($record) => $record)
                            ->required()
                            ->maxValue(255),

                        Forms\Components\Select::make('guard_name')
                            ->label(__('filament-permission::permissions.guard_name'))
                            ->options(self::guards())
                            ->rules([Rule::in(self::guards())])
                            ->required(),
                    ])
                    ->columns(2)
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-permission::permissions.created_at'))
                    ->dateTime(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-permission::permissions.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->label(__('filament-permission::permissions.guard_name'))
                    ->options(self::guards()),
            ])
            ->pushActions([
                Tables\Actions\LinkAction::make('delete')
                    ->action(fn ($record) => $record->delete())
                    ->requiresConfirmation()
                    ->color('danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    protected static function guards(): Collection
    {
        return collect(config('auth.guards'))->mapWithKeys(function ($value, $key) {
            return [$key => $key];
        });
    }
}
