<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login | RestaurantOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Manrope:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(140deg, #fff5e5, #ffe2bb);
            font-family: 'Manrope', sans-serif;
            color: #2a1d12;
            padding: 20px;
        }

        .card {
            width: min(460px, 100%);
            background: #fffaf2;
            border: 1px solid #f0d9bb;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 18px 44px rgba(124, 73, 38, .16);
        }

        h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 34px;
        }

        p {
            margin: 8px 0 20px;
            color: #7c5a3f;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            border: 1px solid #dfc5a5;
            border-radius: 10px;
            padding: 11px 12px;
            margin-bottom: 14px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            border: 0;
            border-radius: 999px;
            padding: 12px;
            background: linear-gradient(120deg, #bf5b2c, #dc7e3b);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .alert {
            margin-bottom: 12px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 0 16px;
            font-size: 13px;
        }

        .links {
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        a {
            color: #8e451f;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Customer Login</h1>
        <p>Sign in to manage your customer account.</p>

        @if (session('success'))
            <div class="alert" style="background:#dcfce7;border-color:#bbf7d0;color:#166534">{{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('customer.login.store') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <div class="row">
                <label style="margin:0;font-weight:600"><input type="checkbox" name="remember"
                        style="width:auto;margin:0 6px 0 0">Remember me</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="links">
            <a href="{{ route('customer.register') }}">Create account</a>
            <a href="{{ route('website.home') }}">Back to website</a>
        </div>
    </div>
</body>

</html>
