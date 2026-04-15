<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration | RestaurantOS</title>
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
            background: linear-gradient(145deg, #fff3e2, #ffd4aa);
            font-family: 'Manrope', sans-serif;
            color: #2c1b10;
            padding: 20px;
        }

        .card {
            width: min(520px, 100%);
            background: #fffaf2;
            border: 1px solid #f0d9bb;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 18px 42px rgba(121, 71, 39, .18);
        }

        h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 34px;
        }

        p {
            margin: 8px 0 20px;
            color: #7f5d44;
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

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        @media (max-width: 680px) {
            .grid {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Create Account</h1>
        <p>Register as a customer to manage your orders and profile.</p>

        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('customer.register.store') }}">
            @csrf

            <label>Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>

            <div class="grid">
                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label>Phone (optional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="grid">
                <div>
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div>
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
            </div>

            <button type="submit">Register</button>
        </form>

        <div class="links">
            <a href="{{ route('customer.login') }}">Already have an account?</a>
            <a href="{{ route('website.home') }}">Back to website</a>
        </div>
    </div>
</body>

</html>
