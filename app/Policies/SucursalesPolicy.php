<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Sucursales;
use Illuminate\Auth\Access\HandlesAuthorization;

class SucursalesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Sucursales');
    }

    public function view(AuthUser $authUser, Sucursales $sucursales): bool
    {
        return $authUser->can('View:Sucursales');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Sucursales');
    }

    public function update(AuthUser $authUser, Sucursales $sucursales): bool
    {
        return $authUser->can('Update:Sucursales');
    }

    public function delete(AuthUser $authUser, Sucursales $sucursales): bool
    {
        return $authUser->can('Delete:Sucursales');
    }

    public function restore(AuthUser $authUser, Sucursales $sucursales): bool
    {
        return $authUser->can('Restore:Sucursales');
    }

    public function forceDelete(AuthUser $authUser, Sucursales $sucursales): bool
    {
        return $authUser->can('ForceDelete:Sucursales');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Sucursales');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Sucursales');
    }

    public function replicate(AuthUser $authUser, Sucursales $sucursales): bool
    {
        return $authUser->can('Replicate:Sucursales');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Sucursales');
    }

}