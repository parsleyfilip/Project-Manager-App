<x-app-layout>
    <x-page-header>
        <x-slot name="title">Projects</x-slot>
        <x-slot name="actions">
            <x-button :href="route('projects.create')" variant="primary">
                New Project
            </x-button>
        </x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-4">
                    <form action="{{ route('projects.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                @foreach(['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', ucfirst($status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="team" class="block text-sm font-medium text-gray-700">Team</label>
                            <select name="team" id="team" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Teams</option>
                                @foreach(auth()->user()->teams as $team)
                                    <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Search projects...">
                        </div>

                        <div class="flex items-end">
                            <x-button type="submit" variant="primary">
                                Filter
                            </x-button>
                            @if(request()->hasAny(['status', 'team', 'search']))
                                <x-button :href="route('projects.index')" variant="secondary" class="ml-3">
                                    Clear
                                </x-button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Projects List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($projects as $project)
                            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-5">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold">
                                                <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600">
                                                    {{ $project->name }}
                                                </a>
                                            </h3>
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

                                    <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                        {{ $project->description ?? 'No description provided.' }}
                                    </p>

                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm text-gray-500">
                                            <span>{{ $project->tasks_count ?? 0 }} tasks</span>
                                            @if($project->due_date)
                                                <span>Due {{ $project->due_date->format('M j, Y') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @can('update', $project)
                                        <div class="mt-4 flex justify-end space-x-2">
                                            <a href="{{ route('projects.edit', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @can('delete', $project)
                                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this project?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3">
                                <p class="text-gray-500 text-center py-8">No projects found.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($projects->hasPages())
                        <div class="mt-6">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 