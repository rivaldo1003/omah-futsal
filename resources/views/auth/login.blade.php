<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bg: #FFFFFF;
            --surface: #F7F7F8;
            --border: #E5E5E7;
            --text-primary: #111113;
            --text-secondary: #6B6B70;
            --accent: #1a5fb4;
            --accent-hover: #164e95;
            --danger: #c01c28;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            line-height: 1.5;
        }

        .login-panel {
            width: 100%;
            max-width: 380px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .brand img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 600;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 13px;
            color: var(--text-secondary);
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0 0 24px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .form-control {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            color: var(--text-primary);
            background: var(--bg);
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.12);
            outline: none;
        }

        .form-control::placeholder {
            color: #B0B0B5;
        }

        .invalid-feedback {
            font-size: 13px;
            color: var(--danger);
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 6px;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
        }

        .btn-primary:focus-visible {
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.25);
            outline: none;
        }

        .alert {
            border-radius: 6px;
            font-size: 14px;
            padding: 10px 12px;
            margin-bottom: 16px;
        }

        .alert-danger {
            background: #FDF2F3;
            border: 1px solid #F5C6CB;
            color: var(--danger);
        }

        .alert-success {
            background: #F0F9F4;
            border: 1px solid #BFE5CC;
            color: #1E7A46;
        }

        .form-footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            font-size: 13px;
            color: var(--text-secondary);
        }

        .form-footer a {
            color: var(--accent);
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
</head>

<body>
    <main class="login-panel">
        <div class="brand">
            <img src="{{ asset('images/logo-ofs.png') }}" alt="Logo OFS Futsal Center">
            <div>
                <div class="brand-name">OFS Futsal Center</div>
                <div class="brand-sub">Championship Center</div>
            </div>
        </div>

        <h1>Masuk</h1>
        <p class="subtitle">Gunakan akun admin Anda untuk mengelola turnamen.</p>

        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email') }}" placeholder="nama@ofsfutsal.com" required autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Masukkan password" required>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size: 14px;">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Masuk</button>
        </form>

        <div class="form-footer">
            Butuh bantuan? <a href="mailto:support@ofsfutsal.com">Hubungi support</a>
        </div>
    </main>
</body>

</html>