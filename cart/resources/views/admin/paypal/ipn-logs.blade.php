@extends('layouts.admin')

@section('title', 'PayPal IPN Logs')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">PayPal IPN Logs</h1>
        <p class="text-gray-600 mt-1">Review and manage PayPal webhook notifications</p>
    </div>
    <a href="/admin/paypal-ipn/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-chart-line mr-2"></i>Dashboard
    </a>
</div>

<div class="bg-white rounded-lg shadow p-5 mb-6">
    <form method="GET" action="/admin/payments/paypal-ipn" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
        <input type="text" name="search" placeholder="TXN ID or Email" value="{{ request('search') }}" class="col-span-2 px-3 py-2 border rounded-lg">

        <select name="verification_status" class="px-3 py-2 border rounded-lg">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="verified" {{ request('verification_status') === 'verified' ? 'selected' : '' }}>Verified</option>
            <option value="invalid" {{ request('verification_status') === 'invalid' ? 'selected' : '' }}>Invalid</option>
            <option value="duplicate" {{ request('verification_status') === 'duplicate' ? 'selected' : '' }}>Duplicate</option>
        </select>

        <select name="payment_status" class="px-3 py-2 border rounded-lg">
            <option value="">All Payment Status</option>
            <option value="Completed" {{ request('payment_status') === 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Pending" {{ request('payment_status') === 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Failed" {{ request('payment_status') === 'Failed' ? 'selected' : '' }}>Failed</option>
            <option value="Refunded" {{ request('payment_status') === 'Refunded' ? 'selected' : '' }}>Refunded</option>
        </select>

        <select name="processed" class="px-3 py-2 border rounded-lg">
            <option value="">All</option>
            <option value="1" {{ request('processed') === '1' ? 'selected' : '' }}>Processed</option>
            <option value="0" {{ request('processed') === '0' ? 'selected' : '' }}>Unprocessed</option>
        </select>

        <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-600">Total</div>
        <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-600">Pending</div>
        <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-600">Verified</div>
        <div class="text-2xl font-bold text-green-600">{{ $stats['verified'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-600">Unprocessed</div>
        <div class="text-2xl font-bold text-orange-600">{{ $stats['unprocessed'] }}</div>
    </div>
</div>

@if($ipnLogs->isEmpty())
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500">No IPN logs found</p>
    </div>
@else
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">TXN ID</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Payer Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Payment Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Verified</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Processed</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($ipnLogs as $ipn)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <a href="/admin/paypal-ipn/{{ $ipn->id }}" class="text-blue-600 hover:underline font-mono text-xs">
                                {{ Str::limit($ipn->txn_id ?? 'N/A', 20) }}
                            </a>
                        </td>
                        <td class="px-6 py-3 text-sm">{{ $ipn->getTransactionTypeLabel() }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ Str::limit($ipn->payer_email ?? 'N/A', 25) }}</td>
                        <td class="px-6 py-3 text-sm font-semibold">${{ number_format($ipn->mc_gross ?? 0, 2) }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $ipn->payment_status === 'Completed' ? 'bg-green-100 text-green-800' : ($ipn->payment_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $ipn->getPaymentStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ match($ipn->verification_status) {
                                'verified' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'invalid' => 'bg-red-100 text-red-800',
                                'duplicate' => 'bg-gray-100 text-gray-800',
                                default => 'bg-gray-100 text-gray-800'
                            } }}">
                                {{ ucfirst($ipn->verification_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $ipn->processed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $ipn->processed ? '✓ Yes' : '✗ No' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ $ipn->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="/admin/paypal-ipn/{{ $ipn->id }}" class="text-blue-600 hover:underline text-sm">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $ipnLogs->links() }}
    </div>
@endif
@endsection
