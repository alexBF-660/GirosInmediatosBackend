<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Paises;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaisesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Paises');
    }

    public function view(AuthUser $authUser, Paises $paises): bool
    {
        return $authUser->can('View:Paises');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Paises');
    }

    public function update(AuthUser $authUser, Paises $paises): bool
    {
        return $authUser->can('Update:Paises');
    }

    public function delete(AuthUser $authUser, Paises $paises): bool
    {
        return $authUser->can('Delete:Paises');
    }

    public function restore(AuthUser $authUser, Paises $paises): bool
    {
        return $authUser->can('Restore:Paises');
    }

    public function forceDelete(AuthUser $authUser, Paises $paises): bool
    {
        return $authUser->can('ForceDelete:Paises');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Paises');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Paises');
    }

    public function replicate(AuthUser $authUser, Paises $paises): bool
    {
        return $authUser->can('Replicate:Paises');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Paises');
    }

}