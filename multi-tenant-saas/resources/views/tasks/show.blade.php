<x-app-layout>
    <x-page-header>
        <x-slot name="title">{{ $task->title }}</x-slot>
        <x-slot name="description">{{ $task->project->name }} · {{ $task->project->team->name }}</x-slot>
        <x-slot name="actions">
            @can('update', $task)
                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Edit Task
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Task Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
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
                                @can('delete', $task)
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this task?')">
                                            Delete Task
                                        </button>
                                    </form>
                                @endcan
                            </div>

                            @if($task->description)
                                <div class="mt-6">
                                    <h3 class="text-lg font-medium text-gray-900">Description</h3>
                                    <div class="mt-2 prose prose-sm max-w-none">
                                        {{ $task->description }}
                                    </div>
                                </div>
                            @endif

                            <!-- Task Timeline -->
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900">Timeline</h3>
                                <div class="mt-4 space-y-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Created</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $task->created_at->format('M j, Y \a\t g:i A') }} by {{ $task->creator->name }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($task->updated_at->gt($task->created_at))
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                                    <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                                <p class="text-sm text-gray-500">{{ $task->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($task->due_date)
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $task->due_date->isPast() ? 'bg-red-100' : 'bg-gray-100' }}">
                                                    <svg class="h-5 w-5 {{ $task->due_date->isPast() ? 'text-red-500' : 'text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Due Date</p>
                                                <p class="text-sm {{ $task->due_date->isPast() ? 'text-red-500' : 'text-gray-500' }}">
                                                    {{ $task->due_date->format('M j, Y') }}
                                                    @if($task->due_date->isPast())
                                                        (Overdue)
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Assignment -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Assignment</h3>
                            <div class="mt-6">
                                @if($task->assignee)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                                <span class="text-sm font-medium leading-none text-white">
                                                    {{ substr($task->assignee->name, 0, 1) }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $task->assignee->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $task->assignee->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No one is assigned to this task.</p>
                                @endif

                                @can('update', $task)
                                    <div class="mt-6">
                                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <select name="assignee_id" id="quick_assignee" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    <option value="">Unassign</option>
                                                    @foreach($teamMembers as $member)
                                                        <option value="{{ $member->id }}" {{ $task->assignee_id == $member->id ? 'selected' : '' }}>
                                                            {{ $member->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-3">
                                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Update Assignment
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @can('update', $task)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                                <div class="mt-6 space-y-4">
                                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label for="quick_status" class="block text-sm font-medium text-gray-700">Update Status</label>
                                            <select name="status" id="quick_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                @foreach(['todo', 'in_progress', 'in_review', 'completed'] as $status)
                                                    <option value="{{ $status }}" {{ $task->status === $status ? 'selected' : '' }}>
                                                        {{ str_replace('_', ' ', ucfirst($status)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Update Status
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 