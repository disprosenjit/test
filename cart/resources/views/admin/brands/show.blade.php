@extends('layouts.admin')

@section('title', $brand->name . ' - Brand')

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">{{ $brand->name }}</h1>
        <p class="text-gray-600 mt-1">{{ $brand->slug }}</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/brands" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <a href="/admin/brands/{{ $brand->id }}/edit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Brand Details</h2>
            
            @if($brand->logo_url)
                <div class="mb-6">
                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-48 h-48 object-contain rounded-lg bg-gray-50 p-4">
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-600 text-sm">Name</p>
                    <p class="font-semibold">{{ $brand->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Slug</p>
                    <p class="font-mono font-semibold">{{ $brand->slug }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>
                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Total Products</p>
                    <p class="font-semibold">{{ $brand->product_count }}</p>
                </div>
            </div>

            @if($brand->description)
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <p class="text-gray-600 text-sm font-semibold mb-2">Description</p>
                    <p class="text-gray-700">{{ $brand->description }}</p>
                </div>
            @endif
        </div>

        <!-- Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Products from this Brand</h2>
                <a href="/admin/products/create?brand_id={{ $brand->id }}" class="text-blue-600 hover:underline text-sm">
                    <i class="fas fa-plus mr-1"></i>Add Product
                </a>
            </div>

            @if($brand->products()->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left px-4 py-2 font-semibold">Product Name</th>
                                <th class="text-left px-4 py-2 font-semibold">Category</th>
                                <th class="text-left px-4 py-2 font-semibold">SKU</th>
                                <th class="text-right px-4 py-2 font-semibold">Price</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($brand->products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        <a href="/admin/products/{{ $product->id }}" class="text-blue-600 hover:underline">
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-sm">{{ $product->category->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 font-mono text-gray-600">{{ $product->sku }}</td>
                                    <td class="px-4 py-2 text-right font-semibold">₹{{ number_format($product->price, 2) }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="/admin/products/{{ $product->id }}/edit" class="text-blue-600 hover:underline text-xs">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 py-6 text-center">No products from this brand yet</p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Info Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Brand Stats</h3>
            
            <div class="space-y-3">
                <div class="bg-blue-50 p-3 rounded">
                    <p class="text-blue-600 text-sm font-semibold">Total Products</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $brand->product_count }}</p>
                </div>

                <div>
                    <p class="text-gray-600 text-sm">Created</p>
                    <p class="font-semibold">{{ $brand->created_at->format('M d, Y') }}</p>
                </div>

                <div>
                    <p class="text-gray-600 text-sm">Last Updated</p>
                    <p class="font-semibold">{{ $brand->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
