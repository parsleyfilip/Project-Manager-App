<x-app-layout>
    <x-page-header>
        <x-slot name="title">Tasks</x-slot>
        <x-slot name="actions">
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                New Task
            </a>
        </x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-4">
                    <form action="{{ route('tasks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                @foreach(['todo', 'in_progress', 'in_review', 'completed'] as $status)
                                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', ucfirst($status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority" id="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Priorities</option>
                                @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                                    <option value="{{ $priority }}" {{ request('priority') === $priority ? 'selected' : '' }}>
                                        {{ ucfirst($priority) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="project" class="block text-sm font-medium text-gray-700">Project</label>
                            <select name="project" id="project" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Projects</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Search tasks...">
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                            @if(request()->hasAny(['status', 'priority', 'project', 'search']))
                                <a href="{{ route('tasks.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($tasks as $task)
                            <div class="border rounded-lg p-4 hover:shadow-sm transition-shadow duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium">
                                            <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-600">
                                                {{ $task->title }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $task->project->name }}</p>
                                        @if($task->description)
                                            <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $task->description }}</p>
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
                            <p class="text-gray-500 text-center py-8">No tasks found.</p>
                        @endforelse
                    </div>

                    @if($tasks->hasPages())
                        <div class="mt-6">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 