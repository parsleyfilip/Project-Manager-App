<x-app-layout>
    <x-page-header>
        <x-slot name="title">Create Task</x-slot>
        <x-slot name="description">Create a new task for your project.</x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="space-y-8">
                            <!-- Project Selection -->
                            <div class="space-y-2">
                                <label for="project_id" class="block text-sm font-medium text-gray-900">Project</label>
                                <select name="project_id" id="project_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                    <option value="">Select a project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id', request('project_id')) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ $project->team->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Task Title -->
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-medium text-gray-900">Task Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                                <textarea name="description" id="description" rows="4" 
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status and Priority -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label for="status" class="block text-sm font-medium text-gray-900">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                        @foreach(['todo', 'in_progress', 'in_review', 'completed'] as $status)
                                            <option value="{{ $status }}" {{ old('status', 'todo') === $status ? 'selected' : '' }}>
                                                {{ str_replace('_', ' ', ucfirst($status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="priority" class="block text-sm font-medium text-gray-900">Priority</label>
                                    <select name="priority" id="priority" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                        @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                                            <option value="{{ $priority }}" {{ old('priority', 'medium') === $priority ? 'selected' : '' }}>
                                                {{ ucfirst($priority) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Assignee and Due Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label for="assignee_id" class="block text-sm font-medium text-gray-900">Assign To</label>
                                    <select name="assignee_id" id="assignee_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                        <option value="">Select team member</option>
                                        @foreach($teamMembers as $member)
                                            <option value="{{ $member->id }}" {{ old('assignee_id') == $member->id ? 'selected' : '' }}>
                                                {{ $member->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assignee_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="due_date" class="block text-sm font-medium text-gray-900">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" 
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                    @error('due_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-8 border-t border-gray-200 flex items-center justify-end gap-4">
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
    </div>
</x-app-layout> 