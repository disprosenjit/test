@extends('layouts.admin')

@section('title', 'Stripe Refund Details')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">Refund Details</h1>
        <p class="text-gray-600 mt-1">Refund {{ $refund->stripe_refund_id }}</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/payments/{{ $refund->payment_id }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Payment</a>
        <a href="/admin/payments/stripe/refunds" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Refund Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Stripe Refund ID</p>
                    <p class="font-mono font-semibold text-sm break-all">{{ $refund->stripe_refund_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Stripe Charge ID</p>
                    <p class="font-mono font-semibold text-sm break-all">{{ $refund->stripe_charge_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payment ID</p>
                    <a href="/admin/payments/{{ $refund->payment_id }}" class="font-semibold text-blue-600 hover:underline">#{{ $refund->payment_id }}</a>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Order ID</p>
                    <a href="/admin/orders/{{ $refund->payment?->order_id }}" class="font-semibold text-blue-600 hover:underline">#{{ $refund->payment?->order_id }}</a>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Type</p>
                    <p class="font-semibold">{{ ucfirst($refund->type) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Amount</p>
                    <p class="font-semibold text-green-700 text-xl">₹{{ number_format($refund->amount, 2) }}</p>
                </div>
            </div>

            @if($refund->reason)
                <div class="mt-5 pt-5 border-t">
                    <p class="text-sm text-gray-600">Reason</p>
                    <p class="text-gray-800">{{ $refund->reason }}</p>
                </div>
            @endif

            @if($refund->notes)
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Notes</p>
                    <p class="text-gray-800">{{ $refund->notes }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Stripe Response</h2>
            @php
                $response = is_array($refund->stripe_response)
                    ? $refund->stripe_response
                    : json_decode($refund->stripe_response ?? '{}', true);
            @endphp
            <pre class="bg-gray-900 text-gray-100 p-4 rounded overflow-x-auto text-xs">{{ json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Status</h3>
            <span class="px-3 py-1 rounded text-sm font-semibold
                @if($refund->status === 'completed') bg-green-100 text-green-800
                @elseif($refund->status === 'pending') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($refund->status) }}
            </span>

            <div class="mt-4 space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Processed By</p>
                    <p class="font-semibold">{{ $refund->processedByUser?->name ?? 'System' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Processed At</p>
                    <p class="font-semibold">{{ $refund->processed_at ? $refund->processed_at->format('M d, Y H:i') : 'Not completed yet' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Created At</p>
                    <p class="font-semibold">{{ $refund->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Customer</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-600">Name:</span> {{ $refund->payment?->order?->user?->name ?? 'N/A' }}</p>
                <p><span class="text-gray-600">Email:</span> {{ $refund->payment?->order?->user?->email ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
