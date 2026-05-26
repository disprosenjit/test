@extends('layouts.app')

@section('title', 'My Profile - Ship Spare Parts Store')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-6 border-b">
            <h1 class="text-3xl font-bold">My Profile</h1>
            <p class="text-gray-600 mt-1">Manage your account and billing information.</p>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mx-6 mt-6 success-message border-l-4 border-green-500 bg-green-50 text-green-800 px-6 py-4 rounded-r-lg flex items-start gap-3 shadow-sm">
                <i class="fas fa-check-circle text-green-500 text-xl mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="font-semibold">Success</p>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mx-6 mt-6 error-message border-l-4 border-red-500 bg-red-50 text-red-800 px-6 py-4 rounded-r-lg shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="font-semibold">Error</p>
                        <ul class="list-disc pl-5 space-y-1 mt-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="bg-gradient-to-r from-gray-50 to-white border-b">
            <nav class="flex" aria-label="Tabs">
                <button onclick="switchTab('personal')" id="personal-tab" 
                        class="tab-button active group relative px-6 py-4 font-semibold text-sm transition-all duration-300 whitespace-nowrap">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-user text-lg"></i>
                        <span>Personal Information</span>
                    </span>
                    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-300 tab-indicator"></div>
                </button>
                <button onclick="switchTab('billing')" id="billing-tab" 
                        class="tab-button group relative px-6 py-4 font-semibold text-sm transition-all duration-300 whitespace-nowrap text-gray-600 hover:text-gray-900">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-lg"></i>
                        <span>Billing Address</span>
                    </span>
                    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-300 tab-indicator opacity-0"></div>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="px-6 py-8">
            <!-- Personal Information Tab -->
            <div id="personal-content" class="tab-content transition-all duration-500 ease-in-out" style="opacity: 1; transform: translateY(0);">
                <form method="POST" action="/profile" class="space-y-6 max-w-2xl">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-semibold mb-2 text-gray-700">Full Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            placeholder="Enter your full name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                        >
                        @error('name')
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2 text-gray-700">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            value="{{ $user->email }}"
                            disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                        >
                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-lock text-gray-400"></i>
                            Email cannot be changed from this page.
                        </p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold mb-2 text-gray-700">Phone</label>
                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            required
                            placeholder="Enter your phone number"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('phone') border-red-500 @enderror"
                        >
                        @error('phone')
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold flex items-center gap-2 shadow-md hover:shadow-lg">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                        <a href="/" class="text-gray-600 hover:text-gray-900 font-medium transition duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Back to Home
                        </a>
                    </div>
                </form>
            </div>

            <!-- Billing Address Tab -->
            <div id="billing-content" class="tab-content hidden transition-all duration-500 ease-in-out" style="opacity: 0; transform: translateY(10px);">
                @if($billingAddress)
                    <div class="current-address-box mb-8 p-6 rounded-lg border border-sky-200 shadow-sm">
                        <div class="flex items-start gap-3 mb-4">
                            <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">Current Billing Address</h3>
                                <p class="text-sm text-gray-600 mt-1">This address will be used for billing</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p class="text-gray-700"><strong class="text-gray-900">{{ $billingAddress->name }}</strong></p>
                            <p class="text-gray-700">📞 {{ $billingAddress->phone }}</p>
                            <p class="text-gray-700">📍 {{ $billingAddress->address_line_1 }}
                                @if($billingAddress->address_line_2), {{ $billingAddress->address_line_2 }}@endif
                            </p>
                            <p class="text-gray-700">{{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->postal_code }}</p>
                            <p class="text-gray-700">{{ $billingAddress->country }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="/profile/billing-address" class="space-y-6 max-w-2xl">
                    @csrf

                    @if($billingAddress)
                        <input type="hidden" name="address_id" value="{{ $billingAddress->id }}">
                    @endif

                    <div class="form-grid two-col">
                        <div>
                            <label for="b_name" class="block text-sm font-semibold mb-2 text-gray-700">Full Name <span class="text-red-500">*</span></label>
                            <input
                                id="b_name"
                                name="name"
                                type="text"
                                required
                                placeholder="Enter your full name"
                                value="{{ old('name', $billingAddress?->name ?? '') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                            >
                            @error('name')
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="b_phone" class="block text-sm font-semibold mb-2 text-gray-700">Phone <span class="text-red-500">*</span></label>
                            <input
                                id="b_phone"
                                name="phone"
                                type="text"
                                required
                                placeholder="Enter your phone number"
                                value="{{ old('phone', $billingAddress?->phone ?? '') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('phone') border-red-500 @enderror"
                            >
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="b_company" class="block text-sm font-semibold mb-2 text-gray-700">Company Name <span class="text-gray-400 text-xs">(Optional)</span></label>
                        <input
                            id="b_company"
                            name="company_name"
                            type="text"
                            placeholder="Enter company name if applicable"
                            value="{{ old('company_name', $billingAddress?->company_name ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>

                    <div>
                        <label for="b_address1" class="block text-sm font-semibold mb-2 text-gray-700">Address Line 1 <span class="text-red-500">*</span></label>
                        <input
                            id="b_address1"
                            name="address_line_1"
                            type="text"
                            required
                            placeholder="Street address"
                            value="{{ old('address_line_1', $billingAddress?->address_line_1 ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('address_line_1') border-red-500 @enderror"
                        >
                        @error('address_line_1')
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="b_address2" class="block text-sm font-semibold mb-2 text-gray-700">Address Line 2 <span class="text-gray-400 text-xs">(Optional)</span></label>
                        <input
                            id="b_address2"
                            name="address_line_2"
                            type="text"
                            placeholder="Apartment, suite, etc."
                            value="{{ old('address_line_2', $billingAddress?->address_line_2 ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>

                    <div class="form-grid two-col">
                        <div>
                            <label for="b_city" class="block text-sm font-semibold mb-2 text-gray-700">City <span class="text-red-500">*</span></label>
                            <input
                                id="b_city"
                                name="city"
                                type="text"
                                required
                                placeholder="City"
                                value="{{ old('city', $billingAddress?->city ?? '') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('city') border-red-500 @enderror"
                            >
                            @error('city')
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="b_state" class="block text-sm font-semibold mb-2 text-gray-700">State/Province <span class="text-red-500">*</span></label>
                            <input
                                id="b_state"
                                name="state"
                                type="text"
                                required
                                placeholder="State or Province"
                                value="{{ old('state', $billingAddress?->state ?? '') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('state') border-red-500 @enderror"
                            >
                            @error('state')
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-grid two-col">
                        <div>
                            <label for="b_postal" class="block text-sm font-semibold mb-2 text-gray-700">Postal Code <span class="text-red-500">*</span></label>
                            <input
                                id="b_postal"
                                name="postal_code"
                                type="text"
                                required
                                placeholder="Postal code"
                                value="{{ old('postal_code', $billingAddress?->postal_code ?? '') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('postal_code') border-red-500 @enderror"
                            >
                            @error('postal_code')
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="b_country" class="block text-sm font-semibold mb-2 text-gray-700">Country <span class="text-red-500">*</span></label>
                            <input
                                id="b_country"
                                name="country"
                                type="text"
                                required
                                placeholder="Country"
                                value="{{ old('country', $billingAddress?->country ?? '') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('country') border-red-500 @enderror"
                            >
                            @error('country')
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold flex items-center gap-2 shadow-md hover:shadow-lg">
                            <i class="fas fa-save"></i>
                            Save Billing Address
                        </button>
                        <button type="button" onclick="switchTab('personal')" class="text-gray-600 hover:text-gray-900 font-medium transition duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 flex items-start gap-2">
                        <i class="fas fa-info-circle mt-0.5 text-blue-500"></i>
                        <span><strong>Note:</strong> Required fields are marked with <span class="text-red-500">*</span>. Your billing address will be automatically updated when you place orders with a new address.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab contents with fade-out effect
    document.querySelectorAll('.tab-content').forEach(el => {
        if (!el.classList.contains('hidden')) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(10px)';
            setTimeout(() => {
                el.classList.add('hidden');
            }, 300);
        }
    });
    
    // Remove active class styling from all buttons
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('active', 'text-blue-600');
        el.classList.add('text-gray-600', 'hover:text-gray-900');
        
        // Hide all tab indicators
        const indicator = el.querySelector('.tab-indicator');
        if (indicator) {
            indicator.classList.add('opacity-0');
        }
    });
    
    // Show selected tab content with fade-in effect
    const contentEl = document.getElementById(tabName + '-content');
    if (contentEl) {
        contentEl.classList.remove('hidden');
        // Trigger reflow to enable transition
        void contentEl.offsetWidth;
        contentEl.style.opacity = '1';
        contentEl.style.transform = 'translateY(0)';
    }
    
    // Add active class to clicked button
    const tabEl = document.getElementById(tabName + '-tab');
    if (tabEl) {
        tabEl.classList.add('active', 'text-blue-600');
        tabEl.classList.remove('text-gray-600', 'hover:text-gray-900');
        
        // Show tab indicator
        const indicator = tabEl.querySelector('.tab-indicator');
        if (indicator) {
            indicator.classList.remove('opacity-0');
        }
    }
}
</script>

<style>
.tab-button {
    position: relative;
}

.tab-button .tab-indicator {
    transition: opacity 300ms ease-in-out;
}

.tab-content {
    animation: fadeIn 500ms ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Smooth input focus effects */
.tab-content input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.tab-content label {
    transition: color 200ms ease;
}

.tab-content input:focus + label,
.tab-content input:not(:placeholder-shown) + label {
    color: #2563eb;
}

/* Button hover effects */
.tab-content button[type="submit"] {
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
    transition: all 300ms ease;
}

.tab-content button[type="submit"]:hover {
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.4);
    transform: translateY(-2px);
}

/* Current address display styling */
.current-address-box {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-left: 4px solid #0284c7;
}

/* Form grid enhancement */
.form-grid {
    display: grid;
    gap: 1.5rem;
}

.form-grid.two-col {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}

/* Message styling */
.success-message {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-left: 4px solid #16a34a;
}

.error-message {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-left: 4px solid #dc2626;
}
</style>
@endsection


