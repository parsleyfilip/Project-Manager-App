<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('tasks.store') }}" method="POST" class="divide-y divide-gray-200">
                    @csrf

                    <div class="p-6 space-y-6">
                        <!-- Project Selection -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-900">Project</label>
                            <select name="project_id" id="project_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                <option value="">Select a project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', request('project_id')) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }} ({{ $project->team->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Task Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-900">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status and Priority -->
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-900">Status</label>
                                <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                    @foreach(['todo', 'in_progress', 'in_review', 'completed'] as $status)
                                        <option value="{{ $status }}" {{ old('status', 'todo') === $status ? 'selected' : '' }}>
                                            {{ str_replace('_', ' ', ucfirst($status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-900">Priority</label>
                                <select name="priority" id="priority" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                    @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                                        <option value="{{ $priority }}" {{ old('priority', 'medium') === $priority ? 'selected' : '' }}>
                                            {{ ucfirst($priority) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Assignee and Due Date -->
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="assignee_id" class="block text-sm font-medium text-gray-900">Assign To</label>
                                <select name="assignee_id" id="assignee_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                    <option value="">Select team member</option>
                                    @foreach($teamMembers as $member)
                                        <option value="{{ $member->id }}" {{ old('assignee_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignee_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-900">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                @error('due_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-4">
                        <x-button variant="secondary" :href="route('tasks.index')">
                            Cancel
                        </x-button>
                        <x-button variant="primary" type="submit">
                            Create Task
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 