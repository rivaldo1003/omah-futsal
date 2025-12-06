<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            text-align: center;
            color: white;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 90%;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            margin-bottom: 0;
            line-height: 1;
            background: linear-gradient(135deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .error-icon {
            font-size: 6rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .error-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .error-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-home {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
            color: white;
        }

        .error-details {
            margin-top: 2rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            font-size: 0.9rem;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Page Not Found</h2>
        <p class="error-message">
            Oops! The page you're looking for doesn't exist or has been moved.
        </p>

        <a href="/" class="btn-home">
            <i class="bi bi-house-door me-2"></i> Back to Homepage
        </a>

        <div class="error-details mt-4">
            <p class="mb-2">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Requested URL:</strong> {{ url()->current() }}
            </p>
            <p class="mb-0">
                <i class="bi bi-clock me-2"></i>
                <strong>Time:</strong> {{ now()->format('Y-m-d H:i:s') }}
            </p>
        </div>
    </div>
</body>

</html>