<x-guest-layout>
    <h2 class="text-2xl font-bold text-slate-900 mb-2 text-center">Forgot Password</h2>
    <p class="text-sm text-slate-600 text-center mb-6">
        No worries. Enter your email address and we'll send you a link to reset your password.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div class="mb-6">
            <x-input-label for="email" value="Email Address" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" class="mt-1" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit -->
        <x-primary-button class="w-full justify-center py-3">
            Send Reset Link
        </x-primary-button>

        <!-- Back to Login -->
        <p class="mt-6 text-center text-sm text-slate-600">
            Remember your password?
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold transition">Sign in</a>
        </p>
    </form>
</x-guest-layout>
