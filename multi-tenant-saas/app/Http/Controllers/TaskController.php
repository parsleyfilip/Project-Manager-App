<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query()
            ->whereIn('project_id', function ($query) use ($request) {
                $query->select('id')
                    ->from('projects')
                    ->whereIn('team_id', $request->user()->teams->pluck('id'));
            })
            ->with(['project', 'project.team', 'assignee'])
            ->withCount('comments');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tasks = $query->latest()->paginate(15);
        $projects = Project::whereIn('team_id', $request->user()->teams->pluck('id'))->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $projects = Project::whereIn('team_id', $request->user()->teams->pluck('id'))->get();
        $selectedProject = null;
        $teamMembers = collect();

        if ($request->filled('project_id')) {
            $selectedProject = $projects->find($request->project_id);
            if ($selectedProject) {
                $teamMembers = $selectedProject->team->members;
            }
        }

        return view('tasks.create', [
            'projects' => $projects,
            'selectedProject' => $selectedProject,
            'teamMembers' => $teamMembers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'project_id' => ['required', 'exists:projects,id'],
            'status' => ['required', 'in:todo,in_progress,in_review,completed'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $project = Project::findOrFail($validated['project_id']);
        
        // Check if user belongs to the project's team
        abort_unless($project->team->members->contains($request->user()), 403);

        // If assignee is set, check if they belong to the team
        if (!empty($validated['assignee_id'])) {
            abort_unless($project->team->members->contains($validated['assignee_id']), 403);
        }

        $task = $project->tasks()->create([
            ...$validated,
            'creator_id' => $request->user()->id,
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['project', 'project.team', 'creator', 'assignee', 'comments.user']);
        $teamMembers = $task->project->team->members;

        return view('tasks.show', [
            'task' => $task,
            'teamMembers' => $teamMembers,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $projects = Project::whereIn('team_id', auth()->user()->teams->pluck('id'))->get();
        $teamMembers = $task->project->team->members;

        return view('tasks.edit', [
            'task' => $task,
            'projects' => $projects,
            'teamMembers' => $teamMembers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'project_id' => ['required', 'exists:projects,id'],
            'status' => ['required', 'in:todo,in_progress,in_review,completed'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $project = Project::findOrFail($validated['project_id']);
        
        // Check if user belongs to the project's team
        abort_unless($project->team->members->contains($request->user()), 403);

        // If assignee is set, check if they belong to the team
        if (!empty($validated['assignee_id'])) {
            abort_unless($project->team->members->contains($validated['assignee_id']), 403);
        }

        $task->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Task updated successfully.',
                'task' => $task->fresh(['assignee']),
            ]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
