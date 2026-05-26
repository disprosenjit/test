@extends('layouts.admin')

@section('title', 'Configure ' . $paymentMethod->name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">{{ $paymentMethod->name }}</h1>
        <p class="text-gray-600 mt-1">Configure payment method settings</p>
    </div>
    <a href="/admin/payment-methods" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Back
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
    <div class="lg:col-span-2">
        <!-- Method Information -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Method Information</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="text-gray-600 font-semibold">Name</label>
                    <div>{{ $paymentMethod->name }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Key</label>
                    <div class="font-mono">{{ $paymentMethod->key }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Type</label>
                    <div>{{ ucfirst($paymentMethod->type) }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Status</label>
                    <div class="inline-block px-2 py-1 text-xs rounded font-semibold {{ $paymentMethod->is_enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $paymentMethod->is_enabled ? 'Enabled' : 'Disabled' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Settings</h2>
            <form method="PUT" action="/admin/payment-methods/{{ $paymentMethod->id }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" class="w-full px-3 py-2 border rounded-lg"
                           value="{{ $paymentMethod->sort_order }}" min="0">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first at checkout</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ $paymentMethod->description }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Minimum Amount</label>
                        <input type="number" step="0.01" name="minimum_amount" class="w-full px-3 py-2 border rounded-lg"
                               value="{{ $paymentMethod->minimum_amount }}" placeholder="Optional">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Amount</label>
                        <input type="number" step="0.01" name="maximum_amount" class="w-full px-3 py-2 border rounded-lg"
                               value="{{ $paymentMethod->maximum_amount }}" placeholder="Optional">
                    </div>
                </div>

                <div class="pt-3 border-t">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Actions</h3>
            <div class="space-y-2">
                @if($paymentMethod->is_enabled)
                    <form method="POST" action="/admin/payment-methods/{{ $paymentMethod->id }}/toggle">
                        @csrf
                        <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 text-sm">
                            <i class="fas fa-power-off mr-2"></i>Disable Method
                        </button>
                    </form>
                @else
                    <form method="POST" action="/admin/payment-methods/{{ $paymentMethod->id }}/toggle">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                            <i class="fas fa-power-off mr-2"></i>Enable Method
                        </button>
                    </form>
                @endif

                @if($paymentMethod->is_enabled && !$paymentMethod->is_default)
                    <form method="POST" action="/admin/payment-methods/{{ $paymentMethod->id }}/default">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            <i class="fas fa-star mr-2"></i>Set as Default
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Method Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Details</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <label class="text-gray-600 font-semibold">Service Class</label>
                    <div class="font-mono text-xs break-all">{{ $paymentMethod->service_path ?? 'N/A' }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Controller Class</label>
                    <div class="font-mono text-xs break-all">{{ $paymentMethod->controller_path ?? 'N/A' }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Supports Refunds</label>
                    <div>{{ $methodInstance && $methodInstance->supportsRefunds() ? 'Yes' : 'No' }}</div>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
            <p class="text-sm text-blue-800">
                <i class="fas fa-info-circle mr-2"></i>
                Configure this payment method's limits and display settings. Enable or disable it to make it available or unavailable at checkout.
            </p>
        </div>
    </div>
</div>
@endsection
