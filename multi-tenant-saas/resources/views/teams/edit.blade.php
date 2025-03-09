<x-app-layout>
    <x-page-header>
        <x-slot name="title">Edit Team</x-slot>
        <x-slot name="description">Update team details and manage members.</x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Team Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form action="{{ route('teams.update', $team) }}" method="POST" class="p-6">
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">
                                <!-- Team Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Team Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $team->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $team->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end space-x-3">
                                <a href="{{ route('teams.show', $team) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Update Team
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Current Members -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Team Members</h3>
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
                                        <div class="flex items-center">
                                            @if($member->id === $team->owner_id)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Owner
                                                </span>
                                            @else
                                                <form action="{{ route('teams.remove-member', [$team, $member]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to remove this member from the team?')">
                                                        Remove
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Add Members -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Add Members</h3>
                            <form action="{{ route('teams.add-member', $team) }}" method="POST" class="mt-6">
                                @csrf
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter email address" required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Add Member
                                    </button>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">An invitation email will be sent to the new member.</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 