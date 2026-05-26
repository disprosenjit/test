@extends('layouts.admin')

@section('title', 'IPN Log Details')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">IPN Log Details</h1>
        <p class="text-gray-600 mt-1">{{ $ipnLog->txn_id ?? 'N/A' }}</p>
    </div>
    <a href="/admin/payments/paypal-ipn" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Back to IPN Logs
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Summary Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Event Summary</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Transaction ID</p>
                    <p class="font-mono text-sm font-bold">{{ $ipnLog->txn_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Transaction Type</p>
                    <p class="font-semibold">{{ $ipnLog->getTransactionTypeLabel() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payment Status</p>
                    <p class="font-semibold">{{ $ipnLog->getPaymentStatusLabel() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Amount</p>
                    <p class="font-bold text-lg">${{ number_format($ipnLog->mc_gross ?? 0, 2) }} {{ $ipnLog->mc_currency }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Payer Email</p>
                    <p class="font-mono text-sm">{{ $ipnLog->payer_email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Receiver Email</p>
                    <p class="font-mono text-sm">{{ $ipnLog->receiver_email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Processing Status</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600">Verification Status</span>
                    <span class="px-3 py-1 rounded font-semibold text-sm {{ match($ipnLog->verification_status) {
                        'verified' => 'bg-green-100 text-green-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'invalid' => 'bg-red-100 text-red-800',
                        'duplicate' => 'bg-gray-100 text-gray-800',
                        default => 'bg-gray-100 text-gray-800'
                    } }}">
                        {{ ucfirst($ipnLog->verification_status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-gray-600">Processed</span>
                    <span class="px-3 py-1 rounded font-semibold text-sm {{ $ipnLog->processed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $ipnLog->processed ? '✓ Yes' : '✗ Pending' }}
                    </span>
                </div>
                @if($ipnLog->processing_error)
                <div class="pb-3 border-b">
                    <span class="text-gray-600 text-sm block mb-2">Error</span>
                    <p class="bg-red-50 border border-red-200 rounded p-2 text-sm text-red-800 font-mono">
                        {{ $ipnLog->processing_error }}
                    </p>
                </div>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Created</span>
                    <span class="text-sm">{{ $ipnLog->created_at->format('M d, Y H:i:s') }}</span>
                </div>
                @if($ipnLog->processed_at)
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Processed</span>
                    <span class="text-sm">{{ $ipnLog->processed_at->format('M d, Y H:i:s') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Linked Records -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Linked Records</h2>
            <div class="space-y-3">
                @if($ipnLog->payment)
                    <div class="pb-3 border-b">
                        <span class="text-gray-600 text-sm block mb-1">Payment</span>
                        <a href="/admin/payments/{{ $ipnLog->payment->id }}" class="text-blue-600 hover:underline font-semibold">
                            Payment #{{ $ipnLog->payment->id }} ({{ $ipnLog->payment->method }})
                        </a>
                    </div>
                @endif

                @if($ipnLog->order)
                    <div class="pb-3 border-b">
                        <span class="text-gray-600 text-sm block mb-1">Order</span>
                        <a href="/admin/orders/{{ $ipnLog->order->id }}" class="text-blue-600 hover:underline font-semibold">
                            Order #{{ $ipnLog->order->order_number }}
                        </a>
                    </div>
                @endif

                @if(!$ipnLog->payment && !$ipnLog->order)
                    <p class="text-gray-500 italic">No linked records</p>
                @endif
            </div>
        </div>

        <!-- Parsed Data -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Parsed Data</h2>
            <div class="bg-gray-50 rounded p-4 max-h-96 overflow-y-auto">
                <pre class="text-xs font-mono">{{ json_encode($ipnLog->parsed_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>

        <!-- Raw Data -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Raw POST Data</h2>
                <a href="/admin/paypal-ipn/{{ $ipnLog->id }}/raw" class="text-blue-600 hover:underline text-sm">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
            <div class="bg-gray-50 rounded p-4 max-h-96 overflow-y-auto">
                <pre class="text-xs font-mono">{{ $ipnLog->raw_post_data }}</pre>
            </div>
        </div>
    </div>

    <!-- Action Panel -->
    <div class="space-y-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold mb-4">Actions</h3>
            <div class="space-y-2">
                @if($ipnLog->verification_status === 'pending')
                    <form method="POST" action="/admin/paypal-ipn/{{ $ipnLog->id }}/verify-manually">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 text-sm font-semibold">
                            <i class="fas fa-check mr-2"></i>Mark Verified
                        </button>
                    </form>
                @endif

                @if($ipnLog->processed && $ipnLog->processing_error)
                    <form method="POST" action="/admin/paypal-ipn/{{ $ipnLog->id }}/retry">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 text-sm font-semibold">
                            <i class="fas fa-redo mr-2"></i>Retry Processing
                        </button>
                    </form>
                @endif

                @if($ipnLog->verification_status !== 'invalid')
                    <button type="button" class="w-full bg-red-100 text-red-800 py-2 rounded hover:bg-red-200 text-sm font-semibold" onclick="document.getElementById('invalidForm').style.display = 'block'; this.style.display = 'none';">
                        <i class="fas fa-times mr-2"></i>Mark Invalid
                    </button>
                @endif
            </div>
        </div>

        <!-- Mark Invalid Form -->
        <div id="invalidForm" style="display: none;" class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="/admin/paypal-ipn/{{ $ipnLog->id }}/invalid">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">Reason</label>
                    <textarea name="reason" required class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Why is this IPN invalid?" rows="3"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded hover:bg-red-700 text-sm font-semibold">
                        <i class="fas fa-check"></i> Confirm
                    </button>
                    <button type="button" onclick="document.getElementById('invalidForm').style.display = 'none';" class="flex-1 bg-gray-300 text-gray-800 py-2 rounded hover:bg-gray-400 text-sm font-semibold">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
            <h3 class="font-bold text-blue-900 mb-2">About IPN</h3>
            <p class="text-sm text-blue-800">Instant Payment Notification (IPN) allows PayPal to notify your server of payment events automatically. Use these tools to debug and monitor webhook deliveries.</p>
        </div>

        <!-- Links -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-bold mb-2">Resources</h3>
            <div class="space-y-2 text-sm">
                <a href="/admin/paypal-ipn/settings" class="block text-blue-600 hover:underline">IPN Settings & URLs</a>
                <a href="/admin/paypal-ipn/dashboard" class="block text-blue-600 hover:underline">IPN Dashboard</a>
                <a href="/admin/payments/paypal-settings" class="block text-blue-600 hover:underline">PayPal Configuration</a>
            </div>
        </div>
    </div>
</div>
@endsection
