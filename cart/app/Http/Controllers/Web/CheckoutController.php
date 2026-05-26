<?php

namespace App\Http\Controllers\Web;

use App\Models\User\Address;
use App\Models\Commerce\Cart;
use App\Helpers\PaymentMethodHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->active()
            ->latest()
            ->firstOrFail();
        
        if ($cart->items()->count() === 0) {
            return redirect('/cart')->with('error', 'Your cart is empty');
        }

        // Get user's unique shipping addresses
        $addresses = Address::where('user_id', auth()->id())
            ->shipping()
            ->distinct()
            ->get();
        
        // Get enabled payment methods
        $enabledMethods = PaymentMethodHelper::getEnabledForView();

        return view('frontend.checkout.index', compact('addresses', 'enabledMethods'));
    }

    /**
     * Store order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
            'payment_method' => 'required|in:bank_transfer,upi,credit_card,debit_card,stripe,paypal',
            'notes' => 'nullable|string',
        ]);

        // The actual order creation happens via API
        // This is just for form validation
        return redirect('/api/orders')->with('validated_data', $validated);
    }
}
