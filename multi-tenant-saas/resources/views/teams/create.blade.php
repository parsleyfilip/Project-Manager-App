<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Team
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('teams.store') }}">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Team Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                                    placeholder="Enter team description">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="member_emails" class="block text-sm font-medium text-gray-700">
                                Invite Team Members (Optional)
                            </label>
                            <div class="mt-1">
                                <textarea id="member_emails" name="member_emails" rows="3"
                                    class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Enter email addresses, one per line">{{ old('member_emails') }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Enter email addresses of team members, one per line.</p>
                            @error('member_emails')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <x-button variant="secondary" href="{{ route('teams.index') }}" class="mr-3">
                                Cancel
                            </x-button>
                            <x-button variant="primary" type="submit">
                                Create Team
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 