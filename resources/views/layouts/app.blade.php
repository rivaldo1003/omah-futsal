<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OFS Futsal Centre</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
    :root {
        --primary-color: #1a5fb4;
        --secondary-color: #5e5c64;
        --success-color: #26a269;
        --danger-color: #c01c28;
        --warning-color: #f5c211;
        --info-color: #1c71d8;
        --dark-color: #0f172a;
        --light-color: #f8fafc;
        --accent-color: #3b82f6;
        --sidebar-bg: #0f172a;
        --sidebar-link: #cbd5e1;
        --nav-bg: rgba(255, 255, 255, 0.98);
        --nav-shadow: rgba(15, 23, 42, 0.1);
        --card-shadow: rgba(0, 0, 0, 0.05);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #334155;
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow-x: hidden;
    }

    /* Subtle Futsal Court Pattern */
    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            /* Very subtle field lines */
            linear-gradient(90deg, rgba(59, 130, 246, 0.03) 1px, transparent 1px),
            linear-gradient(180deg, rgba(59, 130, 246, 0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        background-position: center center;
        mask-image: radial-gradient(circle at center, black 20%, transparent 80%);
        -webkit-mask-image: radial-gradient(circle at center, black 20%, transparent 80%);
        z-index: -1;
    }

    /* Stadium Lighting Effect */
    body::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 300px;
        background: radial-gradient(ellipse at top, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
        z-index: -1;
    }

    /* Login Container */
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 2rem;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.97);
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
        padding: 2.5rem;
        width: 100%;
        max-width: 420px;
        border: 1px solid rgba(226, 232, 240, 0.5);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    /* Sport-themed accent border */
    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-color), #60a5fa, var(--accent-color));
        border-radius: 16px 16px 0 0;
    }

    /* Subtle goal post effect in corners */
    .login-card::after {
        content: '';
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border: 1px solid rgba(59, 130, 246, 0.1);
        border-radius: 10px;
        pointer-events: none;
    }

    .login-card:hover {
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
    }

    /* Brand Header */
    .brand-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .brand-logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        border: 2px solid var(--accent-color);
        position: relative;
    }

    /* Stadium light effect on logo */
    .brand-logo::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border-radius: 18px;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
        pointer-events: none;
    }

    .brand-logo img {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }

    .brand-text {
        text-align: center;
    }

    .brand-main {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--dark-color);
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
        position: relative;
        display: inline-block;
    }

    /* Sport-themed dots */
    .brand-main::before,
    .brand-main::after {
        content: '•';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1rem;
        color: var(--accent-color);
        opacity: 0.6;
    }

    .brand-main::before {
        left: -20px;
    }

    .brand-main::after {
        right: -20px;
    }

    .brand-sub {
        font-size: 0.85rem;
        color: #64748b;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: 600;
    }

    /* Form Styling */
    .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        color: var(--accent-color);
        font-size: 1rem;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
        color: #334155;
    }

    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .input-group {
        border-radius: 10px;
        overflow: hidden;
    }

    .input-group .form-control {
        border-right: none;
    }

    .input-group .input-group-text {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-left: none;
        color: #64748b;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--accent-color);
        color: var(--accent-color);
        background: rgba(59, 130, 246, 0.05);
    }

    /* Remember Me Checkbox */
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        border: 2px solid #e2e8f0;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .form-check-label {
        color: #475569;
        font-weight: 500;
        cursor: pointer;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 700;
        padding: 0.85rem 1.5rem;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-color), #60a5fa);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, var(--accent-color));
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.2);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-primary i {
        font-size: 1rem;
    }

    /* Alerts */
    .alert {
        border: none;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    }

    .alert-danger {
        background: linear-gradient(135deg, var(--danger-color), #f87171);
        color: white;
    }

    .alert-success {
        background: linear-gradient(135deg, var(--success-color), #2dd4bf);
        color: white;
    }

    .alert i {
        font-size: 1.1rem;
    }

    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Footer Text */
    .login-footer {
        text-align: center;
        margin-top: 1.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e2e8f0;
    }

    .login-footer p {
        color: #64748b;
        font-size: 0.85rem;
        margin: 0;
    }

    .login-footer a {
        color: var(--accent-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .login-footer a:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    /* Password Toggle */
    .password-toggle {
        background: none;
        border: none;
        color: #64748b;
        padding: 0.5rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: var(--accent-color);
    }

    /* Loading State */
    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Sport-themed background elements */
    .sport-bg-element {
        position: absolute;
        z-index: -1;
        opacity: 0.05;
    }

    /* Football pattern */
    .sport-bg-element.football-pattern {
        top: 10%;
        right: 10%;
        width: 150px;
        height: 150px;
        background:
            radial-gradient(circle at 30% 30%, var(--accent-color) 2px, transparent 3px),
            radial-gradient(circle at 70% 70%, var(--accent-color) 2px, transparent 3px),
            radial-gradient(circle at 30% 70%, var(--accent-color) 2px, transparent 3px),
            radial-gradient(circle at 70% 30%, var(--accent-color) 2px, transparent 3px);
        background-size: 40px 40px;
    }

    /* Goal post pattern */
    .sport-bg-element.goal-pattern {
        bottom: 15%;
        left: 15%;
        width: 100px;
        height: 100px;
        background:
            linear-gradient(90deg, transparent 45%, var(--accent-color) 45%, var(--accent-color) 55%, transparent 55%),
            linear-gradient(0deg, transparent 45%, var(--accent-color) 45%, var(--accent-color) 55%, transparent 55%);
        background-size: 20px 20px;
    }

    /* Responsive Design */
    @media (max-width: 576px) {
        .login-container {
            padding: 1rem;
        }

        .login-card {
            padding: 2rem;
        }

        .brand-logo {
            width: 70px;
            height: 70px;
        }

        .brand-logo img {
            width: 45px;
            height: 45px;
        }

        .brand-main {
            font-size: 1.5rem;
        }

        .brand-main::before,
        .brand-main::after {
            display: none;
        }

        .brand-sub {
            font-size: 0.8rem;
        }

        .sport-bg-element {
            display: none;
        }
    }

    /* Animation for form */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-card {
        animation: fadeInUp 0.5s ease-out;
    }

    /* Enhanced focus states */
    .form-control:focus,
    .btn:focus {
        outline: none;
    }

    /* Custom scrollbar for form */
    .login-card::-webkit-scrollbar {
        width: 6px;
    }

    .login-card::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .login-card::-webkit-scrollbar-thumb {
        background: var(--accent-color);
        border-radius: 3px;
    }
    </style>
</head>

<body>
    <!-- Sport-themed Background Elements -->
    <div class="sport-bg-element football-pattern"></div>
    <div class="sport-bg-element goal-pattern"></div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Brand Header -->
            <div class="brand-header">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo">
                </div>
                <div class="brand-text">
                    <div class="brand-main">OFS FUTSAL</div>
                    <div class="brand-sub">Championship Center</div>
                </div>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div class="flex-grow-1">
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i>
                <div class="flex-grow-1">{{ session('status') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Input -->
                <div class="form-group mb-3">
                    <label class="form-label" for="email">
                        <i class="bi bi-envelope"></i> Email Address
                    </label>
                    <div class="input-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="Enter your email address" required
                            autofocus>
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group mb-3">
                    <label class="form-label" for="password">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" class="input-group-text password-toggle" id="togglePassword">
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="form-group mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary w-100" id="loginButton">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </button>
                </div>
            </form>

            <!-- Footer Links -->
            <div class="login-footer">
                <p>
                    <i class="bi bi-info-circle me-1"></i>
                    Need help? <a href="mailto:support@ofsfutsal.com">Contact Support</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('passwordIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('bi-eye-slash');
            passwordIcon.classList.add('bi-eye');
        }
    });

    // Form submission loading state
    document.querySelector('form').addEventListener('submit', function(e) {
        const submitButton = document.getElementById('loginButton');
        submitButton.disabled = true;
        submitButton.classList.add('btn-loading');
        submitButton.innerHTML = '';
    });

    <
    !--Auto - focus script-- >
        <
        script >
        <?php if($errors->has('email')): ?>
    document.getElementById('email').focus();
    <?php endif; ?>

    <?php if($errors->has('password')): ?>
    document.getElementById('password').focus();
    <?php endif; ?>
    </script>

    // Add enhanced input focus effects
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
    input.addEventListener('focus', function() {
    this.parentElement.classList.add('input-focused');
    });

    input.addEventListener('blur', function() {
    this.parentElement.classList.remove('input-focused');
    });
    });

    // Add CSS for enhanced focus effects
    const focusStyle = document.createElement('style');
    focusStyle.textContent = `
    .input-focused .input-group-text {
    border-color: var(--accent-color);
    background: rgba(59, 130, 246, 0.05);
    color: var(--accent-color);
    }

    /* Subtle pulse animation for focus */
    .form-control:focus {
    animation: pulseBorder 2s infinite;
    }

    @keyframes pulseBorder {
    0%, 100% { box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    50% { box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); }
    }
    `;
    document.head.appendChild(focusStyle);

    // Smooth animation for form elements
    document.addEventListener('DOMContentLoaded', function() {
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
    group.style.animation = `fadeInUp 0.5s ease-out ${index * 0.1}s both`;
    });
    });

    // Add CSS for animations
    const animationStyle = document.createElement('style');
    animationStyle.textContent = `
    @keyframes fadeInUp {
    from {
    opacity: 0;
    transform: translateY(10px);
    }
    to {
    opacity: 1;
    transform: translateY(0);
    }
    }

    .form-group {
    opacity: 0;
    animation-fill-mode: both;
    }
    `;
    document.head.appendChild(animationStyle);
    </script>
</body>

</html>