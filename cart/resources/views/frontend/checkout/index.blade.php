@extends('layouts.app')

@section('title', 'Checkout - Ship Spare Parts Store')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    <form id="checkout-form" onsubmit="submitOrder(event)" method="POST" action="/orders">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Steps -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Step 1: Shipping Address -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                        Shipping Address
                    </h2>
                    
                    @if($addresses->count() > 0)
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <label class="block text-sm font-semibold mb-2 text-gray-700">
                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                Select Saved Address
                            </label>
                            <select id="address-select" name="address_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition cursor-pointer" onchange="loadAddress(this.value)">
                                <option value="">-- Use New Address --</option>
                                @foreach($addresses as $address)
                                    <option value="{{ $address->id }}">
                                        <strong>{{ $address->name }}</strong> - {{ $address->address_line_1 }}, {{ $address->city }}, {{ $address->state }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-600 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Select from your saved addresses or enter a new address below
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Name *</label>
                            <input type="text" name="name" required placeholder="Full name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Phone *</label>
                            <input type="tel" name="phone" required placeholder="10-digit mobile number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Address Line 1 *</label>
                            <input type="text" name="address_line_1" required placeholder="Street address" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Address Line 2 <span class="text-gray-400 text-xs">(Optional)</span></label>
                            <input type="text" name="address_line_2" placeholder="Apartment, Suite, etc." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">City *</label>
                            <input type="text" name="city" required placeholder="City" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">State *</label>
                            <input type="text" name="state" required placeholder="State/Province" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Postal Code *</label>
                            <input type="text" name="postal_code" required placeholder="Postal code" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Country *</label>
                            <input type="text" name="country" value="India" required placeholder="Country" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>
                    </div>
                </div>

                <!-- Step 2: Payment Method -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">
                        <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                        Payment Method
                    </h2>

                    @if(!empty($enabledMethods))
                    <div class="space-y-3">
                        @foreach($enabledMethods as $method)
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition">
                            <input type="radio" name="payment_method" value="{{ $method['key'] }}" {{ $loop->first ? 'checked' : '' }} class="mr-3 w-4 h-4 cursor-pointer" onchange="togglePaymentDetails()">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">
                                    <i class="{{ $method['icon'] }} mr-2 text-lg"></i>{{ $method['name'] }}
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $method['description'] ?? 'Select this payment method' }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <!-- Bank Transfer Details -->
                    @if(array_search('bank_transfer', array_column($enabledMethods, 'key')) !== false)
                    <div id="bank-details" class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg hidden">
                        <div class="flex gap-3">
                            <i class="fas fa-university text-yellow-600 text-xl mt-0.5 flex-shrink-0"></i>
                            <div>
                                <h3 class="font-bold text-yellow-900 mb-2">Bank Transfer Details</h3>
                                <div class="space-y-1 text-sm text-yellow-800">
                                    <p><strong>Account Holder:</strong> Ship Spare Parts Store</p>
                                    <p><strong>Bank:</strong> HDFC Bank</p>
                                    <p><strong>Account Number:</strong> 1234567890123456</p>
                                    <p><strong>IFSC Code:</strong> HDFC0001234</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    @if(empty($enabledMethods))
                    <div class="p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>No payment methods are currently available. Please contact support.</span>
                    </div>
                    @endif
                </div>

                <!-- Step 3: Order Notes (Optional) -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">
                        <i class="fas fa-sticky-note text-blue-600 mr-2"></i>
                        Order Notes <span class="text-gray-400 text-sm font-normal">(Optional)</span>
                    </h2>
                    <textarea name="notes" rows="4" placeholder="Add any special instructions or requirements for your order..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"></textarea>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        These notes will help us prepare your order better
                    </p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow sticky top-20">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-receipt text-blue-600"></i>
                        Order Summary
                    </h2>
                    
                    <div id="order-items" class="space-y-2 mb-4 pb-4 border-b">
                        <!-- Items will be populated by JavaScript -->
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-gray-900" id="subtotal">₹0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax (18%)</span>
                            <span class="font-semibold text-gray-900" id="tax">₹0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold text-green-600" id="shipping">FREE</span>
                        </div>
                        <div class="border-t pt-4 flex justify-between font-bold text-lg">
                            <span>Total Amount</span>
                            <span id="total" class="text-blue-600 text-xl">₹0</span>
                        </div>
                    </div>

                    <button id="place-order-btn" type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 font-bold mb-2 shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        Place Order
                    </button>

                    <button type="button" onclick="window.location.href='/cart'" class="w-full bg-gray-200 text-gray-800 py-2.5 rounded-lg hover:bg-gray-300 font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Back to Cart
                    </button>

                    <!-- Security Badge -->
                    <div class="mt-4 pt-4 border-t text-center text-sm text-gray-600">
                        <i class="fas fa-lock text-green-600 mr-1"></i>
                        Secure checkout powered by encryption
                    </div>
                </div>
            </div>
        </div>
    </form>
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

function loadAddress(addressId) {
    if (!addressId) {
        // Clear all fields when "New Address" is selected
        clearAddressFields();
        return;
    }

    // Fetch address details from API
    fetch(`/api/addresses/${addressId}`, {
        credentials: 'include',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to load address');
        return response.json();
    })
    .then(address => {
        // Populate form fields with address data
        document.querySelector('input[name="name"]').value = address.name || '';
        document.querySelector('input[name="phone"]').value = address.phone || '';
        document.querySelector('input[name="address_line_1"]').value = address.address_line_1 || '';
        document.querySelector('input[name="address_line_2"]').value = address.address_line_2 || '';
        document.querySelector('input[name="city"]').value = address.city || '';
        document.querySelector('input[name="state"]').value = address.state || '';
        document.querySelector('input[name="postal_code"]').value = address.postal_code || '';
        document.querySelector('input[name="country"]').value = address.country || 'India';
        
        // Show a visual confirmation
        showNotification('Address loaded successfully', 'success', 'Success');
    })
    .catch(error => {
        console.error('Error loading address:', error);
        showNotification('Failed to load address', 'error', 'Error');
        clearAddressFields();
    });
}

function clearAddressFields() {
    // Clear all address fields
    document.querySelector('input[name="name"]').value = '';
    document.querySelector('input[name="phone"]').value = '';
    document.querySelector('input[name="address_line_1"]').value = '';
    document.querySelector('input[name="address_line_2"]').value = '';
    document.querySelector('input[name="city"]').value = '';
    document.querySelector('input[name="state"]').value = '';
    document.querySelector('input[name="postal_code"]').value = '';
    document.querySelector('input[name="country"]').value = 'India';
}

function togglePaymentDetails() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    const bankDetails = document.getElementById('bank-details');
    
    if (method === 'bank_transfer' && bankDetails) {
        bankDetails.classList.remove('hidden');
    } else if (bankDetails) {
        bankDetails.classList.add('hidden');
    }
}

async function submitOrder(event) {
    event.preventDefault();
    const placeOrderBtn = document.getElementById('place-order-btn');
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

    // If Stripe is selected, create order and redirect to card form
    if (paymentMethod === 'stripe') {
        return handleStripePayment(event);
    }

    placeOrderBtn.disabled = true;
    placeOrderBtn.classList.add('opacity-60', 'cursor-not-allowed');
    placeOrderBtn.textContent = 'Placing Order...';
    
    const formData = new FormData(document.getElementById('checkout-form'));
    const data = {
        name: formData.get('name'),
        phone: formData.get('phone'),
        address_line_1: formData.get('address_line_1'),
        address_line_2: formData.get('address_line_2'),
        city: formData.get('city'),
        state: formData.get('state'),
        postal_code: formData.get('postal_code'),
        country: formData.get('country'),
        payment_method: formData.get('payment_method'),
        notes: formData.get('notes')
    };

    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            const result = await response.json();
            const orderId = result.order?.order_id;
            const paymentMethod = data.payment_method;

            if (!orderId) {
                throw new Error('Order created but order ID is missing');
            }

            const paymentResponse = await fetch(paymentEndpoint(paymentMethod, orderId), {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const paymentData = await paymentResponse.json();

            if (!paymentResponse.ok) {
                throw new Error(paymentData.error || paymentData.message || 'Payment initiation failed');
            }

            if (paymentMethod === 'upi' && paymentData.upi_link) {
                window.open(paymentData.upi_link, '_blank');
            }

            let message = 'Order placed and payment initiated.';
            if (paymentMethod === 'bank_transfer') {
                message = 'Order placed. Please upload transfer proof from order details.';
            } else if (paymentMethod === 'upi') {
                message = 'Order placed. UPI payment link opened in a new tab.';
            }

            showNotification(message, 'success', 'Success');
            setTimeout(() => {
                window.location.href = `/orders/${orderId}`;
            }, 1500);
        } else {
            const error = await response.json();
            showNotification(error.error || 'Error placing order', 'error', 'Error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Error placing order', 'error', 'Error');
    } finally {
        placeOrderBtn.disabled = false;
        placeOrderBtn.classList.remove('opacity-60', 'cursor-not-allowed');
        placeOrderBtn.textContent = 'Place Order';
    }
}

// Load order summary on page load
document.addEventListener('DOMContentLoaded', function() {
    loadOrderSummary();
});

function loadOrderSummary() {
    // Fetch cart via API and display summary
    fetch('/api/cart', {
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        const cart = data || {};
        const cartItems = Array.isArray(cart.items) ? cart.items : [];
        
        const itemsContainer = document.getElementById('order-items');
        itemsContainer.innerHTML = '';
        
        cartItems.forEach(item => {
            const unitPrice = Number(item.price || item.product?.price || 0);
            const itemTotal = unitPrice * Number(item.quantity || 0);
            
            itemsContainer.innerHTML += `
                <div class="flex justify-between text-sm pb-2 border-b last:border-0">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">${item.product.name}</p>
                        <p class="text-xs text-gray-500">Qty: ${item.quantity}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600">₹${(itemTotal).toFixed(2)}</p>
                    </div>
                </div>
            `;
        });
        
        const subtotal = Number(cart.subtotal || 0);
        const tax = Number(cart.tax || (subtotal * 0.18));
        const shipping = subtotal >= 5000 ? 0 : 500;
        const total = Number(cart.total || (subtotal + tax)) + shipping;
        
        document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '₹' + tax.toFixed(2);
        document.getElementById('shipping').textContent = shipping === 0 ? 'FREE' : '₹' + shipping.toFixed(2);
        document.getElementById('total').textContent = '₹' + total.toFixed(2);
    })
    .catch(() => {
        document.getElementById('order-items').innerHTML = '<p class="text-sm text-gray-500">Unable to load cart summary.</p>';
    });
}

// Handle Stripe Payment
async function handleStripePayment(event) {
    event.preventDefault();
    const placeOrderBtn = document.getElementById('place-order-btn');
    placeOrderBtn.disabled = true;
    placeOrderBtn.classList.add('opacity-60', 'cursor-not-allowed');
    placeOrderBtn.textContent = 'Creating Order...';
    
    const formData = new FormData(document.getElementById('checkout-form'));
    const data = {
        name: formData.get('name'),
        phone: formData.get('phone'),
        address_line_1: formData.get('address_line_1'),
        address_line_2: formData.get('address_line_2'),
        city: formData.get('city'),
        state: formData.get('state'),
        postal_code: formData.get('postal_code'),
        country: formData.get('country'),
        payment_method: 'stripe',
        notes: formData.get('notes')
    };

    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            const result = await response.json();
            const orderId = result.order?.order_id;

            if (!orderId) {
                throw new Error('Order created but order ID is missing');
            }

            // Redirect to Stripe card form
            window.location.href = `/checkout/stripe/card-form?order_id=${orderId}`;
        } else {
            const error = await response.json();
            showNotification(error.error || 'Error placing order', 'error', 'Error');
            placeOrderBtn.disabled = false;
            placeOrderBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            placeOrderBtn.textContent = 'Place Order';
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Error placing order', 'error', 'Error');
        placeOrderBtn.disabled = false;
        placeOrderBtn.classList.remove('opacity-60', 'cursor-not-allowed');
        placeOrderBtn.textContent = 'Place Order';
    }
}
</script>
@endsection
