@extends('layouts.admin')

@section('title', 'PayPal IPN Settings')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">PayPal IPN Configuration</h1>
    <p class="text-gray-600 mt-1">Configure your PayPal IPN webhook settings</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Webhook URL</h2>
            <div class="bg-gray-50 p-4 rounded border border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Copy this URL to your PayPal IPN settings:</p>
                <div class="bg-white p-3 rounded border font-mono text-sm break-all">
                    {{ url('/webhook/paypal/ipn') }}
                </div>
                <button onclick="copyWebhookUrl()" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    <i class="fas fa-copy mr-2"></i>Copy URL
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Configuration Steps</h2>
            <ol class="space-y-4 text-sm">
                <li class="flex gap-4">
                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0">1</span>
                    <div>
                        <p class="font-semibold">Go to PayPal Developer Dashboard</p>
                        <p class="text-gray-600 text-xs mt-1">
                            Visit <a href="https://developer.paypal.com/dashboard/" target="_blank" class="text-blue-600 hover:underline">https://developer.paypal.com/dashboard/</a>
                        </p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0">2</span>
                    <div>
                        <p class="font-semibold">Navigate to IPN Settings</p>
                        <p class="text-gray-600 text-xs mt-1">
                            Go to: Account Settings → Notifications → Instant Payment Notifications (IPN)
                        </p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0">3</span>
                    <div>
                        <p class="font-semibold">Enter the Webhook URL</p>
                        <p class="text-gray-600 text-xs mt-1">
                            Paste the URL above in the "Notification URL" field
                        </p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0">4</span>
                    <div>
                        <p class="font-semibold">Select Event Types</p>
                        <p class="text-gray-600 text-xs mt-1">
                            Subscribe to: Payments, Refunds, Disputes, and Subscriptions events
                        </p>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0">5</span>
                    <div>
                        <p class="font-semibold">Save and Test</p>
                        <p class="text-gray-600 text-xs mt-1">
                            Save your settings and use PayPal's test tools to send a sample IPN
                        </p>
                    </div>
                </li>
            </ol>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <h3 class="font-bold text-blue-900 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Important
            </h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li>• Ensure your website is publicly accessible</li>
                <li>• The webhook URL must be HTTPS</li>
                <li>• IPN requests are sent to this URL in real-time</li>
                <li>• Always return HTTP 200 for acknowledgment</li>
            </ul>
        </div>

        <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
            <h3 class="font-bold text-yellow-900 mb-2">
                <i class="fas fa-lightbulb mr-2"></i>Testing
            </h3>
            <p class="text-sm text-yellow-800 mb-3">
                First test using PayPal's Sandbox environment before going live.
            </p>
            <a href="/admin/paypal-ipn" class="text-yellow-900 hover:underline text-sm font-semibold">
                View IPN Logs →
            </a>
        </div>

        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
            <h3 class="font-bold text-green-900 mb-2">
                <i class="fas fa-check-circle mr-2"></i>Event Types
            </h3>
            <ul class="text-sm text-green-800 space-y-1">
                <li>✓ web_accept</li>
                <li>✓ refund</li>
                <li>✓ subscr_signup</li>
                <li>✓ subscr_payment</li>
                <li>✓ subscr_failed</li>
            </ul>
        </div>
    </div>
</div>

<script>
function copyWebhookUrl() {
    const url = '{{ url("/webhook/paypal/ipn") }}';
    navigator.clipboard.writeText(url).then(() => {
        showNotification('Webhook URL copied to clipboard!', 'success', 'Copied');
    });
}
</script>
@endsection
