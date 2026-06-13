@extends('layouts.admin')

@section('title', 'Customers Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Customers</h1>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <a href="/admin/customers" class="bg-white p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        <p class="text-sm text-gray-600">Total Customers</p>
    </a>
    <a href="/admin/customers?status=active" class="bg-green-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        <p class="text-sm text-gray-600">Active</p>
    </a>
    <a href="/admin/customers?status=inactive" class="bg-gray-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-gray-600">{{ $stats['inactive'] }}</p>
        <p class="text-sm text-gray-600">Inactive</p>
    </a>
</div>

<!-- Search & Filter -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Search customer name, email or phone..." class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}" />
        <select name="status" class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold transition-colors duration-200">
            Filter
        </button>
    </form>
</div>

<!-- Customers Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Phone</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Joined Date</th>
                <th class="px-6 py-3 text-center text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm font-semibold text-gray-900">
                        <a href="/admin/customers/{{ $customer->id }}" class="text-blue-600 hover:underline">
                            {{ $customer->name }}
                        </a>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $customer->email }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $customer->phone ?: 'N/A' }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $customer->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $customer->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-3 text-sm text-center">
                        <x-crud-actions
                            :viewRoute="route('admin.customers.show', $customer)"
                            :editRoute="route('admin.customers.edit', $customer)"
                            :deleteRoute="route('admin.customers.destroy', $customer)"
                            deleteMessage="Are you sure you want to delete this customer? This action cannot be undone."
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-3 text-center text-gray-600">No customers found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $customers->links() }}
</div>

{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
