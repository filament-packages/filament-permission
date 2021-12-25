<?php

namespace FilamentPackages\FilamentPermission\Resources;

use FilamentPackages\FilamentPermission\Resources\RoleResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use FilamentPackages\FilamentPermission\Resources\RoleResource\RelationManagers;

class RoleResource extends Resource
{
    public static function getModel(): string
    {
        return config('filament-permission.resources.roles.model');
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return config('filament-permission.resources.roles.titleAttribute', 'name');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-permission::resources.Roles');
    }

    protected static function getNavigationIcon(): string
    {
        return config('filament-permission.resources.roles.navigationIcon', 'heroicon-o-collection');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('filament-permission::navigation.sidebar-group-label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament-permission::resources.Role');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-permission::roles.name'))
                            ->unique(self::getModel(), 'name', fn($record) => $record)
                            ->required()
                            ->maxValue(255),

                        Forms\Components\Select::make('guard_name')
                            ->label(__('filament-permission::roles.guard_name'))
                            ->options(self::guards())
                            ->rules([Rule::in(self::guards())])
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-permission::roles.name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('guard_name')
                    ->label(__('filament-permission::roles.guard_name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-permission::roles.created_at'))
                    ->dateTime(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-permission::roles.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guard_name')
                    ->label(__('filament-permission::roles.guard_name'))
                    ->options(self::guards()),
            ])
            ->pushActions([
                Tables\Actions\LinkAction::make('delete')
                    ->action(fn($record) => $record->delete())
                    ->requiresConfirmation()
                    ->color('danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PermissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    protected static function guards(): Collection
    {
        return collect(config('auth.guards'))->mapWithKeys(function ($value, $key) {
            return [$key => $key];
        });
    }
}
