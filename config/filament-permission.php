<?php

return [
    'resources' => [

        'roles' => [
            'model' => \Spatie\Permission\Models\Role::class,
            'navigationIcon' => 'heroicon-o-collection',
            'titleAttribute' => 'name',
        ],

        'permissions' => [
            'model' => \Spatie\Permission\Models\Permission::class,
            'navigationIcon' => 'heroicon-o-collection',
            'titleAttribute' => 'name',
        ],

    ],
];
