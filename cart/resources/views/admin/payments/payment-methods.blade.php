@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">Payment Methods</h1>
        <p class="text-gray-600 mt-1">Configure and manage available payment methods</p>
    </div>
    <a href="/admin/payments" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Back to Payments
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800">{{ session('error') }}</div>
@endif

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-gray-600 text-sm">Total Methods</div>
        <div class="text-2xl font-bold mt-2">{{ $paymentMethods->count() }}</div>
    </div>
    <div class="bg-green-50 rounded-lg shadow p-4">
        <div class="text-green-700 font-semibold text-sm">Enabled</div>
        <div class="text-2xl font-bold text-green-700 mt-2">{{ $paymentMethods->where('is_enabled', true)->count() }}</div>
    </div>
    <div class="bg-blue-50 rounded-lg shadow p-4">
        <div class="text-blue-700 font-semibold text-sm">Default</div>
        <div class="text-xl font-bold text-blue-700 mt-2">{{ $paymentMethods->where('is_default', true)->first()?->name ?? 'None' }}</div>
    </div>
</div>

<!-- Payment Methods Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Method</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                <th class="px-6 py-3 text-center text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-center text-sm font-semibold">Default</th>
                <th class="px-6 py-3 text-center text-sm font-semibold">Order</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Description</th>
                <th class="px-6 py-3 text-center text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($paymentMethods as $method)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <i class="{{ $method->icon_class }} text-xl text-gray-500"></i>
                        <div>
                            <div class="font-semibold">{{ $method->name }}</div>
                            <div class="text-xs text-gray-500">{{ $method->key }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 text-xs rounded bg-gray-100">{{ ucfirst($method->type) }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($method->is_enabled)
                        <span class="px-2 py-1 text-xs rounded font-semibold bg-green-100 text-green-800">Enabled</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded font-semibold bg-gray-100 text-gray-800">Disabled</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    @if($method->is_default)
                        <span class="text-blue-600 font-bold"><i class="fas fa-star"></i></span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center font-mono text-sm">{{ $method->sort_order }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $method->description }}</td>
                <td class="px-6 py-4 text-center text-sm space-x-2">
                    <a href="/admin/payment-methods/{{ $method->id }}" class="text-blue-600 hover:underline">Configure</a>
                    @if($method->is_enabled)
                        <form method="POST" action="/admin/payment-methods/{{ $method->id }}/toggle" class="inline">
                            @csrf
                            <button type="submit" class="text-orange-600 hover:underline" title="Disable">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </form>
                    @else
                        <form method="POST" action="/admin/payment-methods/{{ $method->id }}/toggle" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:underline" title="Enable">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </form>
                    @endif
                    @if($method->is_enabled && !$method->is_default)
                        <form method="POST" action="/admin/payment-methods/{{ $method->id }}/default" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:underline" title="Set as default">
                                <i class="fas fa-star"></i>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">No payment methods found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-200">
    <h3 class="font-bold text-blue-900 mb-2"><i class="fas fa-info-circle mr-2"></i>About Payment Methods</h3>
    <ul class="text-sm text-blue-800 space-y-1">
        <li>• Enable or disable payment methods for your customers</li>
        <li>• Set one method as default (recommended Stripe)</li>
        <li>• Reorder methods by changing the sort order</li>
        <li>• Configure method-specific settings in the details page</li>
        <li>• Only enabled methods will appear at checkout</li>
    </ul>
</div>
@endsection
