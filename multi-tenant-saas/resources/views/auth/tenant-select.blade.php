<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Please select your tenant domain to continue.') }}
    </div>

    <form method="POST" action="{{ route('tenant.select') }}">
        @csrf

        <!-- Domain Selection -->
        <div>
            <x-input-label for="domain" :value="__('Select Domain')" />
            <select id="domain" name="domain" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Select a domain') }}</option>
                @foreach($domains as $domain)
                    <option value="{{ $domain->domain }}" {{ old('domain') == $domain->domain ? 'selected' : '' }}>
                        {{ $domain->domain }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('tenant.register') }}">
                {{ __('Need to register a new tenant?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Continue') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> 