<x-guest-layout>
    <h2 class="text-2xl font-bold text-slate-900 mb-6 text-center">Reset Password</h2>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="mb-4">
            <x-input-label for="email" value="Email Address" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" class="mt-1" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" value="New Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Enter new password" class="mt-1" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" value="Confirm New Password" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password" class="mt-1" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit -->
        <x-primary-button class="w-full justify-center py-3">
            Reset Password
        </x-primary-button>
    </form>
</x-guest-layout>
