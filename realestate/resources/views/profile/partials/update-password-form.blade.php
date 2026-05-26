<section>
    <header>
        <h3 class="text-lg font-semibold text-slate-900">Update Password</h3>
        <p class="mt-1 text-sm text-slate-600">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div class="mb-4">
            <x-input-label for="update_password_current_password" value="Current Password" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" placeholder="Enter current password" class="mt-1" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div class="mb-4">
            <x-input-label for="update_password_password" value="New Password" />
            <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" placeholder="Enter new password" class="mt-1" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirm New Password -->
        <div class="mb-6">
            <x-input-label for="update_password_password_confirmation" value="Confirm Password" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Confirm new password" class="mt-1" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Update Password</x-primary-button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-green-600 font-medium">Saved.</p>
            @endif
        </div>
    </form>
</section>
