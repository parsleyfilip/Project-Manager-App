<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            FlipTask Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex items-start gap-4">
                        <div class="bg-teal-100 p-1.5 rounded-lg shrink-0">
                            <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-2">Total Projects</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ auth()->user()->teams()->withCount('projects')->get()->sum('projects_count') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-1.5 rounded-lg shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-2">Active Tasks</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ auth()->user()->assignedTasks()->where('status', '!=', 'completed')->count() }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex items-start gap-4">
                        <div class="bg-purple-100 p-1.5 rounded-lg shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-2">My Teams</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ auth()->user()->teams()->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Projects Overview -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Projects</h3>
                            <x-button :href="route('projects.create')" variant="primary">
                                New Project
                            </x-button>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse(auth()->user()->teams()->with(['projects' => function($query) { 
                                $query->latest()->limit(5); 
                            }])->get()->pluck('projects')->flatten() as $project)
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">
                                                <a href="{{ route('projects.show', $project) }}" class="hover:text-teal-600">
                                                    {{ $project->name }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $project->team->name }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ 
                                            match($project->status) {
                                                'planning' => 'bg-gray-100 text-gray-800',
                                                'in_progress' => 'bg-blue-100 text-blue-800',
                                                'on_hold' => 'bg-amber-100 text-amber-800',
                                                'completed' => 'bg-emerald-100 text-emerald-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            }
                                        }}">
                                            {{ str_replace('_', ' ', ucfirst($project->status)) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="mt-3 text-sm font-medium text-gray-900">No projects yet</h3>
                                    <p class="mt-2 text-sm text-gray-500">Create your first project to get started.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('projects.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                View all projects →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tasks Overview -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-lg font-semibold text-gray-900">My Tasks</h3>
                            <x-button :href="route('tasks.create')" variant="primary">
                                New Task
                            </x-button>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse(auth()->user()->assignedTasks()->with(['project', 'project.team'])->latest()->limit(5)->get() as $task)
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">
                                                <a href="{{ route('tasks.show', $task) }}" class="hover:text-teal-600">
                                                    {{ $task->title }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $task->project->name }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs rounded-full {{ 
                                                match($task->priority) {
                                                    'low' => 'bg-gray-100 text-gray-800',
                                                    'medium' => 'bg-blue-100 text-blue-800',
                                                    'high' => 'bg-amber-100 text-amber-800',
                                                    'urgent' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                }
                                            }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                            <span class="px-2 py-1 text-xs rounded-full {{ 
                                                match($task->status) {
                                                    'todo' => 'bg-gray-100 text-gray-800',
                                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                                    'in_review' => 'bg-amber-100 text-amber-800',
                                                    'completed' => 'bg-emerald-100 text-emerald-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                }
                                            }}">
                                                {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <h3 class="mt-3 text-sm font-medium text-gray-900">No tasks assigned</h3>
                                    <p class="mt-2 text-sm text-gray-500">Create a new task to get started.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('tasks.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                View all tasks →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Teams Overview -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-lg font-semibold text-gray-900">My Teams</h3>
                            <x-button :href="route('teams.create')" variant="primary">
                                New Team
                            </x-button>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse(auth()->user()->teams()->withCount(['members', 'projects'])->latest()->limit(5)->get() as $team)
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">
                                                <a href="{{ route('teams.show', $team) }}" class="hover:text-teal-600">
                                                    {{ $team->name }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $team->members_count }} members · {{ $team->projects_count }} projects
                                            </p>
                                        </div>
                                        @if($team->owner_id === auth()->id())
                                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">Owner</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <h3 class="mt-3 text-sm font-medium text-gray-900">No teams yet</h3>
                                    <p class="mt-2 text-sm text-gray-500">Create your first team to collaborate.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('teams.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                View all teams →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
