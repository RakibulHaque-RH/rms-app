<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | RestaurantOS</title>
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
            width: min(900px, 92%);
            margin: 30px auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 44px;
        }

        .btn {
            border: 0;
            border-radius: 999px;
            padding: 10px 16px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-light {
            background: #fff;
            color: #3b2818;
            border: 1px solid #e8ceb0;
        }

        .btn-brand {
            background: linear-gradient(120deg, #bf5b2c, #dc7e3b);
            color: #fff;
        }

        .card {
            border: 1px solid #ebcfaf;
            background: #fff9ef;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 14px 36px rgba(120, 69, 35, .15);
        }

        .label {
            font-size: 12px;
            text-transform: uppercase;
            color: #7f5f46;
            letter-spacing: .8px;
            margin-bottom: 4px;
        }

        .value {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .notice {
            margin: 18px 0;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 10px;
            border-radius: 10px;
        }

        .help {
            margin-top: 16px;
            color: #6d523f;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="top">
            <h1>My Account</h1>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a class="btn btn-light" href="{{ route('website.home') }}">Website</a>
                <a class="btn btn-light" href="{{ route('customer.orders') }}">My Orders</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-brand" type="submit">Logout</button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

        <section class="card">
            <div class="label">Name</div>
            <div class="value">{{ auth()->user()->name }}</div>

            <div class="label">Email</div>
            <div class="value">{{ auth()->user()->email }}</div>

            <div class="label">Phone</div>
            <div class="value">{{ auth()->user()->phone ?: 'Not provided' }}</div>

            <div class="help">
                Your customer account is active. You can now use this account for future customer-side features such as
                booking history and online order tracking.
            </div>
        </section>
    </div>
</body>

</html>
