<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Team: {{ $team->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('teams.update', $team) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Team Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name', $team->name) }}" required
                                    class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Enter team name">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="3"
                                    class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Enter team description">{{ old('description', $team->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <x-button variant="secondary" href="{{ route('teams.show', $team) }}">
                                Cancel
                            </x-button>
                            <x-button variant="primary" type="submit">
                                Update Team
                            </x-button>
                        </div>
                    </form>

                    <!-- Team Members Section -->
                    <div class="mt-10 pt-10 border-t border-gray-200">
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Team Members</h3>
                            <p class="mt-1 text-sm text-gray-500">Manage your team members here.</p>
                        </div>

                        <!-- Add Member Form -->
                        <form method="POST" action="{{ route('teams.add-member', $team) }}" class="mb-6">
                            @csrf
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Add Member</label>
                                    <div class="mt-1">
                                        <input type="email" name="email" id="email" required
                                            class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            placeholder="Enter email address">
                                    </div>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex items-end">
                                    <x-button variant="primary" type="submit">
                                        Add Member
                                    </x-button>
                                </div>
                            </div>
                        </form>

                        <!-- Members List -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900">Current Members</h4>
                            <ul role="list" class="mt-3 divide-y divide-gray-100 border-t border-b border-gray-200">
                                @foreach($team->members as $member)
                                    <li class="flex items-center justify-between py-4">
                                        <div class="flex items-center min-w-0 gap-x-4">
                                            <div class="min-w-0 flex-auto">
                                                <p class="text-sm font-semibold leading-6 text-gray-900">{{ $member->name }}</p>
                                                <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-x-4">
                                            @if($member->id === $team->owner_id)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-md">
                                                    Owner
                                                </span>
                                            @else
                                                <form method="POST" action="{{ route('teams.remove-member', [$team, $member]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button variant="danger" type="submit" 
                                                        onclick="return confirm('Are you sure you want to remove this member?')"
                                                        class="text-xs">
                                                        Remove
                                                    </x-button>
                                                </form>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if($team->owner_id === auth()->id())
                        <div class="mt-10 pt-10 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-red-600">Delete Team</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Once you delete a team, all of its data will be permanently deleted.
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('teams.destroy', $team) }}" class="flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <x-button 
                                        variant="danger"
                                        type="submit"
                                        onclick="return confirm('Are you sure you want to delete this team? This action cannot be undone.')"
                                    >
                                        Delete Team
                                    </x-button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 