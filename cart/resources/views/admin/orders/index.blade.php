@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Orders</h1>
    <a href="/admin/orders/export" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        <i class="fas fa-download mr-2"></i>Export CSV
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <a href="/admin/orders" class="bg-white p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        <p class="text-sm text-gray-600">Total Orders</p>
    </a>
    <a href="/admin/orders?status=pending" class="bg-yellow-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        <p class="text-sm text-gray-600">Pending</p>
    </a>
    <a href="/admin/orders?status=processing" class="bg-blue-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-blue-600">{{ $stats['processing'] }}</p>
        <p class="text-sm text-gray-600">Processing</p>
    </a>
    <a href="/admin/orders?status=shipped" class="bg-purple-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-purple-600">{{ $stats['shipped'] }}</p>
        <p class="text-sm text-gray-600">Shipped</p>
    </a>
    <a href="/admin/orders?status=cancelled" class="bg-red-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
        <p class="text-sm text-gray-600">Cancelled</p>
    </a>
</div>

<!-- Search & Filter -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Search order ID or customer..." class="flex-1 px-3 py-2 border rounded-lg" value="{{ request('search') }}" />
        <select name="status" class="px-3 py-2 border rounded-lg">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filter
        </button>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Order ID</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Customer</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Total</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Payment</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm font-semibold">
                        <a href="/admin/orders/{{ $order->id }}" class="text-blue-600 hover:underline">#{{ $order->id }}</a>
                    </td>
                    <td class="px-6 py-3 text-sm">{{ $order->user->name }}</td>
                    <td class="px-6 py-3 text-sm font-semibold">₹{{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->payment?->status === 'approved') bg-green-100 text-green-800
                            @elseif($order->payment?->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment?->status ?? 'unpaid') }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                            @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-3 text-sm text-center">
                        <x-crud-actions
                            :viewRoute="route('admin.orders.show', $order)"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-3 text-center text-gray-600">No orders found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $orders->links() }}
</div>

{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
