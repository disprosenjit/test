@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Back to Customers
    </a>
    <h1 class="text-2xl font-bold">Customer Profile</h1>
    <p class="text-gray-600 mt-1">{{ $customer->name }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Info Card -->
    <div class="bg-white rounded-lg shadow p-6 lg:col-span-1">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $customer->name }}</h2>
                <span class="px-2 py-1 rounded text-xs font-semibold {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase">Email Address</label>
                <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $customer->email }}</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase">Phone Number</label>
                <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $customer->phone ?: 'Not provided' }}</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase">Member Since</label>
                <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $customer->created_at->format('F d, Y') }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t flex gap-3">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg font-semibold transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <button type="button"
                    onclick="openDeleteModal('delete-form-{{ $customer->id }}', 'Are you sure you want to delete this customer? This action cannot be undone.')"
                    class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg font-semibold border border-red-200 transition-colors duration-200">
                <i class="fas fa-trash mr-2"></i>Delete
            </button>
            <form id="delete-form-{{ $customer->id }}" method="POST" action="{{ route('admin.customers.destroy', $customer) }}" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- Addresses & Orders Columns -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Addresses -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>Registered Addresses</h3>
            @forelse($customer->addresses as $address)
                <div class="p-4 border rounded-lg hover:bg-gray-50 mb-3 last:mb-0">
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase {{ $address->type === 'billing' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $address->type }}
                        </span>
                        @if($address->is_default)
                            <span class="text-xs text-gray-500 font-semibold"><i class="fas fa-check-circle text-green-500 mr-1"></i>Default</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-800">{{ $address->address_line1 }}</p>
                    @if($address->address_line2)
                        <p class="text-sm text-gray-800">{{ $address->address_line2 }}</p>
                    @endif
                    <p class="text-sm text-gray-800">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                    <p class="text-sm text-gray-800 font-semibold mt-1">{{ $address->country }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No addresses registered.</p>
            @endforelse
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-shopping-bag text-gray-400 mr-2"></i>Order History</h3>
            @if($customer->orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Order ID</th>
                                <th class="px-4 py-2 text-left font-semibold">Date</th>
                                <th class="px-4 py-2 text-left font-semibold">Total</th>
                                <th class="px-4 py-2 text-left font-semibold">Status</th>
                                <th class="px-4 py-2 text-center font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-blue-600">
                                        <a href="/admin/orders/{{ $order->id }}" class="hover:underline">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 font-semibold">₹{{ number_format($order->total, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase 
                                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ !in_array($order->status, ['delivered', 'pending', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="/admin/orders/{{ $order->id }}" class="text-blue-600 hover:text-blue-800" title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm">No orders placed yet.</p>
            @endif
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
