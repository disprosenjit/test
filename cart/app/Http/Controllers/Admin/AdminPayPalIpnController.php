<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\PaypalIpn;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminPayPalIpnController extends Controller
{
    /**
     * Display all IPN logs
     */
    public function index(Request $request)
    {
        $query = PaypalIpn::with(['order.user', 'payment']);

        // Filter by status
        if ($request->status) {
            if ($request->status === 'verified') {
                $query->where('verified', true);
            } elseif ($request->status === 'unverified') {
                $query->where('verified', false);
            } elseif ($request->status === 'processed') {
                $query->where('processed', true);
            } elseif ($request->status === 'unprocessed') {
                $query->where('processed', false);
            }
        }

        // Filter by payment status
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by transaction type
        if ($request->txn_type) {
            $query->where('txn_type', $request->txn_type);
        }

        // Search
        if ($request->search) {
            $query->where('txn_id', 'like', '%' . $request->search . '%')
                  ->orWhere('payer_email', 'like', '%' . $request->search . '%')
                  ->orWhere('invoice', 'like', '%' . $request->search . '%');
        }

        // Date range
        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $ipnLogs = $query->orderBy('created_at', 'desc')->paginate(50);
        $stats = $this->getStats();

        return view('admin.payments.paypal-ipn-logs', compact('ipnLogs', 'stats'));
    }

    /**
     * Show IPN details
     */
    public function show(PaypalIpn $ipnLog)
    {
        $ipnLog->load(['order.user', 'order.items', 'payment']);
        return view('admin.payments.paypal-ipn-show', compact('ipnLog'));
    }

    /**
     * View raw IPN data
     */
    public function viewRaw(PaypalIpn $ipnLog)
    {
        $rawData = json_decode($ipnLog->raw_data, true) ?? [];
        return view('admin.payments.paypal-ipn-raw', compact('ipnLog', 'rawData'));
    }

    /**
     * Verify IPN manually
     */
    public function verifyManually(PaypalIpn $ipnLog)
    {
        try {
            $rawData = json_decode($ipnLog->raw_data, true);
            $rawData['cmd'] = '_notify-validate';

            $verifyUrl = $ipnLog->test_ipn
                ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
                : 'https://www.paypal.com/cgi-bin/webscr';

            $response = $this->curlVerifyIpn($verifyUrl, $rawData);

            if ($response === 'VERIFIED') {
                $ipnLog->update(['verified' => true, 'error_message' => null]);
                return back()->with('success', 'IPN verified successfully');
            } else {
                return back()->with('error', 'Verification failed: ' . $response);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Verification error: ' . $e->getMessage());
        }
    }

    /**
     * Mark IPN as invalid
     */
    public function markInvalid(PaypalIpn $ipnLog)
    {
        $ipnLog->update([
            'verified' => false,
            'error_message' => 'Manually marked as invalid',
        ]);

        return back()->with('success', 'IPN marked as invalid');
    }

    /**
     * Retry processing
     */
    public function retry(PaypalIpn $ipnLog)
    {
        if (!$ipnLog->verified) {
            return back()->with('error', 'Cannot retry unverified IPN');
        }

        try {
            $ipnLog->update(['processed' => false]);
            $this->processPayment($ipnLog);

            return back()->with('success', 'IPN reprocessed');
        } catch (\Exception $e) {
            return back()->with('error', 'Reprocessing error: ' . $e->getMessage());
        }
    }

    /**
     * IPN settings page
     */
    public function settings()
    {
        return view('admin.payments.paypal-ipn-settings');
    }

    /**
     * Show IPN dashboard/summary
     */
    public function dashboard(Request $request)
    {
        $from = $request->from ? date('Y-m-d', strtotime($request->from)) : now()->subDays(30)->toDateString();
        $to = $request->to ? date('Y-m-d', strtotime($request->to)) : now()->toDateString();

        $stats = [
            'total_ipns' => PaypalIpn::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'verified' => PaypalIpn::verified()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'unverified' => PaypalIpn::unverified()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'processed' => PaypalIpn::processed()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'unprocessed' => PaypalIpn::unprocessed()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'completed' => PaypalIpn::completed()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'failed' => PaypalIpn::failed()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'refunded' => PaypalIpn::refunded()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->count(),
            'total_amount' => PaypalIpn::completed()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->sum('mc_gross'),
            'total_fees' => PaypalIpn::completed()->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->sum('mc_fee'),
        ];

        // Recent IPNs
        $recentIpns = PaypalIpn::with(['order', 'payment'])->orderBy('created_at', 'desc')->limit(10)->get();

        // By type
        $byType = PaypalIpn::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('txn_type')
            ->selectRaw('txn_type, COUNT(*) as count')
            ->get();

        // By status
        $byStatus = PaypalIpn::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->groupBy('payment_status')
            ->selectRaw('payment_status, COUNT(*) as count')
            ->get();

        return view('admin.payments.paypal-ipn-dashboard', compact('stats', 'recentIpns', 'byType', 'byStatus', 'from', 'to'));
    }

    /**
     * Get overall statistics
     */
    private function getStats()
    {
        return [
            'total_ipns' => PaypalIpn::count(),
            'verified' => PaypalIpn::where('verified', true)->count(),
            'unverified' => PaypalIpn::where('verified', false)->count(),
            'processed' => PaypalIpn::where('processed', true)->count(),
            'unprocessed' => PaypalIpn::where('processed', false)->count(),
            'completed' => PaypalIpn::whereIn('payment_status', ['Completed', 'Processed'])->count(),
            'failed' => PaypalIpn::whereIn('payment_status', ['Failed', 'Denied', 'Expired', 'Voided'])->count(),
            'pending' => PaypalIpn::where('payment_status', 'Pending')->count(),
            'total_amount' => PaypalIpn::whereIn('payment_status', ['Completed', 'Processed'])->sum('mc_gross'),
        ];
    }

    /**
     * Send verification request to PayPal
     */
    private function curlVerifyIpn(string $url, array $data): string
    {
        $postData = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('cURL error: ' . $error);
        }

        return trim($response);
    }

    /**
     * Process payment based on IPN data
     */
    private function processPayment(PaypalIpn $ipnRecord): void
    {
        if (!$ipnRecord->order_id) {
            return;
        }

        $order = $ipnRecord->order;
        if (!$order) {
            return;
        }

        $payment = $ipnRecord->payment_id 
            ? Payment::find($ipnRecord->payment_id)
            : Payment::where('order_id', $order->id)->where('method', 'paypal')->first();

        if (!$payment) {
            $payment = Payment::create([
                'order_id' => $order->id,
                'method' => 'paypal',
                'status' => $this->mapPaypalStatusToPaymentStatus($ipnRecord->payment_status),
                'amount' => $ipnRecord->mc_gross ?? $order->total,
                'transaction_reference' => $ipnRecord->txn_id,
                'metadata' => [
                    'ipn_id' => $ipnRecord->id,
                    'payer_email' => $ipnRecord->payer_email,
                ],
            ]);
            $ipnRecord->update(['payment_id' => $payment->id]);
        } else {
            $payment->update([
                'status' => $this->mapPaypalStatusToPaymentStatus($ipnRecord->payment_status),
            ]);
        }

        $ipnRecord->update(['processed' => true]);

        if ($ipnRecord->isCompleted()) {
            if ($order->status === 'pending') {
                $order->update(['status' => 'confirmed']);
            }
            if ($payment->status !== 'approved') {
                $payment->update(['status' => 'approved']);
            }
        } elseif ($ipnRecord->isFailed()) {
            if ($order->status === 'pending') {
                $order->update(['status' => 'cancelled']);
            }
            $payment->update(['status' => 'rejected']);
        } elseif ($ipnRecord->isRefunded()) {
            $payment->update(['status' => 'refunded']);
        }
    }

    /**
     * Map PayPal payment status
     */
    private function mapPaypalStatusToPaymentStatus(string $paypalStatus): string
    {
        return match ($paypalStatus) {
            'Completed', 'Processed' => 'approved',
            'Pending' => 'pending',
            'Failed', 'Denied', 'Expired', 'Voided' => 'rejected',
            'Refunded', 'Reversed', 'Canceled_Reversal' => 'refunded',
            default => 'pending',
        };
    }
}
