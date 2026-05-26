<section>
    <header>
        <h3 class="text-lg font-semibold text-slate-900">Delete Account</h3>
        <p class="mt-1 text-sm text-red-600">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </header>

    <div class="mt-6">
        <p class="text-sm text-slate-600 mb-4">
            Before deleting your account, please download any data or information that you wish to retain. This action cannot be undone.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
            @csrf
            @method('delete')

            <!-- Password Confirmation -->
            <div class="mb-6">
                <x-input-label for="delete_password" value="Password" />
                <x-text-input id="delete_password" name="password" type="password" placeholder="Enter your password to confirm" class="mt-1" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <x-danger-button>
                Delete Account
            </x-danger-button>
        </form>
    </div>
</section>
