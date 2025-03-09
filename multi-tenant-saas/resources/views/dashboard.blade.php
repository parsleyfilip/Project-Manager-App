<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Projects Overview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Projects</h3>
                            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                New Project
                            </a>
                        </div>
                        @forelse(auth()->user()->teams()->with(['projects' => function($query) { 
                            $query->latest()->limit(5); 
                        }])->get()->pluck('projects')->flatten() as $project)
                            <div class="mb-4 p-4 border rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold">
                                            <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600">
                                                {{ $project->name }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $project->team->name }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        match($project->status) {
                                            'planning' => 'bg-gray-100 text-gray-800',
                                            'in_progress' => 'bg-blue-100 text-blue-800',
                                            'on_hold' => 'bg-yellow-100 text-yellow-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        }
                                    }}">
                                        {{ str_replace('_', ' ', ucfirst($project->status)) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No projects found.</p>
                        @endforelse
                        <div class="mt-4">
                            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all projects →</a>
                        </div>
                    </div>
                </div>

                <!-- Tasks Overview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">My Tasks</h3>
                            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                New Task
                            </a>
                        </div>
                        @php
                            $tasks = auth()->user()->assignedTasks()
                                ->with(['project', 'project.team'])
                                ->latest()
                                ->limit(5)
                                ->get();
                        @endphp
                        @forelse($tasks as $task)
                            <div class="mb-4 p-4 border rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold">
                                            <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-600">
                                                {{ $task->title }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $task->project->name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded-full {{ 
                                            match($task->priority) {
                                                'low' => 'bg-gray-100 text-gray-800',
                                                'medium' => 'bg-blue-100 text-blue-800',
                                                'high' => 'bg-yellow-100 text-yellow-800',
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
                                                'in_review' => 'bg-yellow-100 text-yellow-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            }
                                        }}">
                                            {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No tasks assigned to you.</p>
                        @endforelse
                        <div class="mt-4">
                            <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all tasks →</a>
                        </div>
                    </div>
                </div>

                <!-- Teams Overview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">My Teams</h3>
                            <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                New Team
                            </a>
                        </div>
                        @forelse(auth()->user()->teams()->withCount(['members', 'projects'])->latest()->limit(5)->get() as $team)
                            <div class="mb-4 p-4 border rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold">
                                            <a href="{{ route('teams.show', $team) }}" class="hover:text-indigo-600">
                                                {{ $team->name }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $team->members_count }} members · {{ $team->projects_count }} projects
                                        </p>
                                    </div>
                                    @if($team->owner_id === auth()->id())
                                        <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">Owner</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No teams found.</p>
                        @endforelse
                        <div class="mt-4">
                            <a href="{{ route('teams.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all teams →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
