<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login | WebsolAI</title>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <style>
        body { font-family: 'Inter', sans-serif !important; background: #0f172a !important; }
        .login-box { width: 380px; }
        .login-card-body { border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,.35); }
        .login-logo a { color: #fff !important; font-weight: 700; letter-spacing: -0.5px; }
        .btn-primary { background: #4f46e5; border-color: #4f46e5; font-weight: 600; }
        .btn-primary:hover { background: #4338ca; border-color: #4338ca; }
        .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 .2rem rgba(79,70,229,.25); }
        .input-group-text { background: #f8fafc; }
    </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo mb-3">
        <a href="{{ url('/') }}">
            <img src="/images/logo.png" alt="WebsolAI"
                 style="width:52px;height:52px;object-fit:cover;border-radius:14px;margin-bottom:12px;display:block;margin-left:auto;margin-right:auto;">
            WebSol<span style="color:#818cf8;">AI</span>
        </a>
        <p class="text-muted" style="font-size:.85rem;">Admin Panel</p>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg" style="font-weight:600;color:#0f172a;font-size:1rem;">Sign in to continue</p>

            @if ($errors->any())
            <div class="alert alert-danger py-2 px-3">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email address"
                           value="{{ old('email') }}" required autofocus autocomplete="email">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope text-muted"></span></div>
                    </div>
                </div>

                <div class="input-group mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Password"
                           required autocomplete="current-password">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock text-muted"></span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt mr-1"></i> Sign In
                        </button>
                    </div>
                </div>
            </form>

            <p class="mt-4 mb-0 text-center">
                <a href="{{ url('/') }}" class="text-muted" style="font-size:.82rem;">
                    <i class="fas fa-arrow-left mr-1"></i> Back to website
                </a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
