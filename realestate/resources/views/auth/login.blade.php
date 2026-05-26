<x-guest-layout>
    <h2 class="text-2xl font-bold text-slate-900 mb-6 text-center">Sign In</h2>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <x-input-label for="email" value="Email Address" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" class="mt-1" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" class="mt-1" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-slate-600">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-700 font-medium transition" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <x-primary-button class="w-full justify-center py-3">
            Sign In
        </x-primary-button>

        <!-- Register Link -->
        <p class="mt-6 text-center text-sm text-slate-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold transition">Create one</a>
        </p>
    </form>
</x-guest-layout>
