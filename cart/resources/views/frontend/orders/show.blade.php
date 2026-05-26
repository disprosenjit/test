@extends('layouts.app')

@section('title', 'Order Details - Ship Spare Parts Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">Order {{ $order->order_number }}</h1>
            <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="/orders" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Back</a>
            <a href="/orders/{{ $order->id }}/invoice" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Download Invoice</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold">Items</h2>
                </div>
                <div class="divide-y">
                    @foreach($order->items as $item)
                        <div class="p-6 flex justify-between gap-4">
                            <div>
                                <p class="font-semibold">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                                <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Unit: ₹{{ number_format($item->unit_price, 2) }}</p>
                                <p class="font-semibold">₹{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($order->shippingAddress)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-3">Shipping Address</h2>
                    <p class="font-semibold">{{ $order->shippingAddress->name }}</p>
                    <p class="text-gray-700">{{ $order->shippingAddress->phone }}</p>
                    <p class="text-gray-700">{{ $order->shippingAddress->address_line_1 }}</p>
                    @if($order->shippingAddress->address_line_2)
                        <p class="text-gray-700">{{ $order->shippingAddress->address_line_2 }}</p>
                    @endif
                    <p class="text-gray-700">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                    <p class="text-gray-700">{{ $order->shippingAddress->country }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Order Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>₹{{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="flex justify-between"><span>Tax</span><span>₹{{ number_format($order->tax, 2) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>₹{{ number_format($order->shipping, 2) }}</span></div>
                    <div class="border-t pt-2 mt-2 flex justify-between font-bold text-base"><span>Total</span><span>₹{{ number_format($order->total, 2) }}</span></div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Status</h3>
                <p class="mb-2">Order: <span class="font-semibold">{{ ucfirst($order->status) }}</span></p>
                <p>Payment: <span class="font-semibold">{{ ucfirst($order->payment_status) }}</span></p>

                @if($order->shipment && $order->shipment->tracking_number)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600">Tracking Number</p>
                        <p class="font-mono">{{ $order->shipment->tracking_number }}</p>
                    </div>
                @endif
            </div>

            @if($order->payment_status === 'pending' && $order->status !== 'cancelled')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-3">Complete Payment</h3>
                    <p class="text-sm text-gray-600 mb-3">Select a payment option to pay this pending order.</p>

                    @if(!empty($enabledMethods))
                    <div class="space-y-3">
                        @foreach($enabledMethods as $method)
                        <button type="button" onclick="payWithMethod('{{ $method['key'] }}', {{ $order->id }})" 
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:border-blue-500 hover:bg-blue-50 transition flex items-center justify-center gap-2">
                            <i class="{{ $method['icon'] }}"></i>
                            {{ $method['name'] }}
                        </button>
                        @endforeach
                    </div>

                    @if(\App\Models\Payment\PaymentMethod::where('key', 'bank_transfer')->where('is_enabled', true)->exists())
                    <form id="bank-proof-form" class="space-y-3 mt-4 pt-4 border-t" onsubmit="submitBankProof(event, {{ $order->id }})">
                        <h4 class="font-semibold text-sm">Bank Transfer Proof</h4>
                        <input type="text" name="transaction_reference" placeholder="Transaction reference (optional)" class="w-full px-3 py-2 border rounded-lg text-sm" />
                        <input type="file" name="proof_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border rounded-lg text-sm" />
                        <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded hover:bg-black text-sm">Upload Proof</button>
                    </form>
                    @endif
                    @else
                    <div class="p-4 rounded-lg bg-red-100 text-red-800">
                        <p>No payment methods are currently available. Please contact support.</p>
                    </div>
                    @endif
                </div>
            @endif

            @if(in_array($order->status, ['pending', 'confirmed']))
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-3">Cancel Order</h3>
                    <form method="POST" action="/orders/{{ $order->id }}/cancel" onsubmit="return confirm('Cancel this order?')">
                        @csrf
                        <textarea name="reason" rows="3" required placeholder="Reason for cancellation" class="w-full px-3 py-2 border rounded-lg mb-3"></textarea>
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">Cancel Order</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function paymentEndpoint(method, orderId) {
    const map = {
        stripe: `/card-payment/${orderId}`,
        paypal: `/paypal/checkout/${orderId}`,
        bank_transfer: null,
        card: `/card-payment/${orderId}`,
    };

    return map[method] || null;
}

async function payWithMethod(method, orderId) {
    const endpoint = paymentEndpoint(method, orderId);

    if (endpoint) {
        window.location.href = endpoint;
        return;
    }

    // For methods without dedicated endpoints, show message
    if (method === 'bank_transfer') {
        showNotification('Please upload your bank transfer proof below.', 'info', 'Bank Transfer');
        document.getElementById('bank-proof-form').scrollIntoView({ behavior: 'smooth' });
        return;
    }

    showNotification('Payment method not available', 'warning', 'Warning');
}

async function submitBankProof(event, orderId) {
    event.preventDefault();
    const form = document.getElementById('bank-proof-form');
    const formData = new FormData(form);
    formData.append('order_id', orderId);

    try {
        const response = await fetch('/api/payments/verify-bank-transfer', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || result.message || 'Unable to submit proof');
        }

        showNotification(result.message || 'Payment proof submitted successfully.', 'success', 'Success');
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    } catch (error) {
        showNotification(error.message || 'Unable to submit proof', 'error', 'Error');
    }
}
</script>
@endsection
