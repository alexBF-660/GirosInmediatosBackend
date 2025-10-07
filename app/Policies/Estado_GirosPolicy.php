<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Estado_Giros;
use Illuminate\Auth\Access\HandlesAuthorization;

class Estado_GirosPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EstadoGiros');
    }

    public function view(AuthUser $authUser, Estado_Giros $estadoGiros): bool
    {
        return $authUser->can('View:EstadoGiros');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EstadoGiros');
    }

    public function update(AuthUser $authUser, Estado_Giros $estadoGiros): bool
    {
        return $authUser->can('Update:EstadoGiros');
    }

    public function delete(AuthUser $authUser, Estado_Giros $estadoGiros): bool
    {
        return $authUser->can('Delete:EstadoGiros');
    }

    public function restore(AuthUser $authUser, Estado_Giros $estadoGiros): bool
    {
        return $authUser->can('Restore:EstadoGiros');
    }

    public function forceDelete(AuthUser $authUser, Estado_Giros $estadoGiros): bool
    {
        return $authUser->can('ForceDelete:EstadoGiros');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EstadoGiros');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EstadoGiros');
    }

    public function replicate(AuthUser $authUser, Estado_Giros $estadoGiros): bool
    {
        return $authUser->can('Replicate:EstadoGiros');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EstadoGiros');
    }

}