@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
        <p class="text-gray-600 mt-1">SKU: <span class="font-mono">{{ $product->sku }}</span></p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/products/{{ $product->id }}/edit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <form method="POST" action="/admin/products/{{ $product->id }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                <i class="fas fa-trash mr-2"></i>Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Images -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Product Images</h2>
            
            @php
                $images = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?? []);
            @endphp

            @if(count($images) > 0)
                <div class="space-y-4">
                    <div class="bg-gray-100 rounded-lg h-96 flex items-center justify-center overflow-hidden">
                        <img id="mainImage" src="{{ $images[0] }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                    
                    @if(count($images) > 1)
                        <div class="flex gap-2 overflow-x-auto">
                            @foreach($images as $index => $image)
                                <button type="button" onclick="document.getElementById('mainImage').src='{{ $image }}'" class="flex-shrink-0">
                                    <img src="{{ $image }}" alt="Thumbnail" class="w-20 h-20 object-cover rounded border-2 border-gray-300 hover:border-blue-600">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <p>No images available</p>
                </div>
            @endif
        </div>

        <!-- Details -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-lg font-bold mb-4">Product Details</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Part Number</p>
                    <p class="font-semibold">{{ $product->part_number }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Brand</p>
                    <p class="font-semibold">{{ $product->brand->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Category</p>
                    <p class="font-semibold">{{ $product->category->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Product Type</p>
                    <p class="font-semibold">{{ ucfirst($product->product_type ?? 'physical') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Vessel Type</p>
                    <p class="font-semibold">{{ $product->vesselType->name ?? 'General' }}</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t">
                <h3 class="font-bold mb-2">Description</h3>
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>

            @php
                $specs = is_array($product->specifications) ? $product->specifications : (json_decode($product->specifications, true) ?? []);
            @endphp
            
            @if(count($specs) > 0)
                <div class="mt-4 pt-4 border-t">
                    <h3 class="font-bold mb-2">Specifications</h3>
                    <dl class="space-y-2">
                        @foreach($specs as $key => $value)
                            <div class="flex justify-between">
                                <dt class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                <dd class="font-semibold">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif
        </div>
    </div>

    <!-- Pricing & Inventory -->
    <div>
        <!-- Pricing Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Pricing</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm">Cost Price</p>
                    <p class="text-2xl font-bold">₹{{ number_format($product->cost, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Sale Price</p>
                    <p class="text-2xl font-bold text-green-600">₹{{ number_format($product->price, 2) }}</p>
                </div>
                <div class="pt-3 border-t">
                    <p class="text-gray-600 text-sm">Profit Margin</p>
                    <p class="text-xl font-bold">{{ $product->cost > 0 ? round((($product->price - $product->cost) / $product->cost) * 100, 1) : 0 }}%</p>
                </div>
            </div>
        </div>

        @if($product->isDownloadable())
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Download Asset</h3>

                @if($product->hasDownloadFile())
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">Filename</p>
                            <p class="font-semibold break-all">{{ $product->getDownloadName() }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">MIME Type</p>
                            <p class="font-semibold">{{ $product->download_file_mime_type }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">File Size</p>
                            <p class="font-semibold">{{ number_format(($product->download_file_size ?? 0) / 1024, 1) }} KB</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No downloadable file uploaded yet.</p>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Inventory</h3>
                
                @php
                    $inventory = $product->inventory;
                @endphp

                @if($inventory)
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-600 text-sm">Warehouse Qty</p>
                            <p class="text-2xl font-bold">{{ $inventory->warehouse_qty }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Reserved Qty</p>
                            <p class="text-xl font-semibold text-yellow-600">{{ $inventory->reserved_qty }}</p>
                        </div>
                        <div class="pt-3 border-t">
                            <p class="text-gray-600 text-sm">Available Qty</p>
                            <p class="text-2xl font-bold {{ $inventory->available_qty > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $inventory->available_qty }}
                            </p>
                        </div>
                    </div>

                    @if($inventory->available_qty <= 10)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-yellow-800 text-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Low stock alert
                        </div>
                    @endif
                @else
                    <p class="text-gray-500">No inventory record</p>
                @endif
            </div>
        @endif

        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Status</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm">Active</p>
                    <div class="mt-1">
                        @if($product->is_active)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-semibold">Active</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded text-sm font-semibold">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="pt-3 border-t">
                    <p class="text-gray-600 text-sm">View Count</p>
                    <p class="text-2xl font-bold">{{ $product->view_count ?? 0 }}</p>
                </div>

                <div class="pt-3 border-t">
                    <p class="text-gray-600 text-sm">Created</p>
                    <p class="text-sm">{{ $product->created_at->format('M d, Y') }}</p>
                </div>

                <div>
                    <p class="text-gray-600 text-sm">Updated</p>
                    <p class="text-sm">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
