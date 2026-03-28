<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Admin Dashboard</title>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e9ecef;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            width: 400px;
            max-width: 90%;
        }
        .card {
            border: 0;
            border-radius: 0.5rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-top: 3px solid #0d6efd;
        }
        .card-header {
            background-color: transparent;
            border-bottom: 0;
            padding-top: 2rem;
            padding-bottom: 1rem;
            text-align: center;
        }
        .login-logo {
            font-size: 2.2rem;
            font-weight: 700;
            color: #495057;
            text-decoration: none;
            letter-spacing: -1px;
        }
        .login-logo b {
            color: #0d6efd;
            font-weight: 800;
            letter-spacing: 0;
        }
        .form-control {
            padding: 0.6rem 1rem;
        }
        .input-group-text {
            color: #6c757d;
        }
        .btn-primary {
            padding: 0.6rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="card">
            <div class="card-header text-center">
                <a href="#" class="login-logo"><i class="bi bi-box-fill me-2 text-primary"></i>SYS<b>ADMIN</b></a>
            </div>
            <div class="card-body px-4 pb-4">
                <p class="text-center text-muted mb-4">Sign in to start your session</p>

                <!-- Session Status -->
                <x-auth-session-status class="alert alert-success mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input id="email" type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Username / Email">
                            <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Password">
                            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="row align-items-center mb-3 mt-4">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label text-muted" for="remember_me">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">Sign In</button>
                        </div>
                    </div>
                </form>

                @if (Route::has('password.request'))
                    <p class="mb-0 mt-4 text-center">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">I forgot my password</a>
                    </p>
                @endif
            </div>
        </div>
        <div class="text-center mt-3 text-muted small">
            &copy; {{ date('Y') }} Bootstrap 5 Admin Dashboard
        </div>
    </div>
</body>
</html>
