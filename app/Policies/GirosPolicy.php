<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Giros;
use Illuminate\Auth\Access\HandlesAuthorization;

class GirosPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Giros');
    }

    public function view(AuthUser $authUser, Giros $giros): bool
    {
        return $authUser->can('View:Giros');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Giros');
    }

    public function update(AuthUser $authUser, Giros $giros): bool
    {
        return $authUser->can('Update:Giros');
    }

    public function delete(AuthUser $authUser, Giros $giros): bool
    {
        return $authUser->can('Delete:Giros');
    }

    public function restore(AuthUser $authUser, Giros $giros): bool
    {
        return $authUser->can('Restore:Giros');
    }

    public function forceDelete(AuthUser $authUser, Giros $giros): bool
    {
        return $authUser->can('ForceDelete:Giros');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Giros');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Giros');
    }

    public function replicate(AuthUser $authUser, Giros $giros): bool
    {
        return $authUser->can('Replicate:Giros');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Giros');
    }

}