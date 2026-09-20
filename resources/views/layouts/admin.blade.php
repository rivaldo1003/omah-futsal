<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - OFS Futsal Center</title>
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
            --sidebar-width: 250px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--surface);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.5;
            font-size: 14px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 32px;
            min-height: 100vh;
        }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
                padding: 24px 16px;
            }
        }

        /* Base typography */
        h1 {
            font-size: 24px;
            font-weight: 600;
            line-height: 1.2;
        }

        h5 {
            font-weight: 600;
        }

        a {
            color: var(--accent);
        }

        /* Base card style */
        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: none;
            background: var(--bg);
        }

        .card-header {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
            padding: 12px 16px;
        }

        .card-header h5 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .card-body {
            padding: 16px;
        }

        /* Buttons */
        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .btn-outline-primary {
            border-color: var(--border);
            color: var(--text-primary);
        }

        .btn-outline-primary:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--bg);
        }

        /* Forms */
        .form-control,
        .form-select {
            border-color: var(--border);
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.12);
        }

        /* Alerts */
        .alert {
            border-radius: 6px;
            font-size: 14px;
        }

        /* Tables */
        .table {
            font-size: 14px;
        }

        /* Inisial fallback logo tim — dipakai partial team-logo di semua halaman */
        .team-initial {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F0F0F2;
            color: var(--text-secondary);
            font-weight: 600;
        }
    </style>

    {{-- Style per halaman, dimuat setelah base style agar bisa override.
         Halaman me-render tag <style>/<link> miliknya sendiri di dalam section,
         jadi TIDAK dibungkus <style> lagi di sini — tag bersarang membuat
         parser CSS membuang rule pertama halaman. --}}
    @yield('styles')
    @stack('styles')
</head>

<body>
    @include('partials.sidebar')

    <div class="main-content" id="mainContent">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>