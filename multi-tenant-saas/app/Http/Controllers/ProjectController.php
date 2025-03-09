<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query()
            ->whereIn('team_id', $request->user()->teams->pluck('id'))
            ->with(['team', 'tasks'])
            ->withCount('tasks');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('team')) {
            $query->where('team_id', $request->team);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $projects = $query->latest()->paginate(12);

        return view('projects.index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $teams = $request->user()->teams;
        $selectedTeam = null;

        if ($request->filled('team_id')) {
            $selectedTeam = $teams->find($request->team_id);
        }

        return view('projects.create', [
            'teams' => $teams,
            'selectedTeam' => $selectedTeam,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'team_id' => ['required', 'exists:teams,id'],
            'status' => ['required', 'in:planning,in_progress,on_hold,completed,cancelled'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $team = Team::findOrFail($validated['team_id']);
        
        // Check if user belongs to the team
        abort_unless($team->members->contains($request->user()), 403);

        $project = $team->projects()->create([
            ...$validated,
            'creator_id' => $request->user()->id,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['team', 'creator', 'tasks' => function ($query) {
            $query->with(['assignee'])->latest();
        }]);

        return view('projects.show', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $teams = auth()->user()->teams;

        return view('projects.edit', [
            'project' => $project,
            'teams' => $teams,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'team_id' => ['required', 'exists:teams,id'],
            'status' => ['required', 'in:planning,in_progress,on_hold,completed,cancelled'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $team = Team::findOrFail($validated['team_id']);
        
        // Check if user belongs to the team
        abort_unless($team->members->contains($request->user()), 403);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
