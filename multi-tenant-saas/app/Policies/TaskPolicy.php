<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view tasks they have access to through teams
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->teams()->where('teams.id', $task->project->team_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Users can create tasks in projects they have access to
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        $team = $task->project->team;
        $membership = $user->teams()->where('teams.id', $team->id)->first();

        if (!$membership) {
            return false;
        }

        // Team owners and admins can update any task
        if ($team->owner_id === $user->id || $membership->pivot->role === 'admin') {
            return true;
        }

        // Task creator or assignee can update the task
        return $task->created_by === $user->id || $task->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        $team = $task->project->team;
        $membership = $user->teams()->where('teams.id', $team->id)->first();

        if (!$membership) {
            return false;
        }

        // Team owners, admins, and task creators can delete tasks
        return $team->owner_id === $user->id 
            || $membership->pivot->role === 'admin'
            || $task->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
