<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\PayPalIpnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalIpnController extends Controller
{
    /**
     * Handle incoming PayPal IPN webhook
     * This endpoint is called by PayPal whenever a payment event occurs
     * Does NOT require authentication
     */
    public function handle(Request $request)
    {
        try {
            // Get all POST data from PayPal
            $postData = $request->all();

            // Log the raw request for debugging
            Log::info('PayPal IPN received', [
                'txn_id' => $postData['txn_id'] ?? null,
                'txn_type' => $postData['txn_type'] ?? null,
                'payment_status' => $postData['payment_status'] ?? null,
            ]);

            // Validate required fields
            if (!isset($postData['txn_type']) || !isset($postData['payment_status'])) {
                Log::warning('PayPal IPN missing required fields', $postData);
                return response('Missing required fields', 400);
            }

            // Process IPN
            $ipnService = new PayPalIpnService();
            $processed = $ipnService->processIpn($postData);

            if ($processed) {
                // Always return 200 OK to PayPal to acknowledge receipt
                return response('IPN verified', 200);
            } else {
                // Still return 200 to prevent PayPal from retrying
                // but log the failure
                Log::error('PayPal IPN verification failed', [
                    'txn_id' => $postData['txn_id'] ?? null,
                    'txn_type' => $postData['txn_type'] ?? null,
                ]);
                return response('IPN verification failed', 200);
            }
        } catch (\Exception $e) {
            Log::error('PayPal IPN handler exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return 200 to avoid PayPal spam
            return response('Handler error', 200);
        }
    }
}
