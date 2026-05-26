@extends('layouts.app')

@section('title', 'My Orders - Ship Spare Parts Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">My Orders</h1>
        <a href="/products" class="text-blue-600 hover:underline">Continue Shopping</a>
    </div>

    @if($orders->count() === 0)
        <div class="bg-white p-10 rounded-lg shadow text-center">
            <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 mb-4">You have not placed any orders yet.</p>
            <a href="/products" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Browse Products
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Order #</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Items</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Payment</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">{{ $order->items->count() }}</td>
                            <td class="px-6 py-4 font-semibold">₹{{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif(in_array($order->status, ['confirmed', 'processing'])) bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($order->payment_status === 'approved') bg-green-100 text-green-800
                                    @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-col gap-2">
                                    <a href="/orders/{{ $order->id }}" class="text-blue-600 hover:underline">View</a>

                                    @if($order->payment_status === 'pending' && $order->status !== 'cancelled')
                                        @if(!empty($enabledMethods))
                                        <div class="flex gap-2 items-center">
                                            <select id="pay-method-{{ $order->id }}" class="px-2 py-1 border rounded text-xs">
                                                @foreach($enabledMethods as $method)
                                                <option value="{{ $method['key'] }}">{{ $method['name'] }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button"
                                                    onclick="payPendingOrder({{ $order->id }})"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">
                                                Pay Now
                                            </button>
                                        </div>
                                        @else
                                        <p class="text-red-600 text-xs">No payment methods available</p>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<script>
function paymentEndpoint(method, orderId) {
    const map = {
        bank_transfer: `/api/payments/bank-transfer/${orderId}`,
        upi: `/api/payments/upi/${orderId}`,
        credit_card: `/api/payments/card/${orderId}`,
        debit_card: `/api/payments/card/${orderId}`,
        stripe: `/api/payments/stripe/${orderId}`,
        paypal: `/api/payments/paypal/${orderId}`,
    };

    return map[method] || map.bank_transfer;
}

async function payPendingOrder(orderId) {
    const select = document.getElementById(`pay-method-${orderId}`);
    const method = select ? select.value : 'bank_transfer';

    if (method === 'credit_card' || method === 'debit_card') {
        window.location.href = `/card-payment/${orderId}`;
        return;
    }

    if (method === 'paypal') {
        window.location.href = `/paypal/checkout/${orderId}`;
        return;
    }
        const response = await fetch(paymentEndpoint(method, orderId), {
            method: 'POST',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || result.message || 'Unable to initiate payment');
        }

        if (method === 'upi' && result.upi_link) {
            window.open(result.upi_link, '_blank');
        }

        const message = method === 'bank_transfer'
            ? 'Payment initiated. Upload bank transfer proof from order details page.'
            : 'Payment initiated successfully.';

        showNotification(message, 'success', 'Success');
        setTimeout(() => {
            window.location.href = `/orders/${orderId}`;
        }, 1500);
    } catch (error) {
        showNotification(error.message || 'Unable to initiate payment', 'error', 'Error');
    }
}
</script>
@endsection
