<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInvitation;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Team::query()
            ->whereHas('members', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->withCount(['members', 'projects']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $teams = $query->latest()->paginate(12);

        return view('teams.index', [
            'teams' => $teams,
        ]);
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
            'member_emails' => ['nullable', 'array'],
            'member_emails.*' => ['nullable', 'email', 'distinct'],
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'owner_id' => $request->user()->id,
        ]);

        // Add the creator as a member
        $team->members()->attach($request->user()->id);

        // Send invitations to other members
        if (!empty($validated['member_emails'])) {
            foreach ($validated['member_emails'] as $email) {
                if (empty($email)) continue;

                $user = User::firstOrCreate(
                    ['email' => $email],
                    ['name' => explode('@', $email)[0], 'password' => bcrypt(str_random(16))]
                );

                if (!$team->members->contains($user)) {
                    $team->members()->attach($user);
                    Mail::to($email)->send(new TeamInvitation($team, $request->user()));
                }
            }
        }

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $this->authorize('view', $team);

        $team->load(['owner', 'members', 'projects' => function ($query) {
            $query->withCount('tasks')->latest();
        }]);

        return view('teams.show', [
            'team' => $team,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $this->authorize('update', $team);

        $team->load(['members']);

        return view('teams.edit', [
            'team' => $team,
        ]);
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

        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }

    /**
     * Add a member to the team.
     */
    public function addMember(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            ['name' => explode('@', $validated['email'])[0], 'password' => bcrypt(str_random(16))]
        );

        if (!$team->members->contains($user)) {
            $team->members()->attach($user);
            Mail::to($validated['email'])->send(new TeamInvitation($team, $request->user()));
        }

        return redirect()->route('teams.edit', $team)
            ->with('success', 'Team member added successfully.');
    }

    /**
     * Remove a member from the team.
     */
    public function removeMember(Request $request, Team $team, User $user)
    {
        $this->authorize('update', $team);

        if ($user->id === $team->owner_id) {
            return redirect()->route('teams.edit', $team)
                ->with('error', 'Cannot remove the team owner.');
        }

        $team->members()->detach($user);

        return redirect()->route('teams.edit', $team)
            ->with('success', 'Team member removed successfully.');
    }
}
