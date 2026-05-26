@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Products</h1>
    <div class="flex gap-2">
        <a href="/admin/products/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Add Product
        </a>
        <a href="/admin/products/bulk-upload" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-upload mr-2"></i>Bulk Upload
        </a>
    </div>
</div>

<!-- Search & Filter -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Search products..." class="flex-1 px-3 py-2 border rounded-lg" value="{{ request('search') }}" />
        <select name="brand_id" class="px-3 py-2 border rounded-lg">
            <option value="">All Brands</option>
            {{-- Add brand options --}}
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filter
        </button>
    </form>
</div>

<!-- Products Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">SKU</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Price</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Stock</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm">{{ $product->sku }}</td>
                    <td class="px-6 py-3 text-sm">{{ $product->name }}</td>
                    <td class="px-6 py-3 text-sm">₹{{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="{{ ($product->inventory?->warehouse_qty ?? 0) > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->inventory?->warehouse_qty ?? 0 }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">
                        @if($product->is_active)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-sm text-center">
                        <x-crud-actions
                            :viewRoute="route('admin.products.show', $product)"
                            :editRoute="route('admin.products.edit', $product)"
                            :deleteRoute="route('admin.products.destroy', $product)"
                            deleteMessage="Are you sure you want to delete this product? This action cannot be undone."
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-3 text-center text-gray-600">No products found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $products->links() }}
</div>

{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
