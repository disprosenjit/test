@extends('layouts.admin')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">Order #{{ $order->id }}</h1>
        <p class="text-gray-600 mt-1">{{ $order->created_at->format('M d, Y H:i') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/orders" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <a href="/orders/{{ $order->id }}/invoice" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            <i class="fas fa-file-pdf mr-2"></i>Invoice
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="lg:col-span-2">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Customer Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Name</p>
                    <p class="font-semibold">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="font-semibold">{{ $order->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Phone</p>
                    <p class="font-semibold">{{ $order->user->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Company</p>
                    <p class="font-semibold">{{ $order->user->company ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Shipping Address</h2>
            @php
                $address = $order->shippingAddress;
            @endphp
            @if($address)
                <div class="text-sm space-y-1 text-gray-700">
                    <p><strong>{{ $address->name }}</strong></p>
                    <p>{{ $address->address_line1 }}</p>
                    @if($address->address_line2)
                        <p>{{ $address->address_line2 }}</p>
                    @endif
                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                    <p>{{ $address->country }}</p>
                    <p class="mt-2"><strong>Phone:</strong> {{ $address->phone }}</p>
                </div>
            @else
                <p class="text-gray-500">No address on file</p>
            @endif
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Order Items</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-4 py-2 font-semibold">Product</th>
                            <th class="text-right px-4 py-2 font-semibold">Price</th>
                            <th class="text-right px-4 py-2 font-semibold">Qty</th>
                            <th class="text-right px-4 py-2 font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-2">
                                    <a href="/admin/products/{{ $item->product_id }}" class="text-blue-600 hover:underline">
                                        {{ $item->product->name }}
                                    </a>
                                    <p class="text-gray-500 text-xs">SKU: {{ $item->product->sku }}</p>
                                </td>
                                <td class="text-right px-4 py-2">₹{{ number_format($item->price, 2) }}</td>
                                <td class="text-right px-4 py-2">{{ $item->quantity }}</td>
                                <td class="text-right px-4 py-2 font-semibold">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Shipment Info -->
        @if($order->shipment)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-bold mb-4">Shipment Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Tracking Number</p>
                        <p class="font-semibold font-mono">{{ $order->shipment->tracking_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Carrier</p>
                        <p class="font-semibold">{{ ucfirst($order->shipment->carrier) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Status</p>
                        <p class="font-semibold text-blue-600">{{ ucfirst(str_replace('_', ' ', $order->shipment->status)) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Expected Delivery</p>
                        <p class="font-semibold">{{ $order->shipment->expected_delivery->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        @elseif(in_array($order->status, ['confirmed', 'processing']))
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-bold mb-4">Create Shipment</h2>
                <p class="text-gray-600 text-sm mb-4">No shipment has been created yet. Create a shipment to mark this order as shipped.</p>
                <button type="button" onclick="openShipmentModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold text-sm">
                    <i class="fas fa-box mr-2"></i>Create Shipment
                </button>
            </div>
        @endif
    </div>

    <!-- Order Summary Sidebar -->
    <div>
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Order Status</h3>
            
            <div class="mb-4">
                <p class="text-gray-600 text-sm mb-2">Current Status</p>
                <span class="px-3 py-1 rounded text-sm font-semibold
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                    @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <form method="POST" action="/admin/orders/{{ $order->id }}/status" class="space-y-3">
                @csrf
                @method('PUT')
                
                <select name="status" class="w-full px-3 py-2 border rounded-lg text-sm">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <textarea name="notes" rows="2" placeholder="Add notes..." class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold text-sm">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Payment Information</h3>
            
            @if($order->payment)
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm">Status</p>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->payment->status === 'approved') bg-green-100 text-green-800
                            @elseif($order->payment->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Method</p>
                        <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment->method)) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Amount</p>
                        <p class="text-2xl font-bold text-green-600">₹{{ number_format($order->payment->amount, 2) }}</p>
                    </div>
                </div>

                <a href="/admin/payments/{{ $order->payment->id }}" class="block mt-4 text-blue-600 hover:underline text-sm">
                    View Payment Details →
                </a>
            @else
                <p class="text-gray-500">No payment recorded</p>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Order Summary</h3>
            
            <div class="space-y-2 text-sm mb-4 pb-4 border-b">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">₹{{ number_format($order->items->sum(fn($item) => $item->price * $item->quantity), 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Shipping</span>
                    <span class="font-semibold">₹{{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tax (GST)</span>
                    <span class="font-semibold">₹{{ number_format($order->tax ?? 0, 2) }}</span>
                </div>
            </div>

            <div class="flex justify-between text-lg mb-4">
                <span class="font-bold">Total</span>
                <span class="font-bold text-green-600">₹{{ number_format($order->total, 2) }}</span>
            </div>

            <div class="pt-4 border-t">
                <p class="text-gray-600 text-xs">Order Date</p>
                <p class="text-sm">{{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>

        <!-- Cancel Button -->
        @if(!in_array($order->status, ['delivered', 'cancelled']))
            <form method="POST" action="/admin/orders/{{ $order->id }}/cancel" class="mt-6">
                @csrf
                <input type="hidden" name="reason" value="Cancelled by admin">
                <button type="submit" onclick="return confirm('Are you sure?')" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold text-sm">
                    Cancel Order
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Shipment Modal -->
<div id="shipment-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">Create Shipment for Order #{{ $order->id }}</h3>
        <form method="POST" action="/admin/orders/{{ $order->id }}/shipment" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2">Tracking Number *</label>
                <input type="text" name="tracking_number" required placeholder="e.g., TRK1234567890" class="w-full px-3 py-2 border rounded-lg @error('tracking_number') border-red-500 @enderror" />
                @error('tracking_number')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Carrier *</label>
                <select name="carrier" required class="w-full px-3 py-2 border rounded-lg @error('carrier') border-red-500 @enderror">
                    <option value="">Select carrier</option>
                    <option value="dhl">DHL</option>
                    <option value="fedex">FedEx</option>
                    <option value="ups">UPS</option>
                    <option value="local">Local Courier</option>
                </select>
                @error('carrier')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Expected Delivery Date *</label>
                <input type="date" name="expected_delivery" required class="w-full px-3 py-2 border rounded-lg @error('expected_delivery') border-red-500 @enderror" />
                @error('expected_delivery')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">Create Shipment</button>
                <button type="button" onclick="closeShipmentModal()" class="flex-1 bg-gray-200 text-gray-800 py-2 rounded hover:bg-gray-300">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openShipmentModal() {
    document.getElementById('shipment-modal').classList.remove('hidden');
}

function closeShipmentModal() {
    document.getElementById('shipment-modal').classList.add('hidden');
}
</script>
@endsection
