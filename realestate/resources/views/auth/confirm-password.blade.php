<x-guest-layout>
    <h2 class="text-2xl font-bold text-slate-900 mb-2 text-center">Confirm Password</h2>
    <p class="text-sm text-slate-600 text-center mb-6">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ url('/confirm-password') }}">
        @csrf

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" class="mt-1" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Submit -->
        <x-primary-button class="w-full justify-center py-3">
            Confirm
        </x-primary-button>
    </form>
</x-guest-layout>
