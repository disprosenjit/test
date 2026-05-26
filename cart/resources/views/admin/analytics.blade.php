@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Analytics</h1>
    <form method="GET" class="flex gap-2">
        <select name="period" class="px-3 py-2 border rounded-lg">
            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Last 30 Days</option>
            <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>Last Quarter</option>
            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Last Year</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-filter mr-2"></i>Apply
        </button>
    </form>
</div>

<!-- Customer Analytics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm mb-1">Total Customers</p>
        <p class="text-3xl font-bold">{{ $customerData['total'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm mb-1">New This Period</p>
        <p class="text-3xl font-bold text-blue-600">{{ $customerData['new_this_period'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm mb-1">Repeat Customers</p>
        <p class="text-3xl font-bold text-green-600">{{ $customerData['repeat_customers'] }}</p>
    </div>
</div>

<!-- Sales Analytics -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-lg font-bold mb-4">Sales Analytics</h2>
    
    @if($salesData->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-2 font-semibold">Date</th>
                        <th class="text-right px-4 py-2 font-semibold">Orders</th>
                        <th class="text-right px-4 py-2 font-semibold">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($salesData as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-right font-semibold">{{ $data->orders }}</td>
                            <td class="px-4 py-2 text-right font-semibold">₹{{ number_format($data->revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 py-6 text-center">No sales data available for this period</p>
    @endif
</div>

<!-- Product Analytics -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-lg font-bold mb-4">Top Products</h2>
    
    @if($productData->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-2 font-semibold">Product Name</th>
                        <th class="text-right px-4 py-2 font-semibold">Views</th>
                        <th class="text-right px-4 py-2 font-semibold">Sold</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($productData as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $product->name }}</td>
                            <td class="px-4 py-2 text-right">{{ $product->view_count ?? 0 }}</td>
                            <td class="px-4 py-2 text-right font-semibold">{{ $product->sold ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 py-6 text-center">No product data available</p>
    @endif
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Sales Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Sales Summary</h3>
        
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <p class="text-gray-600">Total Orders</p>
                <p class="font-bold">{{ $salesData->sum('orders') }}</p>
            </div>
            <div class="flex justify-between items-center pb-3 border-b">
                <p class="text-gray-600">Total Revenue</p>
                <p class="font-bold text-green-600">₹{{ number_format($salesData->sum('revenue'), 2) }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-gray-600">Average Order Value</p>
                <p class="font-bold">
                    @if($salesData->sum('orders') > 0)
                        ₹{{ number_format($salesData->sum('revenue') / $salesData->sum('orders'), 2) }}
                    @else
                        ₹0.00
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Customer Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Customer Summary</h3>
        
        <div class="space-y-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <p class="text-gray-600">Total Customers</p>
                <p class="font-bold">{{ $customerData['total'] }}</p>
            </div>
            <div class="flex justify-between items-center pb-3 border-b">
                <p class="text-gray-600">New Customers</p>
                <p class="font-bold text-blue-600">{{ $customerData['new_this_period'] }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-gray-600">Repeat Rate</p>
                <p class="font-bold text-green-600">
                    @if($customerData['total'] > 0)
                        {{ number_format(($customerData['repeat_customers'] / $customerData['total']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
