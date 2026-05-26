<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment\Payment;
use App\Models\Commerce\Order;
use Plugins\PaymentStripe\Models\StripeSetting;
use Plugins\PaymentStripe\Models\StripeRefund;
use Plugins\PaymentPayPal\Models\PaypalSetting;
use Plugins\PaymentStripe\Services\StripeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminPaymentController extends Controller
{
    /**
     * Display all payments
     */
    public function index(Request $request)
    {
        $query = Payment::with(['order.user']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by method
        if ($request->method) {
            $query->where('method', $request->method);
        }

        // Search by order ID or reference
        if ($request->search) {
            $query->where('order_id', $request->search)
                  ->orWhere('transaction_reference', 'like', '%' . $request->search . '%');
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);
        $stats = $this->getPaymentStats();

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $payment->load(['order.user', 'order.items']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show Stripe payments
     */
    public function stripe(Request $request)
    {
        $payments = Payment::with(['order.user'])
            ->where('method', 'stripe')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Payment::where('method', 'stripe')->sum('amount'),
            'pending' => Payment::where('method', 'stripe')->where('status', 'pending')->count(),
            'approved' => Payment::where('method', 'stripe')->where('status', 'approved')->count(),
            'failed' => Payment::where('method', 'stripe')->whereIn('status', ['failed', 'rejected'])->count(),
        ];

        return view('admin.payments.stripe', compact('payments', 'stats'));
    }

    /**
     * Show PayPal payments
     */
    public function paypal(Request $request)
    {
        $payments = Payment::with(['order.user'])
            ->where('method', 'paypal')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Payment::where('method', 'paypal')->sum('amount'),
            'pending' => Payment::where('method', 'paypal')->where('status', 'pending')->count(),
            'approved' => Payment::where('method', 'paypal')->where('status', 'approved')->count(),
            'failed' => Payment::where('method', 'paypal')->whereIn('status', ['failed', 'rejected'])->count(),
        ];

        return view('admin.payments.paypal', compact('payments', 'stats'));
    }

    /**
     * Show bank transfer verification
     */
    public function verifyBank(Payment $payment)
    {
        if ($payment->method !== 'bank_transfer') {
            return redirect("/admin/payments/$payment->id")->with('error', 'Not a bank transfer payment');
        }

        return view('admin.payments.verify-bank', compact('payment'));
    }

    /**
     * Approve payment
     */
    public function approve(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string'
        ]);

        $payment->update([
            'status' => 'approved',
            'verified_at' => now(),
        ]);

        // Update order status if pending
        if ($payment->order && $payment->order->status === 'pending') {
            $payment->order->update(['status' => 'confirmed']);
        }

        return redirect("/admin/payments/$payment->id")
            ->with('success', 'Payment approved successfully');
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $payment->update([
            'status' => 'rejected',
            'verified_at' => now(),
        ]);

        // Cancel order if payment rejected
        if ($payment->order) {
            $payment->order->update(['status' => 'cancelled']);
        }

        return redirect("/admin/payments/$payment->id")
            ->with('success', 'Payment rejected');
    }

    /**
     * View bank transfer proof
     */
    public function viewProof(Payment $payment)
    {
        if (!$payment->proof_file_path) {
            abort(404);
        }

        return Storage::download($payment->proof_file_path);
    }

    /**
     * Get payment statistics
     */
    private function getPaymentStats()
    {
        return [
            'total' => Payment::sum('amount'),
            'total_received' => Payment::where('status', 'approved')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->count(),
            'approved' => Payment::where('status', 'approved')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
            'by_method' => Payment::groupBy('method')
                ->selectRaw('method, COUNT(*) as count, SUM(amount) as total')
                ->get()
        ];
    }

    /**
     * Generate payment report
     */
    public function report(Request $request)
    {
        $query = Payment::with(['order.user']);

        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'payment_report_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, ['Order ID', 'Customer', 'Amount', 'Method', 'Status', 'Date']);

        foreach ($payments as $payment) {
            fputcsv($handle, [
                $payment->order_id,
                $payment->order->user->name,
                $payment->amount,
                $payment->method,
                $payment->status,
                $payment->created_at->format('Y-m-d H:i')
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }

    /**
     * Show Stripe settings configuration page
     */
    public function stripeSettings()
    {
        $settings = StripeSetting::first();
        return view('admin.payments.stripe-settings', compact('settings'));
    }

    /**
     * Store/Update Stripe settings
     */
    public function updateStripeSettings(Request $request)
    {
        $validated = $request->validate([
            'publishable_key' => 'required|string',
            'secret_key' => 'required|string',
            'webhook_secret' => 'nullable|string',
            'environment' => 'required|in:test,live',
            'is_active' => 'nullable|boolean',
            'business_name' => 'nullable|string|max:255',
            'business_email' => 'nullable|email',
            'currency' => 'required|string|size:3',
            'minimum_amount' => 'required|numeric|min:0.01',
            'maximum_amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['configured_by'] = auth()->id();
        $validated['configured_at'] = now();

        // Delete existing settings and create new one (to ensure single source)
        StripeSetting::truncate();
        
        $settings = StripeSetting::create($validated);

        return redirect('/admin/payments/stripe-settings')
            ->with('success', 'Stripe settings updated successfully');
    }

    /**
     * Show Stripe refunds list
     */
    public function stripeRefunds(Request $request)
    {
        $query = StripeRefund::with(['payment.order', 'processedByUser']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->search) {
            $query->where('stripe_refund_id', 'like', '%' . $request->search . '%')
                  ->orWhere('stripe_charge_id', 'like', '%' . $request->search . '%');
        }

        $refunds = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total_refunded' => StripeRefund::sum('amount'),
            'pending' => StripeRefund::where('status', 'pending')->count(),
            'completed' => StripeRefund::where('status', 'completed')->count(),
            'failed' => StripeRefund::where('status', 'failed')->count(),
        ];

        return view('admin.payments.stripe-refunds', compact('refunds', 'stats'));
    }

    /**
     * Show refund details
     */
    public function showRefund(StripeRefund $refund)
    {
        $refund->load(['payment.order', 'processedByUser']);
        return view('admin.payments.stripe-refund-show', compact('refund'));
    }

    /**
     * Process full refund
     */
    public function refundFull(Request $request, Payment $payment)
    {
        if ($payment->method !== 'stripe') {
            return response()->json([
                'success' => false,
                'message' => 'This payment is not a Stripe payment'
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        $stripeService = new StripeService($payment->order);
        $result = $stripeService->refund($payment, $validated['reason'] ?? null);

        if ($result['success']) {
            $stripeRefund = StripeRefund::where('payment_id', $payment->id)
                ->where('stripe_refund_id', $result['stripe_refund_id'])
                ->first();

            if ($stripeRefund && isset($validated['notes'])) {
                $stripeRefund->update(['notes' => $validated['notes']]);
            }

            return redirect("/admin/payments/{$payment->id}")
                ->with('success', 'Full refund processed successfully');
        }

        return redirect("/admin/payments/{$payment->id}")
            ->with('error', 'Failed to process refund: ' . $result['message']);
    }

    /**
     * Process partial refund
     */
    public function refundPartial(Request $request, Payment $payment)
    {
        if ($payment->method !== 'stripe') {
            return response()->json([
                'success' => false,
                'message' => 'This payment is not a Stripe payment'
            ], 422);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        if ($validated['amount'] > $payment->amount) {
            return back()->with('error', 'Refund amount cannot exceed payment amount');
        }

        $stripeService = new StripeService($payment->order);
        $result = $stripeService->partialRefund(
            $payment,
            $validated['amount'],
            $validated['reason'] ?? null
        );

        if ($result['success']) {
            $stripeRefund = StripeRefund::where('payment_id', $payment->id)
                ->where('stripe_refund_id', $result['stripe_refund_id'])
                ->first();

            if ($stripeRefund && isset($validated['notes'])) {
                $stripeRefund->update(['notes' => $validated['notes']]);
            }

            return redirect("/admin/payments/{$payment->id}")
                ->with('success', "Partial refund of {$validated['amount']} processed successfully");
        }

        return redirect("/admin/payments/{$payment->id}")
            ->with('error', 'Failed to process refund: ' . $result['message']);
    }

    /**
     * Accept Stripe payment manually (for custom scenarios)
     */
    public function acceptPayment(Request $request, Payment $payment)
    {
        if ($payment->method !== 'stripe') {
            return response()->json([
                'success' => false,
                'message' => 'This payment is not a Stripe payment'
            ], 422);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $payment->markAsApproved();

        if ($payment->order) {
            $payment->order->update(['payment_status' => 'approved']);
            if ($payment->order->status === 'pending') {
                $payment->order->update(['status' => 'confirmed']);
            }
        }

        return redirect("/admin/payments/{$payment->id}")
            ->with('success', 'Payment accepted and recorded successfully');
    }

    /**
     * Reject Stripe payment
     */
    public function rejectPayment(Request $request, Payment $payment)
    {
        if ($payment->method !== 'stripe') {
            return response()->json([
                'success' => false,
                'message' => 'This payment is not a Stripe payment'
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'gateway_response' => json_encode([
                'rejected' => true,
                'reason' => $validated['reason'],
                'rejected_at' => now(),
            ]),
        ]);

        if ($payment->order) {
            $payment->order->update(['payment_status' => 'failed']);
        }

        return redirect("/admin/payments/{$payment->id}")
            ->with('success', 'Payment rejected');
    }

    /**
     * Verify Stripe configuration
     */
    public function verifyStripeConfig()
    {
        $isConfigured = StripeSetting::isConfigured();
        $settings = StripeSetting::getActive();

        if (!$isConfigured) {
            return response()->json([
                'success' => false,
                'configured' => false,
                'message' => 'Stripe is not configured'
            ]);
        }

        return response()->json([
            'success' => true,
            'configured' => true,
            'environment' => $settings->environment,
            'business_name' => $settings->business_name,
            'currency' => $settings->currency,
            'message' => 'Stripe is properly configured'
        ]);
    }

    /**
     * Get Stripe payment statistics
     */
    public function stripeStats()
    {
        $stats = [
            'total_transactions' => Payment::where('method', 'stripe')->count(),
            'total_amount' => Payment::where('method', 'stripe')->sum('amount'),
            'successful' => Payment::where('method', 'stripe')->where('status', 'approved')->count(),
            'pending' => Payment::where('method', 'stripe')->where('status', 'pending')->count(),
            'failed' => Payment::where('method', 'stripe')->whereIn('status', ['failed', 'rejected'])->count(),
            'total_refunded' => StripeRefund::sum('amount'),
            'refunds_count' => StripeRefund::count(),
            'recent_payments' => Payment::where('method', 'stripe')
                ->with(['order.user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Export Stripe payments report
     */
    public function exportStripeReport(Request $request)
    {
        $query = Payment::with(['order.user', 'stripeRefunds'])
            ->where('method', 'stripe');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        $filename = 'stripe_report_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, [
            'Order ID',
            'Customer',
            'Email',
            'Amount',
            'Status',
            'Charge ID',
            'Refund Count',
            'Total Refunded',
            'Date'
        ]);

        foreach ($payments as $payment) {
            $refundCount = $payment->stripeRefunds()->count();
            $totalRefunded = $payment->stripeRefunds()->sum('amount');

            fputcsv($handle, [
                $payment->order_id,
                $payment->order?->user?->name ?? 'N/A',
                $payment->order?->user?->email ?? 'N/A',
                number_format($payment->amount, 2),
                $payment->status,
                $payment->transaction_reference,
                $refundCount,
                number_format($totalRefunded, 2),
                $payment->created_at->format('Y-m-d H:i:s')
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }

    /**
     * Show PayPal settings configuration page
     */
    public function paypalSettings()
    {
        $settings = PaypalSetting::first();
        return view('admin.payments.paypal-settings', compact('settings'));
    }

    /**
     * Store/Update PayPal settings
     */
    public function updatePaypalSettings(Request $request)
    {
        $validated = $request->validate([
            'client_id'      => 'required|string',
            'client_secret'  => 'required|string',
            'environment'    => 'required|in:sandbox,live',
            'is_active'      => 'nullable|boolean',
            'currency'       => 'required|string|size:3',
            'minimum_amount' => 'required|numeric|min:0.01',
            'maximum_amount' => 'required|numeric|min:0.01',
            'business_name'  => 'nullable|string|max:255',
            'business_email' => 'nullable|email',
            'notes'          => 'nullable|string',
        ]);

        $validated['is_active']      = $request->has('is_active');
        $validated['configured_by']  = auth()->id();
        $validated['configured_at']  = now();

        PaypalSetting::truncate();
        PaypalSetting::create($validated);

        return redirect('/admin/payments/paypal-settings')
            ->with('success', 'PayPal settings updated successfully');
    }
}

