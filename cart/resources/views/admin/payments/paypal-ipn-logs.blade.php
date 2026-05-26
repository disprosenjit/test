@extends('layouts.admin')

@section('title', 'PayPal IPN Logs')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">PayPal IPN Logs</h1>
        <p class="text-gray-600 mt-1">Monitor PayPal Instant Payment Notifications</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/paypal-ipn/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-chart-line mr-2"></i>Dashboard
        </a>
        <a href="/admin/paypal-ipn/settings" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            <i class="fas fa-cog mr-2"></i>Settings
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-gray-600 text-sm">Total IPNs</div>
        <div class="text-2xl font-bold">{{ $stats['total_ipns'] }}</div>
    </div>
    <div class="bg-green-50 rounded-lg shadow p-4">
        <div class="text-green-700 text-sm">Verified</div>
        <div class="text-2xl font-bold text-green-700">{{ $stats['verified'] }}</div>
    </div>
    <div class="bg-yellow-50 rounded-lg shadow p-4">
        <div class="text-yellow-700 text-sm">Unverified</div>
        <div class="text-2xl font-bold text-yellow-700">{{ $stats['unverified'] }}</div>
    </div>
    <div class="bg-blue-50 rounded-lg shadow p-4">
        <div class="text-blue-700 text-sm">Processed</div>
        <div class="text-2xl font-bold text-blue-700">{{ $stats['processed'] }}</div>
    </div>
    <div class="bg-orange-50 rounded-lg shadow p-4">
        <div class="text-orange-700 text-sm">Pending</div>
        <div class="text-2xl font-bold text-orange-700">{{ $stats['unprocessed'] }}</div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <input type="text" name="search" placeholder="Transaction ID or Email" 
               class="px-3 py-2 border rounded-lg" value="{{ request('search') }}">
        
        <select name="status" class="px-3 py-2 border rounded-lg">
            <option value="">All Status</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
            <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
            <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
            <option value="unprocessed" {{ request('status') === 'unprocessed' ? 'selected' : '' }}>Unprocessed</option>
        </select>

        <select name="payment_status" class="px-3 py-2 border rounded-lg">
            <option value="">All Payment Status</option>
            <option value="Completed" {{ request('payment_status') === 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Pending" {{ request('payment_status') === 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Failed" {{ request('payment_status') === 'Failed' ? 'selected' : '' }}>Failed</option>
            <option value="Refunded" {{ request('payment_status') === 'Refunded' ? 'selected' : '' }}>Refunded</option>
        </select>

        <input type="date" name="from" class="px-3 py-2 border rounded-lg" value="{{ request('from') }}">
        <input type="date" name="to" class="px-3 py-2 border rounded-lg" value="{{ request('to') }}">
        
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
    </form>
</div>

<!-- IPN Logs Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Transaction ID</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Type</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Payer</th>
                <th class="px-4 py-3 text-right text-sm font-semibold">Amount</th>
                <th class="px-4 py-3 text-center text-sm font-semibold">Verified</th>
                <th class="px-4 py-3 text-center text-sm font-semibold">Processed</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-4 py-3 text-center text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($ipnLogs as $ipn)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-mono text-xs">{{ substr($ipn->txn_id, 0, 12) }}...</td>
                <td class="px-4 py-3 text-sm">
                    <span class="px-2 py-1 text-xs rounded bg-gray-100">{{ $ipn->txn_type }}</span>
                </td>
                <td class="px-4 py-3 text-sm">
                    <span class="px-2 py-1 text-xs rounded 
                        {{ $ipn->payment_status === 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $ipn->payment_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $ipn->payment_status === 'Failed' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $ipn->payment_status === 'Refunded' ? 'bg-blue-100 text-blue-800' : '' }}
                    ">
                        {{ $ipn->payment_status }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 truncate">{{ $ipn->payer_email }}</td>
                <td class="px-4 py-3 text-right text-sm font-semibold">
                    {{ $ipn->mc_currency }} {{ number_format($ipn->mc_gross, 2) }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($ipn->verified)
                        <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                    @else
                        <span class="text-red-600"><i class="fas fa-times-circle"></i></span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    @if($ipn->processed)
                        <span class="text-blue-600"><i class="fas fa-check-double"></i></span>
                    @else
                        <span class="text-orange-600"><i class="fas fa-hourglass-half"></i></span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm">{{ $ipn->created_at->format('M d, Y H:i') }}</td>
                <td class="px-4 py-3 text-center text-sm">
                    <a href="/admin/paypal-ipn/{{ $ipn->id }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-8 text-center text-gray-500">No IPN logs found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $ipnLogs->links() }}
</div>
@endsection
