@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Users Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Users</p>
                <p class="text-3xl font-bold">{{ $metrics['total_users'] }}</p>
            </div>
            <i class="fas fa-users text-4xl text-blue-200"></i>
        </div>
        <p class="text-xs text-gray-600 mt-2">{{ $metrics['active_users'] }} active</p>
    </div>

    <!-- Total Products Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Products</p>
                <p class="text-3xl font-bold">{{ $metrics['total_products'] }}</p>
            </div>
            <i class="fas fa-box text-4xl text-green-200"></i>
        </div>
        <p class="text-xs text-gray-600 mt-2">{{ $metrics['low_stock_products'] }} low stock</p>
    </div>

    <!-- Revenue Today Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Revenue Today</p>
                <p class="text-3xl font-bold">₹{{ number_format($revenue['today'], 0) }}</p>
            </div>
            <i class="fas fa-chart-line text-4xl text-yellow-200"></i>
        </div>
        <p class="text-xs text-gray-600 mt-2">₹{{ number_format($revenue['this_month'], 0) }} this month</p>
    </div>

    <!-- Orders Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Orders</p>
                <p class="text-3xl font-bold">{{ $orders['total'] }}</p>
            </div>
            <i class="fas fa-shopping-bag text-4xl text-purple-200"></i>
        </div>
        <p class="text-xs text-gray-600 mt-2">{{ $orders['pending'] }} pending</p>
    </div>
</div>

<!-- Order Status Overview -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Order Status Chart -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Order Status Distribution</h3>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Pending</span>
                    <span>{{ $orders['pending'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded h-2">
                    <div class="bg-yellow-500 h-2 rounded" style="width: {{ $orders['total'] > 0 ? ($orders['pending'] / $orders['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Processing</span>
                    <span>{{ $orders['processing'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded h-2">
                    <div class="bg-blue-500 h-2 rounded" style="width: {{ $orders['total'] > 0 ? ($orders['processing'] / $orders['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Shipped</span>
                    <span>{{ $orders['shipped'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded h-2">
                    <div class="bg-purple-500 h-2 rounded" style="width: {{ $orders['total'] > 0 ? ($orders['shipped'] / $orders['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Delivered</span>
                    <span>{{ $orders['delivered'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded h-2">
                    <div class="bg-green-500 h-2 rounded" style="width: {{ $orders['total'] > 0 ? ($orders['delivered'] / $orders['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span>Cancelled</span>
                    <span>{{ $orders['cancelled'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded h-2">
                    <div class="bg-red-500 h-2 rounded" style="width: {{ $orders['total'] > 0 ? ($orders['cancelled'] / $orders['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Payment Status</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                <span class="text-sm">Total Approved</span>
                <span class="font-bold text-green-600">₹{{ number_format($payments['total_received'], 0) }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                <span class="text-sm">Pending Verification</span>
                <span class="font-bold text-yellow-600">{{ $payments['pending'] }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-red-50 rounded">
                <span class="text-sm">Rejected</span>
                <span class="font-bold text-red-600">{{ $payments['rejected'] }}</span>
            </div>
            <a href="/admin/payments?status=pending" class="inline-block mt-2 text-blue-600 hover:underline text-sm">
                Review pending payments →
            </a>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b">
                    <tr>
                        <th class="text-left py-2">Order ID</th>
                        <th class="text-left py-2">Customer</th>
                        <th class="text-left py-2">Total</th>
                        <th class="text-left py-2">Status</th>
                        <th class="text-left py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">
                                <a href="/admin/orders/{{ $order->id }}" class="text-blue-600 hover:underline">#{{ $order->id }}</a>
                            </td>
                            <td class="py-3">{{ $order->user->name }}</td>
                            <td class="py-3">₹{{ number_format($order->total, 2) }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-3">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="/admin/orders" class="inline-block mt-4 text-blue-600 hover:underline text-sm">
            View all orders →
        </a>
    </div>

    <!-- Top Products -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Top Products</h3>
        <div class="space-y-3">
            @foreach($topProducts as $product)
                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-600">SKU: {{ $product->sku }}</p>
                    </div>
                    <span class="ml-2 text-sm font-bold">{{ $product->order_items_count }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
    <a href="/admin/products/create" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 text-center">
        <i class="fas fa-plus text-blue-600 text-2xl mb-2"></i>
        <p class="text-sm font-semibold text-gray-800">Add Product</p>
    </a>
    <a href="/admin/payments?status=pending" class="p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 text-center">
        <i class="fas fa-hourglass text-yellow-600 text-2xl mb-2"></i>
        <p class="text-sm font-semibold text-gray-800">Verify Payments</p>
    </a>
    <a href="/admin/inventory/alerts" class="p-4 bg-red-50 rounded-lg hover:bg-red-100 text-center">
        <i class="fas fa-exclamation text-red-600 text-2xl mb-2"></i>
        <p class="text-sm font-semibold text-gray-800">Low Stock</p>
    </a>
    <a href="/admin/analytics" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 text-center">
        <i class="fas fa-chart-bar text-purple-600 text-2xl mb-2"></i>
        <p class="text-sm font-semibold text-gray-800">Analytics</p>
    </a>
</div>
@endsection
