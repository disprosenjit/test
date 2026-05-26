@extends('layouts.admin')

@section('title', 'PayPal IPN - ' . ($ipnLog->txn_id ?? 'Details'))

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">IPN Details</h1>
        <p class="text-gray-600 mt-1">{{ $ipnLog->txn_id }}</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/paypal-ipn" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <a href="/admin/paypal-ipn/{{ $ipnLog->id }}/raw" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-code mr-2"></i>Raw Data
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <!-- IPN Information -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">IPN Information</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="text-gray-600 font-semibold">Transaction ID</label>
                    <div class="font-mono text-xs break-all">{{ $ipnLog->txn_id }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Transaction Type</label>
                    <div>{{ $ipnLog->txn_type }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Payment Status</label>
                    <div class="inline-block px-2 py-1 rounded text-xs font-semibold
                        {{ $ipnLog->payment_status === 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $ipnLog->payment_status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $ipnLog->payment_status === 'Failed' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $ipnLog->payment_status === 'Refunded' ? 'bg-blue-100 text-blue-800' : '' }}
                    ">
                        {{ $ipnLog->payment_status }}
                    </div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Test IPN</label>
                    <div>{{ $ipnLog->test_ipn ? 'Yes (Sandbox)' : 'No (Live)' }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Verified</label>
                    <div class="inline-flex items-center gap-2">
                        <span class="{{ $ipnLog->verified ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas {{ $ipnLog->verified ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        </span>
                        <span>{{ $ipnLog->verified ? 'Verified' : 'Not Verified' }}</span>
                    </div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Processed</label>
                    <div class="inline-flex items-center gap-2">
                        <span class="{{ $ipnLog->processed ? 'text-blue-600' : 'text-orange-600' }}">
                            <i class="fas {{ $ipnLog->processed ? 'fa-check-double' : 'fa-hourglass-half' }}"></i>
                        </span>
                        <span>{{ $ipnLog->processed ? 'Processed' : 'Pending' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payer Information -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Payer Information</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="text-gray-600 font-semibold">Email</label>
                    <div>{{ $ipnLog->payer_email }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Payer ID</label>
                    <div class="font-mono text-xs">{{ $ipnLog->payer_id }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Name</label>
                    <div>{{ $ipnLog->first_name }} {{ $ipnLog->last_name }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Country</label>
                    <div>{{ $ipnLog->address_country }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Payment Details</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="text-gray-600 font-semibold">Gross Amount</label>
                    <div class="text-lg font-bold">{{ $ipnLog->mc_currency }} {{ number_format($ipnLog->mc_gross, 2) }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Fee</label>
                    <div>{{ $ipnLog->mc_currency }} {{ number_format($ipnLog->mc_fee ?? 0, 2) }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Item Name</label>
                    <div>{{ $ipnLog->item_name }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Quantity</label>
                    <div>{{ $ipnLog->quantity }}</div>
                </div>
            </div>
        </div>

        <!-- Related Order/Payment -->
        @if($ipnLog->order)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Related Order</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="text-gray-600 font-semibold">Order ID</label>
                    <a href="/admin/orders/{{ $ipnLog->order->id }}" class="text-blue-600 hover:underline">
                        #{{ $ipnLog->order->order_number }}
                    </a>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Customer</label>
                    <div>{{ $ipnLog->order->user->name }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Order Total</label>
                    <div>{{ $ipnLog->order->currency ?? 'USD' }} {{ number_format($ipnLog->order->total, 2) }}</div>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Order Status</label>
                    <div class="inline-block px-2 py-1 text-xs rounded font-semibold 
                        {{ $ipnLog->order->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $ipnLog->order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $ipnLog->order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    ">
                        {{ ucfirst($ipnLog->order->status) }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($ipnLog->payment)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Related Payment</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="text-gray-600 font-semibold">Payment ID</label>
                    <a href="/admin/payments/{{ $ipnLog->payment->id }}" class="text-blue-600 hover:underline">
                        #{{ $ipnLog->payment->id }}
                    </a>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Payment Status</label>
                    <div class="inline-block px-2 py-1 text-xs rounded font-semibold 
                        {{ $ipnLog->payment->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $ipnLog->payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $ipnLog->payment->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                    ">
                        {{ ucfirst($ipnLog->payment->status) }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($ipnLog->error_message)
        <div class="bg-red-50 rounded-lg shadow p-6 mt-6">
            <h2 class="text-lg font-bold mb-4 text-red-800">Verification Error</h2>
            <p class="text-red-700">{{ $ipnLog->error_message }}</p>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Actions</h2>
            <div class="space-y-2">
                @if(!$ipnLog->verified)
                    <form method="POST" action="/admin/paypal-ipn/{{ $ipnLog->id }}/verify-manually">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            <i class="fas fa-check-circle mr-2"></i>Verify Manually
                        </button>
                    </form>
                @endif

                @if($ipnLog->verified && !$ipnLog->processed)
                    <form method="POST" action="/admin/paypal-ipn/{{ $ipnLog->id }}/retry">
                        @csrf
                        <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 text-sm">
                            <i class="fas fa-redo-alt mr-2"></i>Retry Processing
                        </button>
                    </form>
                @endif

                @if($ipnLog->verified)
                    <form method="POST" action="/admin/paypal-ipn/{{ $ipnLog->id }}/invalid">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm"
                                onclick="return confirm('Mark as invalid?')">
                            <i class="fas fa-ban mr-2"></i>Mark Invalid
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Timeline</h2>
            <div class="space-y-3 text-sm">
                <div class="flex gap-3">
                    <div class="text-green-600"><i class="fas fa-plus-circle"></i></div>
                    <div>
                        <div class="font-semibold">Created</div>
                        <div class="text-gray-600">{{ $ipnLog->created_at->format('M d, Y H:i:s') }}</div>
                    </div>
                </div>
                @if($ipnLog->verified)
                <div class="flex gap-3">
                    <div class="text-blue-600"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="font-semibold">Verified</div>
                        <div class="text-gray-600">{{ $ipnLog->updated_at->format('M d, Y H:i:s') }}</div>
                    </div>
                </div>
                @endif
                @if($ipnLog->processed)
                <div class="flex gap-3">
                    <div class="text-purple-600"><i class="fas fa-check-double"></i></div>
                    <div>
                        <div class="font-semibold">Processed</div>
                        <div class="text-gray-600">{{ $ipnLog->updated_at->format('M d, Y H:i:s') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
