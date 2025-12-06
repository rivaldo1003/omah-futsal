<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - OFS Futsal Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2C3E50;
            --primary-light: #3A506B;
            --secondary: #3498DB;
            --secondary-light: #5DADE2;
            --accent: #E74C3C;
            --accent-light: #EC7063;
            --success: #27AE60;
            --success-light: #52BE80;
            --warning: #F39C12;
            --warning-light: #F7DC6F;
            --info: #17A2B8;
            --info-light: #48C9B0;
            --light: #F8F9FA;
            --dark: #1A252F;
            --gray: #95A5A6;
            --gray-light: #BDC3C7;
            --border-color: #E9ECEF;
            --card-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            --card-hover-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
            font-family: 'Segoe UI', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #2D3748;
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--dark) 100%);
            min-height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transition: var(--transition);
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
        }

        .sidebar-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.05) 50%, transparent 70%);
            animation: shimmer 8s infinite linear;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .sidebar-header .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-header .logo i {
            color: var(--accent-light);
            font-size: 2rem;
            filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.2));
        }

        .sidebar-header .tagline {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 5px;
            font-weight: 400;
            position: relative;
            z-index: 2;
        }

        .nav-item {
            margin: 4px 0;
            position: relative;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 14px 25px;
            margin: 0 15px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--secondary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            border-radius: 0 4px 4px 0;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            padding-left: 30px;
        }

        .nav-link:hover::before {
            transform: scaleY(1);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
            padding-left: 30px;
        }

        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link.active i {
            color: var(--secondary-light);
        }

        .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            transition: var(--transition);
        }

        .nav-divider {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 25px 25px 10px;
            font-weight: 600;
            position: relative;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
            transition: var(--transition);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .breadcrumb-item a {
            color: var(--gray);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: var(--secondary);
        }

        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 500;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: var(--gray-light);
            content: "›";
            font-size: 1.2rem;
            padding: 0 10px;
        }

        /* User Avatar */
        .user-avatar {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
            transition: var(--transition);
        }

        .user-avatar:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }

        /* Logout Button */
        .btn-logout {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            transition: var(--transition);
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
            background: linear-gradient(135deg, var(--accent-light), var(--accent));
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar,
        .main-content::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-track,
        .main-content::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb,
        .main-content::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            border-radius: 4px;
        }

        /* Mobile Toggle Button */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            width: 50px;
            height: 50px;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            background: var(--primary-light);
            transform: scale(1.1);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 25px;
            }

            .sidebar-toggle {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px 15px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 15px;
            }
        }

        /* Additional Styles */
        .page-header {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        }

        .page-header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-header h1 i {
            color: var(--secondary);
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(52, 152, 219, 0.2));
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .page-header .lead {
            color: var(--gray);
            font-size: 1.1rem;
            max-width: 600px;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            margin-bottom: 30px;
            overflow: hidden;
            background: white;
        }

        .card:hover {
            box-shadow: var(--card-hover-shadow);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 25px 35px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
        }

        .card-header h5 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-body {
            padding: 35px;
        }

        /* Additional Custom Styles */
        @yield('styles')
    </style>
</head>

<body>
    @include('partials.sidebar')

    <div class="main-content" id="mainContent">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                sidebarToggle.innerHTML = sidebar.classList.contains('active')
                    ? '<i class="bi bi-x"></i>'
                    : '<i class="bi bi-list"></i>';
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (event) => {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    sidebarToggle.innerHTML = '<i class="bi bi-list"></i>';
                }
            }
        });
    </script>

    @yield('scripts')
</body>

</html>