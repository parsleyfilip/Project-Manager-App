<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view projects they have access to through teams
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->teams()->where('teams.id', $project->team_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Users can create projects in teams they belong to
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        $team = $project->team;
        $membership = $user->teams()->where('teams.id', $team->id)->first();

        if (!$membership) {
            return false;
        }

        // Team owners and admins can update any project
        if ($team->owner_id === $user->id || $membership->pivot->role === 'admin') {
            return true;
        }

        // Project creator can update their own projects
        return $project->creator_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        $team = $project->team;
        $membership = $user->teams()->where('teams.id', $team->id)->first();

        if (!$membership) {
            return false;
        }

        // Only team owners and admins can delete projects
        return $team->owner_id === $user->id || $membership->pivot->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
