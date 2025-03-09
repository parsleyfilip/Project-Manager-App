<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['project', 'assignee'])
            ->whereIn('project_id', function ($query) {
                $query->select('id')
                    ->from('projects')
                    ->whereIn('team_id', auth()->user()->teams()->pluck('teams.id'));
            })
            ->latest()
            ->paginate(20);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::whereIn('team_id', auth()->user()->teams()->pluck('teams.id'))->get();
        $users = User::whereIn('id', function ($query) {
            $query->select('user_id')
                ->from('team_members')
                ->whereIn('team_id', auth()->user()->teams()->pluck('teams.id'));
        })->get();

        return view('tasks.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')->where(function ($query) {
                    $query->whereIn('team_id', auth()->user()->teams()->pluck('teams.id'));
                }),
            ],
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) use ($request) {
                    $query->whereIn('id', function ($subquery) use ($request) {
                        $subquery->select('user_id')
                            ->from('team_members')
                            ->where('team_id', Project::find($request->project_id)->team_id);
                    });
                }),
            ],
            'status' => ['required', 'in:todo,in_progress,in_review,completed'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $validated['created_by'] = auth()->id();
        $validated['order'] = Task::where('project_id', $request->project_id)->max('order') + 1;

        $task = Task::create($validated);

        if ($request->wantsJson()) {
            return response()->json($task->load('assignee'));
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['project', 'assignee', 'creator']);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $projects = Project::whereIn('team_id', auth()->user()->teams()->pluck('teams.id'))->get();
        $users = User::whereIn('id', function ($query) use ($task) {
            $query->select('user_id')
                ->from('team_members')
                ->where('team_id', $task->project->team_id);
        })->get();

        return view('tasks.edit', compact('task', 'projects', 'users'));
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
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')->where(function ($query) {
                    $query->whereIn('team_id', auth()->user()->teams()->pluck('teams.id'));
                }),
            ],
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) use ($request) {
                    $query->whereIn('id', function ($subquery) use ($request) {
                        $subquery->select('user_id')
                            ->from('team_members')
                            ->where('team_id', Project::find($request->project_id)->team_id);
                    });
                }),
            ],
            'status' => ['required', 'in:todo,in_progress,in_review,completed'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $task->update($validated);

        if ($request->wantsJson()) {
            return response()->json($task->load('assignee'));
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

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Task deleted successfully.']);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
