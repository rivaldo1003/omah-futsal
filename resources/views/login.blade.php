<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Omah Futsal Centre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: none;
        }

        .login-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .login-body {
            padding: 2.5rem;
            background: white;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }

        .logo {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .back-link {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #0d6efd;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                        <div class="logo">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <h2>Admin Panel</h2>
                        <p class="mb-0">Tournament Management System</p>
                    </div>

                    <div class="login-body">
                        <h4 class="text-center mb-4">Admin Login</h4>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}"
                                        placeholder="admin@tournament.com" required autofocus>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="password123" required>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <button type="submit" class="btn btn-login w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </button>

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="text-center mt-4">
                                <a href="/" class="back-link">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Homepage
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Login Credentials Info -->
                <div class="text-center mt-4">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Login Credentials</h6>
                        <p class="mb-1"><strong>Email:</strong> admin@tournament.com</p>
                        <p class="mb-0"><strong>Password:</strong> password123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus email field
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('email').focus();
        });
    </script>
</body>

</html>