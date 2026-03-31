<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — RestaurantOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Inter',sans-serif; min-height:100vh;
            background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 40%,#312e81 70%,#4f46e5 100%);
            display:flex; align-items:center; justify-content:center; padding:20px;
        }
        .login-container { width:100%; max-width:440px; }
        .login-brand { text-align:center; margin-bottom:36px; }
        .login-brand .icon {
            width:64px; height:64px; border-radius:18px; margin:0 auto 16px;
            background:linear-gradient(135deg,#6366f1,#8b5cf6); display:flex;
            align-items:center; justify-content:center; font-size:28px; color:#fff;
            box-shadow:0 8px 32px rgba(99,102,241,.4);
        }
        .login-brand h1 { color:#fff; font-size:28px; font-weight:800; letter-spacing:-1px; }
        .login-brand p { color:rgba(255,255,255,.5); font-size:14px; margin-top:4px; }
        .login-card {
            background:rgba(255,255,255,.95); backdrop-filter:blur(20px);
            border-radius:20px; padding:40px; box-shadow:0 20px 60px rgba(0,0,0,.3);
        }
        .login-card h2 { font-size:22px; font-weight:700; color:#1e293b; margin-bottom:4px; }
        .login-card .subtitle { color:#64748b; font-size:14px; margin-bottom:28px; }
        .form-label { font-weight:600; font-size:13px; color:#475569; margin-bottom:6px; }
        .form-control {
            border:1.5px solid #e2e8f0; border-radius:10px; padding:12px 14px;
            font-size:14px; transition:all .2s;
        }
        .form-control:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
        .input-group-text {
            background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none;
            border-radius:10px 0 0 10px; color:#94a3b8;
        }
        .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }
        .btn-login {
            width:100%; padding:13px; border:none; border-radius:10px;
            background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
            font-size:15px; font-weight:600; cursor:pointer; transition:all .3s;
            box-shadow:0 4px 16px rgba(99,102,241,.35);
        }
        .btn-login:hover { transform:translateY(-2px); box-shadow:0 6px 24px rgba(99,102,241,.45); }
        .form-check-input:checked { background-color:#6366f1; border-color:#6366f1; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-brand">
            <div class="icon"><i class="fas fa-utensils"></i></div>
            <h1>RestaurantOS</h1>
            <p>Restaurant Management System</p>
        </div>
        <div class="login-card">
            <h2>Welcome back</h2>
            <p class="subtitle">Sign in to your account to continue</p>
            @if($errors->any())
                <div class="alert alert-danger py-2 px-3" style="border-radius:10px;font-size:13px;border:none;">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:13px;color:#64748b;">Remember me</label>
                    </div>
                </div>
                <button type="submit" class="btn-login">Sign In <i class="fas fa-arrow-right ms-2"></i></button>
            </form>
            <div class="text-center mt-4" style="font-size:12px;color:#94a3b8;">
                Demo: admin@restaurant.com / password
            </div>
        </div>
    </div>
</body>
</html>
