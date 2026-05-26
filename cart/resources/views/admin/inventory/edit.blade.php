@extends('layouts.admin')

@section('title', 'Edit Inventory - ' . $inventory->product->name)

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">Edit Inventory</h1>
        <p class="text-gray-600 mt-1">{{ $inventory->product->name }} (SKU: {{ $inventory->product->sku }})</p>
    </div>
    <a href="/admin/inventory" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Edit Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-6">Update Stock Quantity</h2>

            <form method="POST" action="/admin/inventory/{{ $inventory->id }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Product Info -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-bold text-gray-900 mb-3">Product Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Product Name</p>
                            <p class="font-semibold">{{ $inventory->product->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">SKU</p>
                            <p class="font-mono font-semibold">{{ $inventory->product->sku }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Part Number</p>
                            <p class="font-mono font-semibold">{{ $inventory->product->part_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Price</p>
                            <p class="font-semibold">₹{{ number_format($inventory->product->price, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Current Stock Info -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h3 class="font-bold text-blue-900 mb-3">Current Stock</h3>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-blue-700 text-xs">Warehouse Qty</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $inventory->warehouse_qty }}</p>
                        </div>
                        <div>
                            <p class="text-blue-700 text-xs">Reserved Qty</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $inventory->reserved_qty ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-blue-700 text-xs">Available Qty</p>
                            <p class="text-2xl font-bold text-blue-600">{{ ($inventory->warehouse_qty - ($inventory->reserved_qty ?? 0)) }}</p>
                        </div>
                    </div>
                </div>

                <!-- New Stock Input -->
                <div>
                    <label class="block text-sm font-semibold mb-2">New Stock Quantity *</label>
                    <div class="flex items-end gap-4">
                        <div class="flex-1">
                            <input type="number" 
                                   name="warehouse_qty" 
                                   value="{{ $inventory->warehouse_qty }}" 
                                   required 
                                   min="0"
                                   class="w-full px-4 py-3 border rounded-lg text-lg font-semibold @error('warehouse_qty') border-red-500 @else border-gray-300 @enderror" />
                            @error('warehouse_qty')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="text-sm text-gray-600">
                            <p>Current: <span class="font-semibold">{{ $inventory->warehouse_qty }}</span></p>
                            <p>Difference: <span class="font-semibold" id="qty-diff">0</span></p>
                        </div>
                    </div>
                </div>

                <!-- Reason for Update -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Reason for Update</label>
                    <textarea name="reason" 
                              rows="3"
                              placeholder="e.g., Physical count adjustment, Damaged items, Return from order, Restock received..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Update Options -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-bold text-gray-900 mb-3">Update Options</h3>
                    <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg hover:bg-blue-50 cursor-pointer">
                        <input type="radio" name="action_type" value="set" checked class="w-4 h-4">
                        <div>
                            <p class="font-semibold text-sm">Set to exact quantity</p>
                            <p class="text-xs text-gray-600">Replace current quantity with entered value</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg hover:bg-green-50 cursor-pointer mt-2">
                        <input type="radio" name="action_type" value="add" class="w-4 h-4">
                        <div>
                            <p class="font-semibold text-sm">Add to current quantity</p>
                            <p class="text-xs text-gray-600">Add entered value to current stock</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg hover:bg-red-50 cursor-pointer mt-2">
                        <input type="radio" name="action_type" value="subtract" class="w-4 h-4">
                        <div>
                            <p class="font-semibold text-sm">Subtract from current quantity</p>
                            <p class="text-xs text-gray-600">Subtract entered value from current stock</p>
                        </div>
                    </label>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h4 class="font-bold text-red-800 mb-2">Update Failed</h4>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-semibold">
                        <i class="fas fa-save mr-2"></i>Update Stock
                    </button>
                    <a href="/admin/inventory" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded font-semibold text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stock History & Info -->
    <div>
        <!-- Stock Status -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Stock Status</h3>
            
            <div class="mb-4">
                @if($inventory->warehouse_qty === 0)
                    <span class="px-3 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">
                        Out of Stock
                    </span>
                @elseif($inventory->warehouse_qty < 10)
                    <span class="px-3 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">
                        Low Stock
                    </span>
                @else
                    <span class="px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                        In Stock
                    </span>
                @endif
            </div>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Total Warehouse</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $inventory->warehouse_qty }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Reserved for Orders</p>
                    <p class="text-lg font-semibold text-purple-600">{{ $inventory->reserved_qty ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Available to Sell</p>
                    <p class="text-lg font-semibold text-green-600">{{ ($inventory->warehouse_qty - ($inventory->reserved_qty ?? 0)) }}</p>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Product Details</h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Category</p>
                    <p class="font-semibold">{{ $inventory->product->category->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Brand</p>
                    <p class="font-semibold">{{ $inventory->product->brand->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Cost Price</p>
                    <p class="font-semibold">₹{{ number_format($inventory->product->cost, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Selling Price</p>
                    <p class="font-semibold">₹{{ number_format($inventory->product->price, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Stock Value</p>
                    <p class="text-lg font-bold">₹{{ number_format($inventory->warehouse_qty * $inventory->product->cost, 2) }}</p>
                </div>
            </div>

            <a href="/admin/products/{{ $inventory->product->id }}" class="block mt-4 text-blue-600 hover:underline text-sm font-semibold">
                View Product →
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            
            <div class="space-y-2">
                <button type="button" onclick="setStock(0)" class="w-full text-left px-3 py-2 rounded border border-gray-300 hover:bg-gray-50 text-sm">
                    <i class="fas fa-times mr-2"></i>Mark as Out of Stock
                </button>
                <button type="button" onclick="setStock(10)" class="w-full text-left px-3 py-2 rounded border border-gray-300 hover:bg-gray-50 text-sm">
                    <i class="fas fa-exclamation mr-2"></i>Set Low Stock (10)
                </button>
                <button type="button" onclick="setStock(100)" class="w-full text-left px-3 py-2 rounded border border-gray-300 hover:bg-gray-50 text-sm">
                    <i class="fas fa-check mr-2"></i>Set Standard (100)
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const currentQty = {{ $inventory->warehouse_qty }};
const qtyInput = document.querySelector('input[name="warehouse_qty"]');

function updateDifference() {
    const newQty = parseInt(qtyInput.value) || 0;
    const diff = newQty - currentQty;
    const diffEl = document.getElementById('qty-diff');
    diffEl.textContent = (diff > 0 ? '+' : '') + diff;
    diffEl.className = diff > 0 ? 'text-green-600 font-semibold' : (diff < 0 ? 'text-red-600 font-semibold' : 'font-semibold');
}

qtyInput.addEventListener('input', updateDifference);

function setStock(qty) {
    qtyInput.value = qty;
    updateDifference();
    document.querySelector('textarea[name="reason"]').focus();
}
</script>
@endsection
