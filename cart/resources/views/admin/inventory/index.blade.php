@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Inventory</h1>
    <div class="flex gap-2">
        <a href="/admin/inventory/alerts" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            <i class="fas fa-exclamation mr-2"></i>Low Stock Alerts
        </a>
        <a href="/admin/inventory/export" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-download mr-2"></i>Export Report
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold">{{ $stats['total_products'] }}</p>
        <p class="text-sm text-gray-600">Total Products</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow text-center">
        <p class="text-2xl font-bold">{{ $stats['total_stock'] }}</p>
        <p class="text-sm text-gray-600">Total Stock Units</p>
    </div>
    <a href="?stock_status=low" class="bg-yellow-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['low_stock'] }}</p>
        <p class="text-sm text-gray-600">Low Stock</p>
    </a>
    <a href="?stock_status=out" class="bg-red-50 p-4 rounded-lg shadow text-center hover:shadow-lg">
        <p class="text-2xl font-bold text-red-600">{{ $stats['out_of_stock'] }}</p>
        <p class="text-sm text-gray-600">Out of Stock</p>
    </a>
</div>

<!-- Search & Filter -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Search SKU or product name..." class="flex-1 px-3 py-2 border rounded-lg" value="{{ request('search') }}" />
        <select name="stock_status" class="px-3 py-2 border rounded-lg">
            <option value="">All Stock</option>
            <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock (&lt;10)</option>
            <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filter
        </button>
    </form>
</div>

<!-- Inventory Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">SKU</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Product Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Stock Qty</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Reserved</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Available</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Price</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($inventory as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm font-semibold">{{ $item->product->sku }}</td>
                    <td class="px-6 py-3 text-sm">{{ $item->product->name }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $item->warehouse_qty }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm">{{ $item->reserved_qty ?? 0 }}</td>
                    <td class="px-6 py-3 text-sm font-semibold">{{ $item->available_qty ?? 0 }}</td>
                    <td class="px-6 py-3 text-sm">₹{{ number_format($item->product->price, 2) }}</td>
                    <td class="px-6 py-3 text-sm">
                        @if($item->warehouse_qty === 0)
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Out of Stock</span>
                        @elseif($item->warehouse_qty < 10)
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">Low Stock</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">In Stock</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-sm text-center">
                        <div class="flex items-center justify-center gap-3">
                            <x-crud-actions
                                :editRoute="route('admin.inventory.edit', $item)"
                            />
                            <button type="button"
                                    onclick="openRestockModal({{ $item->id }})"
                                    class="text-green-600 hover:text-green-800 hover:bg-green-50 p-2 rounded-md transition-colors duration-200"
                                    title="Restock">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-3 text-center text-gray-600">No inventory items found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $inventory->links() }}
</div>

<!-- Restock Modal -->
<div id="restock-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">Restock Product</h3>
        <form id="restock-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Quantity</label>
                <input type="number" name="quantity" required min="1" class="w-full px-3 py-2 border rounded-lg" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Supplier (Optional)</label>
                <input type="text" name="supplier" class="w-full px-3 py-2 border rounded-lg" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Cost (Optional)</label>
                <input type="number" name="cost" step="0.01" class="w-full px-3 py-2 border rounded-lg" />
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Restock</button>
                <button type="button" onclick="closeRestockModal()" class="flex-1 bg-gray-200 text-gray-800 py-2 rounded hover:bg-gray-300">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRestockModal(inventoryId) {
    const form = document.getElementById('restock-form');
    form.action = '/admin/inventory/' + inventoryId + '/restock';
    document.getElementById('restock-modal').classList.remove('hidden');
}

function closeRestockModal() {
    document.getElementById('restock-modal').classList.add('hidden');
}
</script>

{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
