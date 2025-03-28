<div class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $title }}
                </h2>
                @if(isset($description))
                    <div class="mt-1 text-sm text-gray-500">
                        {{ $description }}
                    </div>
                @endif
            </div>
            @if(isset($actions))
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</div> 