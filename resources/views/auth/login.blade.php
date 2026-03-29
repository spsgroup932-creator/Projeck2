<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPS SELARAS - Admin Login</title>
    
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-glow: rgba(79, 70, 229, 0.4);
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.12);
            --text-muted: rgba(255, 255, 255, 0.6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #020617;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        /* Moving Mesh Background */
        .bg-glow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 20% 30%, #1e1b4b 0%, transparent 40%),
                        radial-gradient(circle at 80% 70%, #312e81 0%, transparent 40%),
                        radial-gradient(circle at 50% 50%, #0f172a 0%, transparent 100%);
            animation: bgMove 20s infinite alternate;
        }

        @keyframes bgMove {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            z-index: 10;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 45px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary-color), #818cf8);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            color: #fff;
            box-shadow: 0 10px 20px var(--primary-glow);
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 var(--primary-glow); }
            70% { box-shadow: 0 0 0 15px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }

        .brand-name {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            background: linear-gradient(to bottom, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-subtitle {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 400;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
            transition: all 0.3s;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 14px 20px 14px 52px;
            color: #fff;
            font-size: 15px;
            font-weight: 400;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px var(--primary-glow);
            color: #fff;
        }

        .form-control:focus + .input-icon {
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .login-btn {
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            border: none;
            border-radius: 16px;
            padding: 14px;
            font-weight: 700;
            font-size: 16px;
            color: #fff;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px var(--primary-glow);
            filter: brightness(1.1);
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-check-input {
            background-color: transparent;
            border-color: var(--glass-border);
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .forgot-link {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #fff;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            margin-bottom: 24px;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Particles effect helper */
        .dot {
            position: absolute;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    
    <div class="login-container">
        <div class="glass-card">
            <div class="login-header">
                <div class="brand-logo">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h1 class="brand-name">SPS SELARAS</h1>
                <p class="brand-subtitle">Managed Rental & Logistics System</p>
            </div>

            <!-- Session Status & Errors -->
            @if ($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    Email atau password salah kawan.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <input id="email" type="text" name="email" class="form-control" 
                           value="{{ old('email') }}" required autofocus placeholder="Username / Email">
                    <i class="bi bi-envelope-at input-icon"></i>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input id="password" type="password" name="password" class="form-control" 
                           required placeholder="Password">
                    <i class="bi bi-key input-icon"></i>
                </div>

                <div class="remember-me">
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                        <label for="remember_me" class="form-check-label text-muted ms-2">Ingat saya kawan</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" class="login-btn">
                    MASUK SEKARANG <i class="bi bi-arrow-right-short ms-1"></i>
                </button>
            </form>
        </div>
        <p class="footer-text">
            &copy; {{ date('Y') }} PT. SPS SELARAS Group. All Rights Reserved.
        </p>
    </div>

    <script>
        // Simple ambient light dots
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            for (let i = 0; i < 20; i++) {
                const dot = document.createElement('div');
                dot.className = 'dot';
                const size = Math.random() * 4 + 1;
                dot.style.width = `${size}px`;
                dot.style.height = `${size}px`;
                dot.style.left = `${Math.random() * 100}vw`;
                dot.style.top = `${Math.random() * 100}vh`;
                dot.style.opacity = Math.random() * 0.4;
                body.appendChild(dot);
            }
        });
    </script>
</body>
</html>
