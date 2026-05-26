<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentMethod;
use App\PaymentMethods\PaymentMethodsManager;
use Illuminate\Http\Request;

class AdminPaymentMethodsController extends Controller
{
    /**
     * Show all payment methods
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('sort_order')->get();
        $manager = PaymentMethodsManager::getInstance();

        return view('admin.payments.payment-methods', compact('paymentMethods', 'manager'));
    }

    /**
     * Show payment method details
     */
    public function show(PaymentMethod $paymentMethod)
    {
        $manager = PaymentMethodsManager::getInstance();
        $methodInstance = $manager->get($paymentMethod->key);

        return view('admin.payments.payment-method-show', compact('paymentMethod', 'methodInstance'));
    }

    /**
     * Toggle enable/disable
     */
    public function toggle(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->is_enabled) {
            $paymentMethod->disable();
            $message = "{$paymentMethod->name} has been disabled";
        } else {
            $paymentMethod->enable();
            $message = "{$paymentMethod->name} has been enabled";
        }

        return redirect('/admin/payment-methods')
            ->with('success', $message);
    }

    /**
     * Set as default
     */
    public function setDefault(PaymentMethod $paymentMethod)
    {
        if (!$paymentMethod->is_enabled) {
            return redirect('/admin/payment-methods')
                ->with('error', 'Cannot set disabled method as default');
        }

        $paymentMethod->setAsDefault();

        return redirect('/admin/payment-methods')
            ->with('success', "{$paymentMethod->name} is now the default payment method");
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'sort_order' => 'integer|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $paymentMethod->update($validated);

        return redirect("/admin/payment-methods/{$paymentMethod->id}")
            ->with('success', 'Payment method settings updated');
    }

    /**
     * Reorder payment methods
     */
    public function reorder(Request $request)
    {
        $order = $request->validate(['order' => 'required|array'])['order'];

        foreach ($order as $index => $id) {
            PaymentMethod::find($id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
