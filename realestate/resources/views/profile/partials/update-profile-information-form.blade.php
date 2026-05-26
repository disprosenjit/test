<section>
    <header>
        <h3 class="text-lg font-semibold text-slate-900">Profile Information</h3>
        <p class="mt-1 text-sm text-slate-600">Update your account's profile information and email address.</p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-6">
        @csrf
        @method('patch')

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" class="mt-1" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mb-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" class="mt-1" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mb-4">
                <p class="text-sm text-slate-600">
                    Your email address is unverified.
                    <button form="send-verification" class="text-blue-600 hover:text-blue-700 font-medium underline transition">
                        Click here to re-send the verification email.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-green-600">
                        A new verification link has been sent to your email address.
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>Save</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-green-600 font-medium">Saved.</p>
            @endif
        </div>
    </form>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>
</section>
