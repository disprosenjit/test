@extends('layouts.admin')

@section('title', 'PayPal Payments')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">PayPal Payments</h1>
        <p class="text-gray-600 mt-1">Gateway-specific monitoring for PayPal transactions</p>
    </div>
    <a href="/admin/payments" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>All Payments
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-indigo-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-indigo-700">₹{{ number_format($stats['total'], 0) }}</p>
        <p class="text-sm text-gray-600">PayPal Volume</p>
    </div>
    <div class="bg-yellow-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</p>
        <p class="text-sm text-gray-600">Pending</p>
    </div>
    <div class="bg-green-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-green-700">{{ $stats['approved'] }}</p>
        <p class="text-sm text-gray-600">Approved</p>
    </div>
    <div class="bg-red-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-red-700">{{ $stats['failed'] }}</p>
        <p class="text-sm text-gray-600">Failed</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Payment ID</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Order</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Customer</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Reference</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm font-semibold">#{{ $payment->id }}</td>
                    <td class="px-6 py-3 text-sm">#{{ $payment->order_id }}</td>
                    <td class="px-6 py-3 text-sm">{{ $payment->order?->user?->name ?? 'N/A' }}</td>
                    <td class="px-6 py-3 text-sm font-semibold">₹{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-3 text-sm font-mono">{{ $payment->transaction_reference ?? 'N/A' }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($payment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status === 'approved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">
                        <a href="/admin/payments/{{ $payment->id }}" class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-3 text-center text-gray-600">No PayPal payments found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $payments->links() }}
</div>
@endsection
