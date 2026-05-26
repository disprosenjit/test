@extends('layouts.admin')

@section('title', 'PayPal IPN - Raw Data')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">Raw IPN Data</h1>
        <p class="text-gray-600 mt-1">{{ $ipnLog->txn_id }}</p>
    </div>
    <a href="/admin/paypal-ipn/{{ $ipnLog->id }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Back to IPN
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="bg-gray-900 text-gray-100 p-4 rounded font-mono text-sm overflow-x-auto">
        <table class="w-full text-left">
            <tbody>
                @foreach($rawData as $key => $value)
                <tr class="border-b border-gray-700">
                    <td class="py-2 pr-4 text-blue-400 align-top" style="width: 250px;">{{ $key }}</td>
                    <td class="py-2 text-gray-300 break-all">
                        @if(is_array($value))
                            <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            {{ $value }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <button type="button" onclick="copyToClipboard()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-copy mr-2"></i>Copy to Clipboard
        </button>
    </div>
</div>

<script>
function copyToClipboard() {
    const text = document.querySelector('pre, table').innerText;
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Copied to clipboard!', 'success', 'Copied');
    });
}
</script>
@endsection
