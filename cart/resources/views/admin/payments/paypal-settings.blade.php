@extends('layouts.admin')

@section('title', 'PayPal Settings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">PayPal Configuration</h1>
        <p class="text-gray-600 mt-1">Manage PayPal API credentials and payment settings</p>
    </div>
    <a href="/admin/payments/paypal" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Back to PayPal Payments
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <form method="POST" action="/admin/payments/paypal-settings" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Environment</label>
                    <select name="environment" class="w-full px-3 py-2 border rounded-lg" required>
                        <option value="sandbox" {{ old('environment', $settings?->environment ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                        <option value="live" {{ old('environment', $settings?->environment ?? 'sandbox') === 'live' ? 'selected' : '' }}>Live</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Currency</label>
                    <input type="text" name="currency" maxlength="3" class="w-full px-3 py-2 border rounded-lg uppercase"
                           value="{{ old('currency', $settings?->currency ?? 'USD') }}" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Client ID</label>
                <input type="text" name="client_id" class="w-full px-3 py-2 border rounded-lg font-mono text-sm"
                       value="{{ old('client_id', $settings?->client_id ?? '') }}" required>
                <p class="text-xs text-gray-500 mt-1">Found in PayPal Developer Dashboard → My Apps &amp; Credentials</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Client Secret</label>
                <input type="text" name="client_secret" class="w-full px-3 py-2 border rounded-lg font-mono text-sm"
                       value="{{ old('client_secret', $settings?->client_secret ?? '') }}" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Minimum Amount</label>
                    <input type="number" step="0.01" min="0.01" name="minimum_amount" class="w-full px-3 py-2 border rounded-lg"
                           value="{{ old('minimum_amount', $settings?->minimum_amount ?? '0.01') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Amount</label>
                    <input type="number" step="0.01" min="0.01" name="maximum_amount" class="w-full px-3 py-2 border rounded-lg"
                           value="{{ old('maximum_amount', $settings?->maximum_amount ?? '999999.99') }}" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Business Name</label>
                    <input type="text" name="business_name" class="w-full px-3 py-2 border rounded-lg"
                           value="{{ old('business_name', $settings?->business_name ?? '') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Business Email</label>
                    <input type="email" name="business_email" class="w-full px-3 py-2 border rounded-lg"
                           value="{{ old('business_email', $settings?->business_email ?? '') }}">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Notes</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ old('notes', $settings?->notes ?? '') }}</textarea>
            </div>

            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $settings?->is_active ?? true) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Enable PayPal payments</span>
            </label>

            <div class="pt-3 border-t">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Save PayPal Settings
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-3">Quick Links</h2>
            <div class="space-y-2 text-sm">
                <a href="/admin/payments/paypal" class="block text-blue-600 hover:underline">PayPal Payments</a>
                <a href="https://developer.paypal.com/dashboard/" target="_blank" class="block text-blue-600 hover:underline">PayPal Developer Dashboard ↗</a>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-lg p-5">
            <h2 class="text-base font-bold mb-2 text-yellow-800">Sandbox Testing</h2>
            <p class="text-sm text-yellow-900">Use Sandbox mode and test credentials during development. Switch to Live only in production.</p>
        </div>

        @if($settings)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-3">Current Config</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Mode</span>
                    <span class="font-semibold {{ $settings->environment === 'live' ? 'text-red-600' : 'text-green-600' }}">
                        {{ ucfirst($settings->environment) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Currency</span>
                    <span class="font-semibold">{{ $settings->currency }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status</span>
                    <span class="font-semibold {{ $settings->is_active ? 'text-green-600' : 'text-gray-500' }}">
                        {{ $settings->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Last Updated</span>
                    <span class="font-semibold text-xs">{{ $settings->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
