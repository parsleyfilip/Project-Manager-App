<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view teams they belong to
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        return $user->teams()->where('teams.id', $team->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create a team
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        $membership = $user->teams()->where('teams.id', $team->id)->first();

        if (!$membership) {
            return false;
        }

        // Only team owner and admins can update team
        return $team->owner_id === $user->id || $membership->pivot->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        // Only team owner can delete the team
        return $team->owner_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        return false;
    }

    public function addMember(User $user, Team $team): bool
    {
        $membership = $user->teams()->where('teams.id', $team->id)->first();

        if (!$membership) {
            return false;
        }

        // Only team owner and admins can add members
        return $team->owner_id === $user->id || $membership->pivot->role === 'admin';
    }

    public function removeMember(User $user, Team $team): bool
    {
        $membership = $user->teams()->where('teams.id', $team->id)->first();

        if (!$membership) {
            return false;
        }

        // Only team owner and admins can remove members
        return $team->owner_id === $user->id || $membership->pivot->role === 'admin';
    }
}
