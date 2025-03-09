<x-app-layout>
    <x-page-header>
        <x-slot name="title">{{ $project->name }}</x-slot>
        <x-slot name="description">{{ $project->team->name }}</x-slot>
        <x-slot name="actions">
            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Edit Project
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Project Details -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Project Details</h3>
                            
                            <div class="mt-6 space-y-4">
                                <div>
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

                                @if($project->description)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Description</h4>
                                        <p class="mt-1 text-sm text-gray-900">{{ $project->description }}</p>
                                    </div>
                                @endif

                                <div class="grid grid-cols-2 gap-4">
                                    @if($project->start_date)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Start Date</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{ $project->start_date->format('M j, Y') }}</p>
                                        </div>
                                    @endif

                                    @if($project->due_date)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Due Date</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{ $project->due_date->format('M j, Y') }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Created By</h4>
                                    <p class="mt-1 text-sm text-gray-900">{{ $project->creator->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Statistics -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Statistics</h3>
                            
                            <dl class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6">
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks</dt>
                                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $project->tasks->count() }}</dd>
                                </div>

                                <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6">
                                    <dt class="text-sm font-medium text-gray-500 truncate">Completed Tasks</dt>
                                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                        {{ $project->tasks->where('status', 'completed')->count() }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Tasks List -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-medium text-gray-900">Tasks</h3>
                                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add Task
                                </a>
                            </div>

                            <div class="space-y-4">
                                @forelse($project->tasks as $task)
                                    <div class="border rounded-lg p-4 hover:shadow-sm transition-shadow duration-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-lg font-medium">
                                                    <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-600">
                                                        {{ $task->title }}
                                                    </a>
                                                </h4>
                                                @if($task->description)
                                                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $task->description }}</p>
                                                @endif
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

                                        <div class="mt-4 flex items-center justify-between text-sm">
                                            <div class="flex items-center space-x-4">
                                                @if($task->assignee)
                                                    <div class="flex items-center">
                                                        <span class="text-gray-500">Assigned to:</span>
                                                        <span class="ml-1 text-gray-900">{{ $task->assignee->name }}</span>
                                                    </div>
                                                @endif
                                                @if($task->due_date)
                                                    <div class="flex items-center">
                                                        <span class="text-gray-500">Due:</span>
                                                        <span class="ml-1 text-gray-900">{{ $task->due_date->format('M j, Y') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            @can('update', $task)
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    @can('delete', $task)
                                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this task?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">No tasks found for this project.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 