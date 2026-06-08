<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Nombre',
    'column.guard_name' => 'Guard',
    'column.roles' => 'Roles',
    'column.permissions' => 'Permisos',
    'column.updated_at' => 'Actualizado el',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Nombre',
    'field.guard_name' => 'Guard',
    'field.permissions' => 'Permisos',
    'field.select_all.name' => 'Seleccionar todos',
    'field.select_all.message' => 'Habilitar todos los permisos actualmente <span class="text-primary font-medium">habilitados</span> para este rol',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Seguridad',
    'nav.role.label' => 'Roles',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Rol',
    'resource.label.roles' => 'Roles',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Entidades',
    'resources' => 'Recursos',
    'widgets' => 'Widgets',
    'pages' => 'Páginas',
    'custom' => 'Permisos personalizados',

    /*
    |--------------------------------------------------------------------------
    | Permission action labels (used in role checkboxes)
    |--------------------------------------------------------------------------
    */

    'view' => 'Ver registro',
    'view_any' => 'Ver listado',
    'create' => 'Crear',
    'update' => 'Actualizar',
    'delete' => 'Eliminar registro',
    'delete_any' => 'Eliminar varios',
    'force_delete' => 'Eliminar permanentemente',
    'force_delete_any' => 'Eliminar permanentemente varios',
    'restore' => 'Restaurar registro',
    'restore_any' => 'Restaurar varios',
    'replicate' => 'Duplicar',
    'reorder' => 'Reordenar',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'Usted no tiene permiso de acceso',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Ver registro',
        'view_any' => 'Ver listado',
        'create' => 'Crear',
        'update' => 'Actualizar',
        'delete' => 'Eliminar registro',
        'delete_any' => 'Eliminar varios',
        'force_delete' => 'Eliminar permanentemente',
        'force_delete_any' => 'Eliminar permanentemente varios',
        'restore' => 'Restaurar registro',
        'restore_any' => 'Restaurar varios',
        'replicate' => 'Duplicar',
        'reorder' => 'Reordenar',
    ],
];
