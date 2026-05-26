@extends('layouts.admin')

@section('title', 'Stripe Refunds')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">Stripe Refunds</h1>
        <p class="text-gray-600 mt-1">Track full and partial Stripe refunds</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/payments/stripe/export" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export CSV</a>
        <a href="/admin/payments/stripe" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-blue-700">₹{{ number_format($stats['total_refunded'], 2) }}</p>
        <p class="text-sm text-gray-600">Total Refunded</p>
    </div>
    <div class="bg-yellow-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</p>
        <p class="text-sm text-gray-600">Pending</p>
    </div>
    <div class="bg-green-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-green-700">{{ $stats['completed'] }}</p>
        <p class="text-sm text-gray-600">Completed</p>
    </div>
    <div class="bg-red-50 p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold text-red-700">{{ $stats['failed'] }}</p>
        <p class="text-sm text-gray-600">Failed</p>
    </div>
</div>

<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" class="flex-1 min-w-64 px-3 py-2 border rounded-lg" placeholder="Search refund ID or charge ID">

        <select name="status" class="px-3 py-2 border rounded-lg">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>

        <select name="type" class="px-3 py-2 border rounded-lg">
            <option value="">All Types</option>
            <option value="full" {{ request('type') === 'full' ? 'selected' : '' }}>Full</option>
            <option value="partial" {{ request('type') === 'partial' ? 'selected' : '' }}>Partial</option>
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Refund ID</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Payment</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Processed By</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($refunds as $refund)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm font-mono">{{ $refund->stripe_refund_id }}</td>
                    <td class="px-6 py-3 text-sm">
                        <a href="/admin/payments/{{ $refund->payment_id }}" class="text-blue-600 hover:underline">#{{ $refund->payment_id }}</a>
                    </td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $refund->type === 'full' ? 'bg-indigo-100 text-indigo-800' : 'bg-cyan-100 text-cyan-800' }}">
                            {{ ucfirst($refund->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm font-semibold">₹{{ number_format($refund->amount, 2) }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($refund->status === 'completed') bg-green-100 text-green-800
                            @elseif($refund->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($refund->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">{{ $refund->processedByUser?->name ?? 'System' }}</td>
                    <td class="px-6 py-3 text-sm">{{ $refund->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-3 text-sm">
                        <a href="/admin/payments/stripe/refunds/{{ $refund->id }}" class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-6 text-center text-gray-600">No refunds found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $refunds->links() }}
</div>
@endsection
