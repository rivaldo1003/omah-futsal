<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Masuk — OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-page: #f8fafc;
            --surface: #ffffff;
            --border-color: #e2e8f0;
            --border-focus: #1a5fb4;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --accent: #1a5fb4;
            --accent-hover: #154c90;
            --danger: #dc2626;
            --danger-subtle: #fef2f2;
            --success: #16a34a;
            --success-subtle: #f0fdf4;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 12px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            line-height: 1.5;
        }

        /* Login Container & Card */
        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 32px 28px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }

        /* Brand Identity */
        .login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .login-brand-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            padding: 2px;
            background: #ffffff;
            flex-shrink: 0;
        }

        .login-brand-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .login-brand-sub {
            font-size: 11.5px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Page Heading */
        .login-heading {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .login-subheading {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 24px;
            line-height: 1.4;
        }

        /* Form Inputs & Floating Icon Wraps */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
            display: block;
        }

        .input-icon-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 12px;
            color: var(--text-secondary);
            font-size: 15px;
            pointer-events: none;
            z-index: 2;
        }

        .form-control-custom {
            width: 100%;
            height: 42px;
            padding-left: 36px;
            padding-right: 14px;
            font-size: 13.5px;
            color: var(--text-primary);
            background-color: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }

        .form-control-custom.has-toggle {
            padding-right: 40px;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.12);
        }

        .form-control-custom::placeholder {
            color: #94a3b8;
            font-size: 13px;
        }

        .form-control-custom.is-invalid {
            border-color: var(--danger);
            background-color: #fff;
        }

        .form-control-custom.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
        }

        /* Password Show/Hide Toggle */
        .password-toggle-btn {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 16px;
            padding: 4px 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: color 150ms ease;
            z-index: 2;
        }

        .password-toggle-btn:hover {
            color: var(--text-primary);
        }

        .invalid-feedback-custom {
            font-size: 12px;
            color: var(--danger);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Remember Checkbox */
        .form-check-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
        }

        .form-check-custom input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            accent-color: var(--accent);
            cursor: pointer;
        }

        .form-check-custom label {
            font-size: 13px;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
            line-height: 1;
        }

        /* Primary Submit Button */
        .btn-submit {
            width: 100%;
            height: 42px;
            background-color: var(--accent);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: background-color 150ms ease, box-shadow 150ms ease;
        }

        .btn-submit:hover {
            background-color: var(--accent-hover);
            box-shadow: 0 2px 8px rgba(26, 95, 180, 0.2);
        }

        .btn-submit:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.3);
        }

        /* Alerts */
        .alert-custom {
            border-radius: var(--radius-sm);
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.4;
        }

        .alert-custom-danger {
            background-color: var(--danger-subtle);
            border: 1px solid rgba(220, 38, 38, 0.25);
            color: var(--danger);
        }

        .alert-custom-success {
            background-color: var(--success-subtle);
            border: 1px solid rgba(22, 163, 74, 0.25);
            color: var(--success);
        }

        /* Card Footer & Navigation */
        .login-footer {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
            text-align: center;
        }

        .back-home-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--accent);
            text-decoration: none;
            transition: color 150ms ease;
        }

        .back-home-link:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }

        .security-note {
            font-size: 11.5px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Accessibility: Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
</head>

<body>
    <main class="login-wrapper">
        <div class="login-card">
            <!-- Brand Identity -->
            <div class="login-brand">
                <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Futsal Center Logo" class="login-brand-logo">
                <div>
                    <div class="login-brand-title">OFS Futsal Center</div>
                    <div class="login-brand-sub">Admin Portal</div>
                </div>
            </div>

            <!-- Header Section -->
            <h1 class="login-heading">Masuk ke Akun</h1>
            <p class="login-subheading">Gunakan kredensial admin Anda untuk mengelola turnamen dan pertandingan.</p>

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="alert-custom alert-custom-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-6 mt-0 flex-shrink-0"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Session Status Alert -->
            @if(session('status'))
                <div class="alert-custom alert-custom-success" role="alert">
                    <i class="bi bi-check-circle-fill fs-6 mt-0 flex-shrink-0"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-envelope input-icon-left"></i>
                        <input type="email"
                               class="form-control-custom @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@ofsfutsal.com"
                               required
                               autofocus
                               autocomplete="email">
                    </div>
                    @error('email')
                        <div class="invalid-feedback-custom">
                            <i class="bi bi-x-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <label for="password" class="form-label mb-0">Kata Sandi</label>
                    </div>
                    <div class="input-icon-wrap">
                        <i class="bi bi-lock input-icon-left"></i>
                        <input type="password"
                               class="form-control-custom has-toggle @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Masukkan kata sandi"
                               required
                               autocomplete="current-password">
                        <button type="button"
                                class="password-toggle-btn"
                                id="togglePasswordBtn"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                                tabindex="-1">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback-custom">
                            <i class="bi bi-x-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="form-check-custom">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="btnSubmit">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <!-- Card Footer -->
            <footer class="login-footer">
                <a href="{{ url('/') }}" class="back-home-link">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
                <div class="security-note">
                    <i class="bi bi-shield-check text-success"></i>
                    <span>Akses sistem terlindungi & terenkripsi</span>
                </div>
            </footer>
        </div>
    </main>

    <!-- Interactive Script: Password Toggle & Submitting State -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle Password Visibility
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    toggleIcon.classList.toggle('bi-eye', !isPassword);
                    toggleIcon.classList.toggle('bi-eye-slash', isPassword);
                });
            }

            // Submitting State
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('btnSubmit');

            if (form && submitBtn) {
                form.addEventListener('submit', function () {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                });
            }
        });
    </script>
</body>

</html>