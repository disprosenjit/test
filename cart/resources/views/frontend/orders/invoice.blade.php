<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .container { width: 100%; max-width: 900px; margin: 0 auto; }
        .header { margin-bottom: 20px; }
        .title { font-size: 22px; font-weight: bold; }
        .muted { color: #6b7280; }
        .grid { width: 100%; }
        .box { border: 1px solid #e5e7eb; padding: 10px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .right { text-align: right; }
        .mt { margin-top: 14px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="title">Tax Invoice</div>
        <div class="muted">Order #: {{ $order->order_number }}</div>
        <div class="muted">Date: {{ $order->created_at->format('d M Y, H:i') }}</div>
    </div>

    <table class="grid">
        <tr>
            <td class="box" style="width:50%; vertical-align: top;">
                <strong>From</strong><br>
                Ship Spare Parts Store<br>
                support@shipparts.com
            </td>
            <td class="box" style="width:50%; vertical-align: top;">
                <strong>Bill To</strong><br>
                {{ $order->user->name }}<br>
                {{ $order->user->email }}<br>
                @if($order->shippingAddress)
                    {{ $order->shippingAddress->address_line_1 }}<br>
                    @if($order->shippingAddress->address_line_2)
                        {{ $order->shippingAddress->address_line_2 }}<br>
                    @endif
                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}<br>
                    {{ $order->shippingAddress->country }}
                @endif
            </td>
        </tr>
    </table>

    <table class="mt">
        <thead>
            <tr>
                <th>Item</th>
                <th>SKU</th>
                <th>Qty</th>
                <th class="right">Unit Price</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product_sku }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="right">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="right">₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="mt">
        <tr>
            <td class="right" style="width:80%;">Subtotal</td>
            <td class="right" style="width:20%;">₹{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="right">Tax</td>
            <td class="right">₹{{ number_format($order->tax, 2) }}</td>
        </tr>
        <tr>
            <td class="right">Shipping</td>
            <td class="right">₹{{ number_format($order->shipping, 2) }}</td>
        </tr>
        <tr>
            <td class="right"><strong>Total</strong></td>
            <td class="right"><strong>₹{{ number_format($order->total, 2) }}</strong></td>
        </tr>
    </table>
</div>
</body>
</html>
