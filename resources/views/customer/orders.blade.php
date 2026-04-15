<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | RestaurantOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Manrope:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, #fff8ec 0%, #ffe2be 100%);
            font-family: 'Manrope', sans-serif;
            color: #25170d;
        }

        .wrap {
            width: min(1000px, 92%);
            margin: 28px auto;
        }

        h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 40px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .btn {
            border: 1px solid #e8ceb0;
            border-radius: 999px;
            padding: 9px 14px;
            text-decoration: none;
            color: #3b2818;
            background: #fff;
            font-weight: 700;
        }

        .card {
            border: 1px solid #ebcfaf;
            background: #fff9ef;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 12px 30px rgba(120, 69, 35, .12);
            margin-bottom: 14px;
        }

        .line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .muted {
            color: #7d5f49;
            font-size: 13px;
        }

        .badge {
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .pending {
            background: #fff7cd;
            color: #7a5b00;
        }

        .preparing {
            background: #dbeafe;
            color: #1e40af;
        }

        .ready {
            background: #dcfce7;
            color: #166534;
        }

        .served,
        .completed {
            background: #d1fae5;
            color: #065f46;
        }

        .cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .approval {
            margin-top: 10px;
            font-size: 13px;
        }

        .notice {
            margin-bottom: 12px;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 10px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="top">
            <h1>My Orders</h1>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a class="btn" href="{{ route('website.home') }}">Website</a>
                <a class="btn" href="{{ route('customer.account') }}">My Account</a>
            </div>
        </div>

        @if (session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

        @forelse($orders as $order)
            <div class="card">
                <div class="line">
                    <div>
                        <div style="font-weight:800">{{ $order->order_number }}</div>
                        <div class="muted">Placed: {{ $order->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    <span class="badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </div>

                <div class="approval">
                    @if (!$order->is_customer_approved)
                        <strong style="color:#92400e">Waiting for admin/manager approval.</strong>
                    @else
                        <strong style="color:#166534">Approved by restaurant. Processing started.</strong>
                    @endif
                </div>

                <div style="margin-top:10px">
                    @foreach ($order->items as $item)
                        <div class="muted">{{ $item->quantity }}x {{ $item->menu->name ?? 'Item' }}</div>
                    @endforeach
                </div>

                <div class="line" style="margin-top:10px">
                    <div class="muted">Payment: {{ ucfirst($order->payment_status ?? 'unpaid') }}</div>
                    <div style="font-weight:800">Total: BDT {{ number_format($order->total_amount, 2) }}</div>
                </div>
            </div>
        @empty
            <div class="card">
                <div style="font-weight:700">No customer orders yet.</div>
                <div class="muted">Place your first order from the landing page menu.</div>
            </div>
        @endforelse

        {{ $orders->links() }}
    </div>
</body>

</html>
