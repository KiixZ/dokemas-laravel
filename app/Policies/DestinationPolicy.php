<?php

namespace App\Policies;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DestinationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Destination $destination)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->role === 'staff' || $user->role === 'admin';
    }

    public function update(User $user, Destination $destination)
    {
        return $user->id === $destination->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Destination $destination)
    {
        return $user->id === $destination->user_id || $user->role === 'admin';
    }

    public function restore(User $user, Destination $destination)
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Destination $destination)
    {
        return $user->role === 'admin';
    }
}

