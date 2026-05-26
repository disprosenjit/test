<x-guest-layout>
    <h2 class="text-2xl font-bold text-slate-900 mb-2 text-center">Verify Email</h2>
    <p class="text-sm text-slate-600 text-center mb-6">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Resend Verification Email
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-600 hover:text-slate-900 font-medium underline transition">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
