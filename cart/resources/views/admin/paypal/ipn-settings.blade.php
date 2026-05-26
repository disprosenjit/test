@extends('layouts.admin')

@section('title', 'IPN Settings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">PayPal IPN Configuration</h1>
        <p class="text-gray-600 mt-1">Webhook URL and setup instructions</p>
    </div>
    <a href="/admin/paypal-ipn/dashboard" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Webhook URL -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Your IPN Webhook URL</h2>
            <p class="text-sm text-gray-600 mb-4">Configure this URL in your PayPal account to receive payment notifications:</p>

            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                    <code class="font-mono text-sm break-all">{{ $webhookUrl }}</code>
                    <button type="button" onclick="copyToClipboard('{{ $webhookUrl }}')" class="ml-2 px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                        Copy
                    </button>
                </div>
            </div>

            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                <p class="text-sm text-yellow-800">
                    <strong>⚠ Important:</strong> Your site must be publicly accessible for PayPal to send webhooks. Local/private networks won't receive notifications.
                </p>
            </div>
        </div>

        <!-- Setup Instructions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Setup Instructions</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="font-bold mb-2">1. Log in to PayPal Developer Dashboard</h3>
                    <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1 ml-2">
                        <li>Go to <a href="https://developer.paypal.com/dashboard/" target="_blank" class="text-blue-600 hover:underline">PayPal Developer Dashboard</a></li>
                        <li>Sign in with your PayPal account</li>
                        <li>Select your app or create a new one</li>
                    </ol>
                </div>

                <div>
                    <h3 class="font-bold mb-2">2. Configure IPN Notifications</h3>
                    <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1 ml-2">
                        <li>Go to <strong>Account Settings</strong> or <strong>Sandbox/Live</strong> account settings</li>
                        <li>Navigate to <strong>Notifications</strong> → <strong>Instant Payment Notifications (IPN)</strong></li>
                        <li>Click <strong>Update</strong></li>
                    </ol>
                </div>

                <div>
                    <h3 class="font-bold mb-2">3. Register Your IPN Endpoint</h3>
                    <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1 ml-2">
                        <li>Paste the webhook URL above into the <strong>Notification URL</strong> field</li>
                        <li>Select <strong>All Transaction Types</strong> (or specific ones you want to track)</li>
                        <li>Click <strong>Save</strong></li>
                    </ol>
                </div>

                <div>
                    <h3 class="font-bold mb-2">4. Test Your Configuration</h3>
                    <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1 ml-2">
                        <li>In the IPN settings, you can send a test notification</li>
                        <li>Check the IPN Logs to see if it was received and verified</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Event Types -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Handled IPN Event Types</h2>
            <p class="text-sm text-gray-600 mb-4">The following PayPal events are automatically processed:</p>

            <div class="space-y-3">
                <div class="flex items-start border-l-4 border-green-500 pl-4 py-2">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">web_accept</p>
                        <p class="text-xs text-gray-600">Payment completed successfully</p>
                    </div>
                </div>

                <div class="flex items-start border-l-4 border-yellow-500 pl-4 py-2">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">pending</p>
                        <p class="text-xs text-gray-600">Payment is pending (holds, eChecks, etc.)</p>
                    </div>
                </div>

                <div class="flex items-start border-l-4 border-red-500 pl-4 py-2">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">failed</p>
                        <p class="text-xs text-gray-600">Payment failed</p>
                    </div>
                </div>

                <div class="flex items-start border-l-4 border-red-500 pl-4 py-2">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">denied</p>
                        <p class="text-xs text-gray-600">Payment was denied by PayPal</p>
                    </div>
                </div>

                <div class="flex items-start border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">refund</p>
                        <p class="text-xs text-gray-600">Payment was refunded</p>
                    </div>
                </div>

                <div class="flex items-start border-l-4 border-purple-500 pl-4 py-2">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">reversed</p>
                        <p class="text-xs text-gray-600">Payment was reversed/disputed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Troubleshooting -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Troubleshooting</h2>

            <div class="space-y-4">
                <div>
                    <p class="font-semibold text-sm mb-1">❌ Not receiving IPNs?</p>
                    <ul class="list-disc list-inside text-xs text-gray-700 space-y-1 ml-2">
                        <li>Verify your webhook URL is publicly accessible</li>
                        <li>Check your site's firewall isn't blocking PayPal IPs</li>
                        <li>Ensure the URL matches exactly what you configured in PayPal</li>
                        <li>Check server logs at /admin/paypal-ipn for any errors</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold text-sm mb-1">❌ IPNs marked "Invalid"?</p>
                    <ul class="list-disc list-inside text-xs text-gray-700 space-y-1 ml-2">
                        <li>Verify PayPal settings (environment: sandbox vs live)</li>
                        <li>Check IPN log details for specific error messages</li>
                        <li>Test with PayPal's IPN simulator first</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold text-sm mb-1">❌ Payments not updating?</p>
                    <ul class="list-disc list-inside text-xs text-gray-700 space-y-1 ml-2">
                        <li>IPN logs show if payment was found and linked</li>
                        <li>Check the payment record to see its current status</li>
                        <li>Look for processing errors in IPN log details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-blue-50 rounded-lg border border-blue-200 p-5">
            <h3 class="font-bold text-blue-900 mb-3">Quick Stats</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-blue-800">Total IPNs Received</span>
                    <span class="font-bold">{{ \Plugins\PaymentPayPal\Models\PaypalIpnLog::count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-800">Verified</span>
                    <span class="font-bold text-green-600">{{ \Plugins\PaymentPayPal\Models\PaypalIpnLog::where('verification_status', 'verified')->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-800">Pending</span>
                    <span class="font-bold text-yellow-600">{{ \Plugins\PaymentPayPal\Models\PaypalIpnLog::where('verification_status', 'pending')->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-800">Unprocessed</span>
                    <span class="font-bold text-orange-600">{{ \Plugins\PaymentPayPal\Models\PaypalIpnLog::where('processed', false)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="font-bold mb-3">Environment</h3>
            <div class="text-sm">
                <p class="text-gray-600">Current Mode</p>
                @php
                    $settings = \Plugins\PaymentPayPal\Models\PaypalSetting::getActive();
                @endphp
                <p class="font-bold {{ ($settings?->environment ?? 'sandbox') === 'live' ? 'text-red-600' : 'text-green-600' }}">
                    {{ ucfirst($settings?->environment ?? 'sandbox') }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="font-bold mb-3">Related Links</h3>
            <div class="space-y-2 text-sm">
                <a href="/admin/paypal-ipn/dashboard" class="block text-blue-600 hover:underline">IPN Dashboard</a>
                <a href="/admin/payments/paypal-ipn" class="block text-blue-600 hover:underline">All IPN Logs</a>
                <a href="/admin/payments/paypal-settings" class="block text-blue-600 hover:underline">PayPal Settings</a>
                <a href="https://developer.paypal.com/docs/classic/products/instant-payment-notification/" target="_blank" class="block text-blue-600 hover:underline">PayPal IPN Docs ↗</a>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
    showNotification('Webhook URL copied to clipboard!', 'success', 'Copied');
}
</script>
@endsection
