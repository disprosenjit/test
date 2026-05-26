<?php

namespace App\Http\Controllers\Web;

use App\Models\User\User;
use App\Models\User\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function loginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if (Auth::attempt($validated, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show register form
     */
    public function registerForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'is_active' => true
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registration successful!');
    }

    /**
     * Show profile
     */
    public function profile()
    {
        $user = auth()->user();
        $billingAddress = $user->addresses()
            ->whereIn('type', ['billing', 'both'])
            ->first();
        
        return view('frontend.auth.profile', compact('user', 'billingAddress'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone,' . auth()->id(),
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Update or create billing address
     */
    public function saveBillingAddress(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|integer|exists:addresses,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $user = auth()->user();
        
        if ($request->address_id) {
            // Update existing address
            $address = Address::where('id', $request->address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            
            $address->update([
                'type' => 'billing',
                ...$validated,
            ]);
        } else {
            // Create new billing address
            Address::create([
                'user_id' => $user->id,
                'type' => 'billing',
                ...$validated,
            ]);
        }

        return back()->with('success', 'Billing address saved successfully');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out');
    }
}
