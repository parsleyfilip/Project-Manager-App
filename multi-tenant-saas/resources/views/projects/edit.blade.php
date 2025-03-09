<x-app-layout>
    <x-page-header>
        <x-slot name="title">Edit Project</x-slot>
        <x-slot name="description">Update project details and settings.</x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="space-y-8">
                            <!-- Team Selection -->
                            <div class="space-y-2">
                                <label for="team_id" class="block text-sm font-medium text-gray-900">Team</label>
                                <select name="team_id" id="team_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                    <option value="">Select a team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('team_id', $project->team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('team_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Project Name -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-900">Project Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" 
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                                <textarea name="description" id="description" rows="4" 
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-medium text-gray-900">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required>
                                    @foreach(['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $project->status) === $status ? 'selected' : '' }}>
                                            {{ str_replace('_', ' ', ucfirst($status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label for="start_date" class="block text-sm font-medium text-gray-900">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" 
                                        value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" 
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="due_date" class="block text-sm font-medium text-gray-900">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" 
                                        value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}" 
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                    @error('due_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-8 border-t border-gray-200 flex items-center justify-end gap-4">
                            <x-button variant="secondary" :href="route('projects.show', $project)">
                                Cancel
                            </x-button>
                            <x-button variant="primary" type="submit">
                                Update Project
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 