@extends('layouts.admin')

@section('title', 'Brands Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Brands</h1>
    <a href="/admin/brands/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Add Brand
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <a href="/admin/brands" class="bg-white p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        <p class="text-sm text-gray-600">Total Brands</p>
    </a>
    <a href="/admin/brands?status=active" class="bg-green-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        <p class="text-sm text-gray-600">Active</p>
    </a>
    <a href="/admin/brands?status=inactive" class="bg-gray-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-gray-600">{{ $stats['inactive'] }}</p>
        <p class="text-sm text-gray-600">Inactive</p>
    </a>
</div>

<!-- Search & Filter -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Search brand name..." class="flex-1 px-3 py-2 border rounded-lg" value="{{ request('search') }}" />
        <select name="status" class="px-3 py-2 border rounded-lg">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filter
        </button>
    </form>
</div>

<!-- Brands Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Slug</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Products</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($brands as $brand)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm font-semibold">
                        <div class="flex items-center gap-3">
                            @if($brand->logo_url)
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-8 h-8 rounded object-cover">
                            @else
                                <div class="w-8 h-8 rounded bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-trademark text-gray-400 text-xs"></i>
                                </div>
                            @endif
                            <a href="/admin/brands/{{ $brand->id }}" class="text-blue-600 hover:underline">
                                {{ $brand->name }}
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-sm font-mono text-gray-600">{{ $brand->slug }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $brand->products()->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $brand->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm text-center">
                        <x-crud-actions
                            :editRoute="route('admin.brands.edit', $brand)"
                            :deleteRoute="route('admin.brands.destroy', $brand)"
                            deleteMessage="Are you sure you want to delete this brand? Products associated with this brand will not be deleted but the brand reference will be removed."
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-3 text-center text-gray-600">No brands found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $brands->links() }}
</div>

{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
