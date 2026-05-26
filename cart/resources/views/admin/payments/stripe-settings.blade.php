@extends('layouts.admin')

@section('title', 'Stripe Settings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">Stripe Configuration</h1>
        <p class="text-gray-600 mt-1">Manage Stripe API keys and payment settings</p>
    </div>
    <a href="/admin/payments/stripe" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Back to Stripe Payments
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <form method="POST" action="/admin/payments/stripe/settings" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Environment</label>
                    <select name="environment" class="w-full px-3 py-2 border rounded-lg" required>
                        <option value="test" {{ old('environment', $settings->environment ?? 'test') === 'test' ? 'selected' : '' }}>Test</option>
                        <option value="live" {{ old('environment', $settings->environment ?? 'test') === 'live' ? 'selected' : '' }}>Live</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Currency</label>
                    <input type="text" name="currency" maxlength="3" class="w-full px-3 py-2 border rounded-lg uppercase" value="{{ old('currency', $settings->currency ?? 'USD') }}" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Publishable Key</label>
                <input type="text" name="publishable_key" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" value="{{ old('publishable_key', $settings->publishable_key ?? '') }}" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Secret Key</label>
                <input type="text" name="secret_key" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" value="{{ old('secret_key', $settings->secret_key ?? '') }}" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Webhook Secret</label>
                <input type="text" name="webhook_secret" class="w-full px-3 py-2 border rounded-lg font-mono text-sm" value="{{ old('webhook_secret', $settings->webhook_secret ?? '') }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Minimum Amount</label>
                    <input type="number" step="0.01" min="0.01" name="minimum_amount" class="w-full px-3 py-2 border rounded-lg" value="{{ old('minimum_amount', $settings->minimum_amount ?? '0.50') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Amount</label>
                    <input type="number" step="0.01" min="0.01" name="maximum_amount" class="w-full px-3 py-2 border rounded-lg" value="{{ old('maximum_amount', $settings->maximum_amount ?? '999999.99') }}" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Business Name</label>
                    <input type="text" name="business_name" class="w-full px-3 py-2 border rounded-lg" value="{{ old('business_name', $settings->business_name ?? '') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Business Email</label>
                    <input type="email" name="business_email" class="w-full px-3 py-2 border rounded-lg" value="{{ old('business_email', $settings->business_email ?? '') }}">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Notes</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ old('notes', $settings->notes ?? '') }}</textarea>
            </div>

            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $settings->is_active ?? true) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Enable Stripe payments</span>
            </label>

            <div class="pt-3 border-t">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Save Stripe Settings
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-3">Quick Links</h2>
            <div class="space-y-2 text-sm">
                <a href="/admin/payments/stripe" class="block text-blue-600 hover:underline">Stripe Payments</a>
                <a href="/admin/payments/stripe/refunds" class="block text-blue-600 hover:underline">Stripe Refunds</a>
                <a href="/admin/payments/stripe/export" class="block text-blue-600 hover:underline">Export Stripe Report</a>
                <a href="/admin/payments/stripe/config-verify" class="block text-blue-600 hover:underline">Verify Configuration (JSON)</a>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-lg p-6">
            <h2 class="text-lg font-bold mb-2 text-yellow-800">Security Note</h2>
            <p class="text-sm text-yellow-900">Use test keys in development and live keys only in production. Restrict admin access and rotate keys regularly.</p>
        </div>
    </div>
</div>
@endsection
