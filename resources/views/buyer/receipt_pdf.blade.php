<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; line-height: 1.5; margin: 0; padding: 0; font-size: 12px; }
        
        .header { text-align: center; border-bottom: 2px solid #738D56; padding-bottom: 15px; margin-bottom: 25px; }
        .brand-coco { color: #6D4C41; font-weight: bold; font-size: 28px; }
        .brand-hub { color: #738D56; font-weight: bold; font-size: 28px; }
        
        .stats-table { width: 100%; margin-bottom: 30px; border-collapse: separate; border-spacing: 10px 0; margin-left: -10px; }
        .stat-card { background: #F9F7F2; padding: 15px 10px; border-radius: 15px; text-align: center; width: 33.33%; }
        
        .stat-label { font-size: 9px; color: #888; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .stat-value { font-size: 16px; font-weight: bold; color: #222; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
        th { background: #738D56; color: white; padding: 12px; font-size: 11px; text-align: left; text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 12px; border-bottom: 1px solid #f0f0f0; font-size: 11px; vertical-align: middle; }
        
        .text-right { text-align: right; }
        .product-name { font-weight: bold; color: #202124; font-size: 11px; }
        .order-ref { color: #6D4C41; font-weight: bold; font-size: 14px; }

        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #aaa; }
        .total-row { background: #F9F7F2; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <span class="brand-coco">Coco</span><span class="brand-hub">Hub</span>
        <div style="font-size: 14px; margin-top: 5px;">Official Electronic Receipt</div>
        <div style="font-size: 11px; color: #666;">Issued on: {{ $generatedAt }}</div>
    </div>

    <table class="stats-table">
        <tr>
            <td class="stat-card">
                <div class="stat-label">Order Reference</div>
                <div class="stat-value" style="color: #6D4C41;">#{{ $order->id }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Transaction Date</div>
                <div class="stat-value">{{ $order->created_at->timezone('Asia/Manila')->format('M d, Y') }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Order Status</div>
                <div class="stat-value" style="color: #738D56;">{{ strtoupper($order->status) }}</div>
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 20px; margin-left: 5px;">
        <div style="font-size: 10px; color: #888; text-transform: uppercase; font-weight: bold;">Billed To:</div>
        <div style="font-size: 14px; font-weight: bold; color: #222;">
            @if($order->user && $order->user->buyerDetail)
                {{ $order->user->buyerDetail->first_name }} {{ $order->user->buyerDetail->last_name }}
            @else
                {{ $order->user->name }}
            @endif
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50%">Product Item</th>
                <th style="width: 15%" class="text-right">Price</th>
                <th style="width: 15%" class="text-right">Qty</th>
                <th style="width: 20%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td class="product-name">{{ $item->product->name }}</td>
                <td class="text-right">&#8369;{{ number_format($item->price, 2) }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">&#8369;{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right" style="padding: 15px;">TOTAL AMOUNT PAID</td>
                <td class="text-right" style="padding: 15px; font-size: 14px; color: #738D56;">&#8369;{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Thank you for supporting local coconut farmers!<br>
        CocoHub - For Educational Purposes Only. Developed by Lumiere.
    </div>
</body>
</html>