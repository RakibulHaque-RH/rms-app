<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
            margin: 0;
            background: #f3f4f6;
        }

        .receipt-wrap {
            max-width: 760px;
            margin: 24px auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 24px;
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: start;
            border-bottom: 2px dashed #d1d5db;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .muted {
            color: #6b7280;
            font-size: 13px;
        }

        .meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 24px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th {
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #6b7280;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px;
        }

        td {
            border-bottom: 1px solid #f3f4f6;
            padding: 10px;
            font-size: 14px;
        }

        .right {
            text-align: right;
        }

        .summary {
            margin-left: auto;
            width: 280px;
            border-top: 2px solid #111827;
            padding-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .summary-total {
            font-size: 18px;
            font-weight: 700;
            margin-top: 4px;
        }

        .footer {
            margin-top: 22px;
            border-top: 2px dashed #d1d5db;
            padding-top: 14px;
            font-size: 13px;
            color: #6b7280;
            text-align: center;
        }

        .actions {
            max-width: 760px;
            margin: 16px auto 0;
            text-align: right;
        }

        .btn {
            display: inline-block;
            border: 1px solid #d1d5db;
            color: #111827;
            background: #ffffff;
            border-radius: 6px;
            padding: 8px 14px;
            text-decoration: none;
            font-size: 13px;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .actions {
                display: none;
            }

            .receipt-wrap {
                border: none;
                margin: 0;
                max-width: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="actions">
        <a href="#" class="btn" onclick="window.print(); return false;">Print</a>
    </div>

    <div class="receipt-wrap">
        <div class="head">
            <div>
                <p class="title">Restaurant Receipt</p>
                <div class="muted">Order {{ $order->order_number }}</div>
            </div>
            <div class="muted">
                {{ $order->created_at->format('M d, Y h:i A') }}
            </div>
        </div>

        <div class="meta">
            <div><strong>Table:</strong> {{ $order->table->table_number ?? 'N/A' }}</div>
            <div><strong>Waiter:</strong> {{ $order->user->name ?? 'N/A' }}</div>
            <div><strong>Order Status:</strong> {{ ucfirst($order->status) }}</div>
            <div><strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'unpaid') }}</div>
            <div><strong>Payment Method:</strong>
                {{ $order->payment_method ? strtoupper($order->payment_method) : 'N/A' }}</div>
            <div><strong>Reference:</strong> {{ $order->payment_reference ?: 'N/A' }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Unit Price</th>
                    <th class="right">Qty</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->menu->name ?? 'Deleted Item' }}</td>
                        <td class="right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-row"><span>Total</span><span>${{ number_format($order->total_amount, 2) }}</span></div>
            <div class="summary-row"><span>Paid</span><span>${{ number_format($order->paid_amount ?? 0, 2) }}</span>
            </div>
            <div class="summary-row summary-total">
                <span>Due</span><span>${{ number_format(max((float) $order->total_amount - (float) ($order->paid_amount ?? 0), 0), 2) }}</span>
            </div>
        </div>

        <div class="footer">
            Thank you for dining with us.
        </div>
    </div>
</body>

</html>
