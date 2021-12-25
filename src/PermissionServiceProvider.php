<?php

namespace FilamentPackages\FilamentPermission;

use Filament\PluginServiceProvider;
use FilamentPackages\FilamentPermission\Resources\RoleResource;
use FilamentPackages\FilamentPermission\Resources\PermissionResource;

class PermissionServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-permission';

    protected function getResources(): array
    {
        return [
            RoleResource::class,
            PermissionResource::class,
        ];
    }

    public function registeringPackage()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-permission.php', 'filament-permission'
        );
    }

    public function bootingPackage()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-permission');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/filament-permission.php' => $this->app->configPath('filament-permission.php'),
            ], 'filament-permission-config');

            $this->publishes([
                __DIR__ . '/../resources/lang' => $this->app->resourcePath('lang/vendor/filament-permission'),
            ], 'filament-permission-lang');
        }
    }
}
