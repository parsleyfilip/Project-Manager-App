<x-app-layout>
    <x-page-header>
        <x-slot name="title">Teams</x-slot>
        <x-slot name="actions">
            <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                New Team
            </a>
        </x-slot>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-4">
                    <form action="{{ route('teams.index') }}" method="GET" class="flex gap-4">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700">Search Teams</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Search by team name...">
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Search
                            </button>
                            @if(request()->has('search'))
                                <a href="{{ route('teams.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Teams List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($teams as $team)
                            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-5">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold">
                                                <a href="{{ route('teams.show', $team) }}" class="hover:text-indigo-600">
                                                    {{ $team->name }}
                                                </a>
                                            </h3>
                                            @if($team->owner_id === auth()->id())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Owner
                                                </span>
                                            @endif
                                        </div>
                                        @if($team->owner_id === auth()->id())
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('teams.edit', $team) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('teams.destroy', $team) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this team? This action cannot be undone.')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm text-gray-500">
                                            <span>{{ $team->members_count }} members</span>
                                            <span>{{ $team->projects_count }} projects</span>
                                        </div>
                                    </div>

                                    @if($team->description)
                                        <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                            {{ $team->description }}
                                        </p>
                                    @endif

                                    <div class="mt-4">
                                        @if($team->members->isNotEmpty())
                                            <div class="flex -space-x-2 overflow-hidden">
                                                @foreach($team->members->take(5) as $member)
                                                    <span class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-gray-500">
                                                        <span class="flex h-full w-full items-center justify-center text-xs font-medium text-white">
                                                            {{ substr($member->name, 0, 1) }}
                                                        </span>
                                                    </span>
                                                @endforeach
                                                @if($team->members->count() > 5)
                                                    <span class="flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white bg-gray-100 text-xs font-medium text-gray-500">
                                                        +{{ $team->members->count() - 5 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3">
                                <p class="text-gray-500 text-center py-8">No teams found.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($teams->hasPages())
                        <div class="mt-6">
                            {{ $teams->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 