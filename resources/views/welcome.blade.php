<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome - System</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 text-center">
        <h1 class="display-4 fw-bold">Selamat Datang di Sistem</h1>
        <p class="lead">Sistem ini dikhususkan untuk pengguna terautentikasi.</p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-3 shadow">Lanjut ke Login</a>
    </div>
</body>
</html>
