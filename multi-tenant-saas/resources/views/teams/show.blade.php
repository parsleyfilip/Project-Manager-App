<x-app-layout>
    <x-page-header>
        <x-slot name="title">{{ $team->name }}</x-slot>
        <x-slot name="description">{{ $team->description }}</x-slot>
        <x-slot name="actions">
            @can('update', $team)
                <a href="{{ route('teams.edit', $team) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Edit Team
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Team Info -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Team Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Team Details</h3>
                            <dl class="mt-6 space-y-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Owner</dt>
                                    <dd class="mt-1 flex items-center">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                            <span class="text-sm font-medium leading-none text-white">
                                                {{ substr($team->owner->name, 0, 1) }}
                                            </span>
                                        </span>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $team->owner->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $team->owner->email }}</p>
                                        </div>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $team->created_at->format('M j, Y') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Members</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $team->members->count() }} members</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Projects</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $team->projects->count() }} projects</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Team Members -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Team Members</h3>
                                @can('update', $team)
                                    <a href="{{ route('teams.edit', $team) }}#members" class="text-sm text-indigo-600 hover:text-indigo-900">
                                        Manage Members
                                    </a>
                                @endcan
                            </div>
                            <div class="mt-6 space-y-4">
                                @foreach($team->members as $member)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                                <span class="text-sm font-medium leading-none text-white">
                                                    {{ substr($member->name, 0, 1) }}
                                                </span>
                                            </span>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                        @if($member->id === $team->owner_id)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Owner
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Projects -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Projects</h3>
                                <a href="{{ route('projects.create', ['team_id' => $team->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    New Project
                                </a>
                            </div>

                            <div class="mt-6 space-y-4">
                                @forelse($team->projects as $project)
                                    <div class="border rounded-lg p-4 hover:shadow-sm transition-shadow duration-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-lg font-medium">
                                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600">
                                                        {{ $project->name }}
                                                    </a>
                                                </h4>
                                                @if($project->description)
                                                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $project->description }}</p>
                                                @endif
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

                                        <div class="mt-4 flex items-center justify-between text-sm">
                                            <div class="flex items-center space-x-4">
                                                <span class="text-gray-500">{{ $project->tasks_count ?? 0 }} tasks</span>
                                                @if($project->due_date)
                                                    <span class="text-gray-500">Due {{ $project->due_date->format('M j, Y') }}</span>
                                                @endif
                                            </div>
                                            @can('update', $project)
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('projects.edit', $project) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    @can('delete', $project)
                                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this project?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-8">No projects found for this team.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 