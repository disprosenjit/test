@extends('layouts.admin')

@section('title', 'Low Stock Alerts')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Stock Alerts</h1>
    <p class="text-gray-600 mt-1">Products with low stock or out of stock</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600">{{ count($lowStockProducts) }}</p>
        <p class="text-sm text-yellow-800">Low Stock (1-9 units)</p>
    </div>
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-red-600">{{ count($outOfStockProducts) }}</p>
        <p class="text-sm text-red-800">Out of Stock</p>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ count($lowStockProducts) + count($outOfStockProducts) }}</p>
        <p class="text-sm text-green-800">Total Alerts</p>
    </div>
</div>

<div class="space-y-6">
    <!-- Low Stock Products -->
    @if(count($lowStockProducts) > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-3">
                <h2 class="text-lg font-bold text-yellow-900">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Products
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">SKU</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Product Name</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold">Current Stock</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold">Reserved</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold">Available</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($lowStockProducts as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm font-mono">{{ $item->product->sku }}</td>
                                <td class="px-6 py-3 text-sm">
                                    <a href="/admin/products/{{ $item->product_id }}" class="text-blue-600 hover:underline">
                                        {{ $item->product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-3 text-sm text-right">
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold">
                                        {{ $item->warehouse_qty }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-right">{{ $item->reserved_qty }}</td>
                                <td class="px-6 py-3 text-sm text-right font-semibold">{{ $item->available_qty }}</td>
                                <td class="px-6 py-3 text-sm">
                                    <a href="/admin/inventory" class="text-blue-600 hover:underline">
                                        Restock
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Out of Stock Products -->
    @if(count($outOfStockProducts) > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-red-50 border-b border-red-200 px-6 py-3">
                <h2 class="text-lg font-bold text-red-900">
                    <i class="fas fa-times-circle mr-2"></i>Out of Stock Products
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">SKU</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Product Name</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold">Current Stock</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($outOfStockProducts as $item)
                            <tr class="hover:bg-gray-50 bg-red-50">
                                <td class="px-6 py-3 text-sm font-mono">{{ $item->product->sku }}</td>
                                <td class="px-6 py-3 text-sm">
                                    <a href="/admin/products/{{ $item->product_id }}" class="text-blue-600 hover:underline">
                                        {{ $item->product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-3 text-sm text-right">
                                    <span class="inline-block bg-red-200 text-red-800 px-2 py-1 rounded font-semibold">
                                        0
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <a href="/admin/inventory" class="text-blue-600 hover:underline font-semibold">
                                        Restock Now
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-green-50 border border-green-200 rounded-lg p-8 text-center">
            <i class="fas fa-check-circle text-4xl text-green-600 mb-4"></i>
            <h3 class="text-lg font-bold text-green-900">All Products In Stock</h3>
            <p class="text-green-800 mt-2">No low stock alerts at this time</p>
        </div>
    @endif
</div>

<div class="mt-6">
    <a href="/admin/inventory" class="text-blue-600 hover:underline">
        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
    </a>
</div>
@endsection
