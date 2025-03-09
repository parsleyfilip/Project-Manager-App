<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = auth()->user()->teams()
            ->withCount(['members', 'projects'])
            ->latest()
            ->paginate(10);

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['owner_id'] = auth()->id();

        $team = Team::create($validated);
        
        // Add the creator as an admin member
        $team->members()->attach(auth()->id(), [
            'role' => 'admin',
            'permissions' => json_encode(['*']),
        ]);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $this->authorize('view', $team);

        $team->load(['members', 'projects' => function ($query) {
            $query->latest()->limit(5);
        }]);

        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $this->authorize('update', $team);
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $team->update($validated);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        if ($team->projects()->exists()) {
            return back()->with('error', 'Cannot delete team with existing projects.');
        }

        $team->members()->detach();
        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }

    public function addMember(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'in:member,admin'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($team->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this team.');
        }

        $team->members()->attach($user->id, [
            'role' => $validated['role'],
            'permissions' => $validated['role'] === 'admin' ? json_encode(['*']) : json_encode([
                'view',
                'create',
                'update',
            ]),
        ]);

        return back()->with('success', 'Team member added successfully.');
    }

    public function removeMember(Team $team, User $user)
    {
        $this->authorize('update', $team);

        if ($team->owner_id === $user->id) {
            return back()->with('error', 'Cannot remove team owner.');
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'Team member removed successfully.');
    }
}
