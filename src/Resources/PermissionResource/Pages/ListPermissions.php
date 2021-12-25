<?php

namespace FilamentPackages\FilamentPermission\Resources\PermissionResource\Pages;

use FilamentPackages\FilamentPermission\Resources\PermissionResource;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;
}
