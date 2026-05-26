@extends('layouts.admin')

@section('title', 'PayPal IPN Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">PayPal IPN Dashboard</h1>
    <p class="text-gray-600 mt-1">Monitor PayPal webhook notifications and payment events</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-5">
        <div class="text-gray-500 text-sm font-medium">Total IPNs</div>
        <div class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</div>
    </div>

    <div class="bg-white rounded-lg shadow p-5">
        <div class="text-gray-500 text-sm font-medium">Pending Verification</div>
        <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
    </div>

    <div class="bg-white rounded-lg shadow p-5">
        <div class="text-gray-500 text-sm font-medium">Verified</div>
        <div class="text-3xl font-bold text-green-600">{{ $stats['verified'] }}</div>
    </div>

    <div class="bg-white rounded-lg shadow p-5">
        <div class="text-gray-500 text-sm font-medium">Invalid</div>
        <div class="text-3xl font-bold text-red-600">{{ $stats['invalid'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Recent IPNs</h2>

        @if($recent->isEmpty())
            <p class="text-gray-500 text-center py-8">No IPN logs yet</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 font-semibold">TXN ID</th>
                            <th class="text-left py-2 font-semibold">Type</th>
                            <th class="text-left py-2 font-semibold">Status</th>
                            <th class="text-left py-2 font-semibold">Amount</th>
                            <th class="text-left py-2 font-semibold">Verify</th>
                            <th class="text-center py-2 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent as $ipn)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2">
                                    <a href="/admin/paypal-ipn/{{ $ipn->id }}" class="text-blue-600 hover:underline font-mono text-xs">
                                        {{ Str::limit($ipn->txn_id ?? 'N/A', 15) }}
                                    </a>
                                </td>
                                <td class="py-2 text-xs">{{ $ipn->getTransactionTypeLabel() }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $ipn->payment_status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $ipn->getPaymentStatusLabel() }}
                                    </span>
                                </td>
                                <td class="py-2 text-xs">${{ number_format($ipn->mc_gross ?? 0, 2) }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ match($ipn->verification_status) {
                                        'verified' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'invalid' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    } }}">
                                        {{ ucfirst($ipn->verification_status) }}
                                    </span>
                                </td>
                                <td class="py-2 text-center">
                                    <a href="/admin/paypal-ipn/{{ $ipn->id }}" class="text-blue-600 hover:underline text-xs">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="font-bold mb-3">Quick Stats</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Duplicates</span>
                    <span class="font-semibold">{{ $stats['duplicate'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Unprocessed</span>
                    <span class="font-semibold text-orange-600">{{ $stats['unprocessed'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="font-bold mb-3">By Transaction Type</h3>
            <div class="space-y-1 text-xs">
                @forelse($stats['by_type'] as $type)
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ $type->txn_type ?? 'Unknown' }}</span>
                        <span class="font-semibold">{{ $type->count }}</span>
                    </div>
                @empty
                    <p class="text-gray-500">No data</p>
                @endforelse
            </div>
        </div>

        <div>
            <a href="/admin/payments/paypal-ipn" class="block w-full bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700">
                <i class="fas fa-list mr-2"></i>View All IPNs
            </a>
        </div>
    </div>
</div>

@if($failed->isNotEmpty())
<div class="bg-red-50 rounded-lg border border-red-200 p-5 mb-6">
    <h3 class="font-bold text-red-900 mb-3">⚠ Attention Required</h3>
    <p class="text-sm text-red-800 mb-3">{{ count($failed) }} IPN(s) need manual attention:</p>
    <div class="space-y-2">
        @foreach($failed as $ipn)
            <div class="bg-white rounded p-3 flex justify-between items-center">
                <div>
                    <p class="text-sm font-mono">{{ Str::limit($ipn->txn_id ?? 'N/A', 20) }}</p>
                    <p class="text-xs text-gray-600">{{ $ipn->created_at->diffForHumans() }}</p>
                </div>
                <a href="/admin/paypal-ipn/{{ $ipn->id }}" class="text-blue-600 hover:underline text-sm">Review</a>
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection
