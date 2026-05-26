@extends('layouts.admin')

@section('title', 'PayPal IPN Dashboard')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">PayPal IPN Dashboard</h1>
        <p class="text-gray-600 mt-1">Real-time monitoring of PayPal Instant Payment Notifications</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/paypal-ipn" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            <i class="fas fa-list mr-2"></i>View All Logs
        </a>
    </div>
</div>

<!-- Date Range Filter -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex gap-3 items-end">
        <div class="flex-1">
            <label class="block text-sm font-semibold mb-2">From Date</label>
            <input type="date" name="from" class="px-3 py-2 border rounded-lg w-full" value="{{ $from }}">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-semibold mb-2">To Date</label>
            <input type="date" name="to" class="px-3 py-2 border rounded-lg w-full" value="{{ $to }}">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Apply
        </button>
    </form>
</div>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-gray-600 text-sm">Total IPNs</div>
        <div class="text-3xl font-bold mt-2">{{ $stats['total_ipns'] }}</div>
    </div>
    <div class="bg-green-50 rounded-lg shadow p-6">
        <div class="text-green-700 font-semibold text-sm">Verified</div>
        <div class="text-3xl font-bold text-green-700 mt-2">{{ $stats['verified'] }}</div>
        <div class="text-xs text-green-600 mt-2">{{ round($stats['verified'] / max($stats['total_ipns'], 1) * 100) }}% verified</div>
    </div>
    <div class="bg-yellow-50 rounded-lg shadow p-6">
        <div class="text-yellow-700 font-semibold text-sm">Processed</div>
        <div class="text-3xl font-bold text-yellow-700 mt-2">{{ $stats['processed'] }}</div>
        <div class="text-xs text-yellow-600 mt-2">{{ round($stats['processed'] / max($stats['total_ipns'], 1) * 100) }}% processed</div>
    </div>
    <div class="bg-blue-50 rounded-lg shadow p-6">
        <div class="text-blue-700 font-semibold text-sm">Completed</div>
        <div class="text-3xl font-bold text-blue-700 mt-2">{{ $stats['completed'] }}</div>
        <div class="text-xs text-blue-600 mt-2">Total: {{ $stats['total_amount'] ? number_format($stats['total_amount'], 2) : '0.00' }}</div>
    </div>
    <div class="bg-red-50 rounded-lg shadow p-6">
        <div class="text-red-700 font-semibold text-sm">Failed</div>
        <div class="text-3xl font-bold text-red-700 mt-2">{{ $stats['failed'] }}</div>
        <div class="text-xs text-red-600 mt-2">Requires attention</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Transaction Types -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">By Transaction Type</h2>
        <div class="space-y-2">
            @forelse($byType as $type)
            <div class="flex justify-between items-center p-2 border rounded">
                <span class="text-sm">{{ $type->txn_type }}</span>
                <span class="font-bold bg-gray-100 px-2 py-1 rounded">{{ $type->count }}</span>
            </div>
            @empty
            <p class="text-gray-500 text-sm">No data</p>
            @endforelse
        </div>
    </div>

    <!-- Payment Status -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">By Payment Status</h2>
        <div class="space-y-2">
            @forelse($byStatus as $status)
            <div class="flex justify-between items-center p-2 border rounded">
                <span class="text-sm">{{ $status->payment_status }}</span>
                <span class="font-bold bg-gray-100 px-2 py-1 rounded">{{ $status->count }}</span>
            </div>
            @empty
            <p class="text-gray-500 text-sm">No data</p>
            @endforelse
        </div>
    </div>

    <!-- Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Summary</h2>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Unverified IPNs</span>
                <span class="font-bold text-orange-600">{{ $stats['unverified'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Unprocessed IPNs</span>
                <span class="font-bold text-orange-600">{{ $stats['unprocessed'] }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Refunded</span>
                <span class="font-bold text-blue-600">{{ $stats['refunded'] }}</span>
            </div>
            <div class="border-t pt-3 flex justify-between text-base font-bold">
                <span>Total Amount</span>
                <span class="text-green-600">USD {{ number_format($stats['total_amount'], 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Processing Fees</span>
                <span class="text-gray-700">USD {{ number_format($stats['total_fees'], 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent IPNs -->
<div class="bg-white rounded-lg shadow mt-6">
    <div class="border-b px-6 py-4">
        <h2 class="text-lg font-bold">Recent IPN Notifications</h2>
    </div>
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">TXN ID</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-right text-sm font-semibold">Amount</th>
                <th class="px-6 py-3 text-center text-sm font-semibold">V</th>
                <th class="px-6 py-3 text-center text-sm font-semibold">P</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-6 py-3 text-center text-sm font-semibold">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($recentIpns as $ipn)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 text-sm font-mono text-xs">{{ substr($ipn->txn_id, 0, 10) }}...</td>
                <td class="px-6 py-3 text-sm"><span class="px-2 py-1 text-xs bg-gray-100 rounded">{{ $ipn->txn_type }}</span></td>
                <td class="px-6 py-3 text-sm">
                    <span class="px-2 py-1 text-xs rounded font-semibold
                        {{ $ipn->payment_status === 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $ipn->payment_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $ipn->payment_status === 'Failed' ? 'bg-red-100 text-red-800' : '' }}
                    ">
                        {{ $ipn->payment_status }}
                    </span>
                </td>
                <td class="px-6 py-3 text-right text-sm font-semibold">USD {{ number_format($ipn->mc_gross, 2) }}</td>
                <td class="px-6 py-3 text-center">
                    <span class="{{ $ipn->verified ? 'text-green-600' : 'text-red-600' }}">
                        {{ $ipn->verified ? '✓' : '✗' }}
                    </span>
                </td>
                <td class="px-6 py-3 text-center">
                    <span class="{{ $ipn->processed ? 'text-blue-600' : 'text-orange-600' }}">
                        {{ $ipn->processed ? '✓' : '○' }}
                    </span>
                </td>
                <td class="px-6 py-3 text-sm">{{ $ipn->created_at->format('M d H:i') }}</td>
                <td class="px-6 py-3 text-center">
                    <a href="/admin/paypal-ipn/{{ $ipn->id }}" class="text-blue-600 hover:underline text-sm">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">No recent IPNs</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
