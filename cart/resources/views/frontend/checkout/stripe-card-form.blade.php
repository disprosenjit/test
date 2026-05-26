@extends('layouts.app')

@section('title', 'Enter Card Details - Secure Payment')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Secure Card Payment</h1>
            <p class="text-gray-600">
                <i class="fas fa-lock text-green-600 mr-2"></i>
                Your payment information is encrypted and secure
            </p>
        </div>

        <!-- Order Summary -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-700">Order ID:</span>
                    <span class="font-semibold">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Amount:</span>
                    <span class="font-semibold text-blue-600 text-lg">₹{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Items:</span>
                    <span class="font-semibold">{{ $order->items()->count() }}</span>
                </div>
            </div>
        </div>

        <form id="stripe-payment-form" class="space-y-6">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <!-- Card Choice Tabs -->
            <div class="mb-6">
                <div class="flex gap-2 border-b border-gray-200">
                    <button type="button" class="card-choice-btn px-4 py-2 border-b-2 border-blue-600 font-semibold text-blue-600" data-choice="new">
                        <i class="fas fa-credit-card mr-2"></i>New Card
                    </button>
                    @if($savedCards->count() > 0)
                    <button type="button" class="card-choice-btn px-4 py-2 border-b-2 border-transparent text-gray-600 hover:text-gray-900" data-choice="saved">
                        <i class="fas fa-bookmark mr-2"></i>Saved Cards ({{ $savedCards->count() }})
                    </button>
                    @endif
                </div>
            </div>

            <!-- New Card Form -->
            <div id="new-card-section" class="space-y-4">
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Card details are processed securely through Stripe and are never stored on our servers.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cardholder Name *</label>
                    <input 
                        type="text" 
                        name="cardholder_name" 
                        id="cardholder_name"
                        placeholder="John Doe" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <span class="error-text text-red-500 text-sm hidden"></span>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Card Number *</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="card_number" 
                            id="card_number"
                            placeholder="4242 4242 4242 4242" 
                            maxlength="19"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <span id="card-brand" class="absolute right-3 top-3 text-gray-500">
                            <i class="fas fa-credit-card"></i>
                        </span>
                    </div>
                    <span class="error-text text-red-500 text-sm hidden"></span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Expiry Month *</label>
                        <input 
                            type="text" 
                            name="exp_month" 
                            id="exp_month"
                            placeholder="MM" 
                            maxlength="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <span class="error-text text-red-500 text-sm hidden"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Expiry Year *</label>
                        <input 
                            type="text" 
                            name="exp_year" 
                            id="exp_year"
                            placeholder="YYYY" 
                            maxlength="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <span class="error-text text-red-500 text-sm hidden"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">CVV *</label>
                        <input 
                            type="text" 
                            name="cvc" 
                            id="cvc"
                            placeholder="123" 
                            maxlength="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <span class="error-text text-red-500 text-sm hidden"></span>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            3 or 4 digits on the back of your card
                        </p>
                    </div>
                </div>

                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="save_card" class="w-4 h-4 text-blue-600">
                    <span class="text-sm text-gray-700">
                        <strong>Save this card for future purchases</strong>
                        <p class="text-xs text-gray-500 mt-1">You'll be able to quickly pay with this card next time</p>
                    </span>
                </label>
            </div>

            <!-- Saved Cards Section -->
            @if($savedCards->count() > 0)
            <div id="saved-cards-section" class="hidden space-y-4">
                <div class="space-y-3">
                    @foreach($savedCards as $card)
                    <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition">
                        <input 
                            type="radio" 
                            name="saved_card_id" 
                            value="{{ $card->id }}"
                            class="mt-1 w-4 h-4 text-blue-600"
                        />
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">
                                <i class="fas fa-{{ strtolower($card->brand) }} mr-2 text-blue-600"></i>
                                {{ $card->getDisplayName() }}
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                Expires: {{ $card->getExpiryDisplay() }}
                            </p>
                            @if($card->cardholder_name)
                            <p class="text-sm text-gray-500">
                                {{ $card->cardholder_name }}
                            </p>
                            @endif
                            @if($card->is_default)
                            <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                Default Card
                            </span>
                            @endif
                            @if($card->isExpired())
                            <span class="inline-block mt-2 px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">
                                EXPIRED
                            </span>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>

                <button type="button" class="w-full px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 font-semibold transition" onclick="switchCardChoice('new')">
                    <i class="fas fa-plus mr-2"></i>
                    Use a Different Card
                </button>
            </div>
            @endif

            <!-- Payment Terms -->
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <label class="flex items-start gap-3">
                    <input type="checkbox" id="agree-terms" class="mt-1 w-4 h-4 text-blue-600" required>
                    <span class="text-sm text-gray-700">
                        I authorize this payment and agree to the 
                        <a href="#" class="text-blue-600 hover:underline">terms and conditions</a>
                    </span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ url()->previous() }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
                <button type="submit" id="pay-button" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-semibold transition flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>
                    <span id="pay-button-text">Pay ₹{{ number_format($payment->amount, 2) }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Test Cards Info -->
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-blue-900">
            <strong>Test Mode:</strong> Use card <code class="bg-white px-2 py-1 rounded">4242 4242 4242 4242</code> 
            with any future expiry date and any 3-digit CVC for testing.
        </p>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

function switchCardChoice(choice) {
    document.querySelectorAll('.card-choice-btn').forEach(btn => {
        btn.classList.toggle('border-b-2');
        btn.classList.toggle('border-blue-600');
        btn.classList.toggle('text-blue-600');
        btn.classList.toggle('border-transparent');
        btn.classList.toggle('text-gray-600');
    });

    document.getElementById('new-card-section').classList.toggle('hidden');
    const savedSection = document.getElementById('saved-cards-section');
    if (savedSection) {
        savedSection.classList.toggle('hidden');
    }

    // Update form choice
    document.querySelector('input[name="card_choice"]').value = choice;
}

document.querySelectorAll('.card-choice-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const choice = btn.dataset.choice;
        if (choice === 'saved' && document.getElementById('saved-cards-section')) {
            switchCardChoice('saved');
        }
    });
});

// Card number formatting
document.getElementById('card_number')?.addEventListener('input', (e) => {
    let value = e.target.value.replace(/\s/g, '');
    let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formatted;

    // Detect card brand
    const brand = detectCardBrand(value);
    const brandEl = document.getElementById('card-brand');
    if (brand) {
        brandEl.innerHTML = `<span class="text-sm">${brand}</span>`;
    }
});

function detectCardBrand(number) {
    const patterns = {
        visa: /^4/,
        amex: /^3[47]/,
        mastercard: /^5[1-5]/,
        discover: /^6(?:011|5)/,
    };

    for (const [brand, pattern] of Object.entries(patterns)) {
        if (pattern.test(number)) {
            return brand.toUpperCase();
        }
    }
    return '';
}

// Form submission
document.getElementById('stripe-payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const payButton = document.getElementById('pay-button');
    payButton.disabled = true;
    payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

    const formData = new FormData(e.target);
    const cardChoice = document.querySelector('input[name="card_choice"]:checked')?.value || 'new';
    formData.set('card_choice', cardChoice);

    try {
        const response = await fetch('{{ route("checkout.stripe-process") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const data = await response.json();

        if (!response.ok) {
            showError(data.message || 'Payment failed. Please try again.');
            payButton.disabled = false;
            payButton.innerHTML = '<i class="fas fa-lock mr-2"></i><span id="pay-button-text">Pay ₹{{ number_format($payment->amount, 2) }}</span>';
            return;
        }

        if (data.success) {
            if (data.pending) {
                // Redirect for 3D Secure confirmation
                window.location.href = data.redirect;
            } else {
                // Payment succeeded
                showSuccess('Payment successful! Redirecting to your order...');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            }
        } else {
            showError(data.message || 'Payment failed. Please try again.');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('An error occurred. Please try again.');
    } finally {
        payButton.disabled = false;
        payButton.innerHTML = '<i class="fas fa-lock mr-2"></i><span id="pay-button-text">Pay ₹{{ number_format($payment->amount, 2) }}</span>';
    }
});

function showError(message) {
    alert(message);
    // You can replace this with a toast notification
}

function showSuccess(message) {
    console.log(message);
    // You can replace this with a toast notification
}

// Set card choice on load
const initialChoice = document.querySelector('input[name="card_choice"]');
if (!initialChoice) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'card_choice';
    input.value = 'new';
    document.getElementById('stripe-payment-form').appendChild(input);
}
</script>

<style>
input[type="text"], input[type="radio"], input[type="checkbox"] {
    font-family: 'Courier New', monospace;
}

#card_number {
    letter-spacing: 0.1em;
}
</style>
@endsection
