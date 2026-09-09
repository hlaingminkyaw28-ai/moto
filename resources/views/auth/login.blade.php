<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Moto Service WebApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/moto.css') }}" rel="stylesheet">
</head>
<body class="d-grid align-items-center" style="min-height:100vh;">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">
            <div class="glass-card p-4 p-md-5">
                <div class="mb-4">
                    <div class="badge badge-soft-primary mb-3">Moto Service WebApp</div>
                    <h1 class="h3 mb-2">Admin Login</h1>
                    <p class="text-soft mb-0">Use the admin account to manage customers, motorcycles, invoices, payments, and reports.</p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="vstack gap-3">
                    @csrf
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-shield-lock me-2"></i>Login
                    </button>
                </form>

                <div class="text-soft small mt-4">
                    Demo admin: <code>admin@motoservice.test</code> / <code>password</code>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
