<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Tournament - Futsal Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        /* Semua style CSS tetap sama seperti sebelumnya */
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
            --sidebar-bg: #1e293b;
            --sidebar-link: #94a3b8;
            --sidebar-link-hover: #ffffff;
            --sidebar-active-bg: #334155;
            --divider-color: #334155;
            --text-muted-dark: #64748b;
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
            /* padding-left: var(--sidebar-width); */
        }

        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-link);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            transition: transform 0.3s ease, width 0.3s ease;
            padding-bottom: 120px;
            overflow-y: auto;
        }

        @media (max-width: 991.98px) {
            body {
                padding-left: 0;
            }

            .sidebar {
                transform: translateX(-280px);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid var(--divider-color);
            margin-bottom: 15px;
        }

        .sidebar-header .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header .tagline {
            font-size: 0.7rem;
            color: var(--text-muted-dark);
            margin-top: 3px;
            letter-spacing: 0.8px;
        }

        .nav {
            padding: 0 15px;
        }

        .nav-link {
            color: var(--sidebar-link);
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 4px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            outline: none !important;
            box-shadow: none !important;
            text-decoration: none;
        }

        .nav-link i {
            font-size: 1rem;
            margin-right: 10px;
            color: var(--sidebar-link);
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--sidebar-link-hover);
        }

        .nav-link:hover i {
            color: var(--sidebar-link-hover);
        }

        .nav-link.active {
            color: var(--sidebar-link-hover);
            background-color: var(--sidebar-active-bg);
            font-weight: 600;
            position: relative;
        }

        .nav-link.active i {
            color: var(--sidebar-link-hover);
        }

        .nav-link.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: #ffffff;
            border-radius: 6px 0 0 6px;
        }

        .nav-divider {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted-dark);
            padding: 15px 15px 5px 15px;
            margin-top: 10px;
        }

        .nav-link.utility-link {
            color: var(--text-muted-dark) !important;
        }

        .nav-link.utility-link:hover {
            color: var(--sidebar-link-hover) !important;
            background-color: var(--sidebar-active-bg);
        }

        .user-profile-section {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            border-top: 1px solid var(--divider-color);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--divider-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .btn-logout {
            width: 100%;
            text-align: left;
            color: var(--sidebar-link);
            background: transparent;
            border: 1px solid var(--divider-color);
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9rem;
            outline: none !important;
            box-shadow: none !important;
        }

        .btn-logout:hover {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-link-hover);
            border-color: var(--sidebar-active-bg);
        }

        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1031;
            background-color: var(--sidebar-active-bg);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 1.1rem;
            box-shadow: none !important;
            transition: background-color 0.2s;
            display: none;
        }

        @media (max-width: 991.98px) {
            .sidebar-toggle {
                display: block;
            }
        }

        .main-content {
            padding: 40px;
            min-height: 100vh;
            transition: var(--transition);
        }

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

        .back-btn {
            background: white;
            color: var(--primary);
            border: 2px solid var(--border-color);
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .back-btn:hover {
            background: var(--light);
            border-color: var(--secondary);
            color: var(--secondary);
            transform: translateX(-5px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.15);
        }

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

        .form-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label .required {
            color: var(--accent);
        }

        .form-control,
        .form-select,
        .select2-selection {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: var(--transition);
            background: white;
            color: var(--primary);
        }

        .form-control:focus,
        .form-select:focus,
        .select2-selection--multiple:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.15);
            background: white;
        }

        .form-control::placeholder {
            color: var(--gray-light);
        }

        .form-text {
            color: var(--gray);
            font-size: 0.875rem;
            margin-top: 8px;
            padding-left: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-text i {
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            min-height: 52px;
            padding: 5px 10px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.15);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            border: none;
            border-radius: 8px;
            color: white;
            padding: 6px 12px;
            margin: 4px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 6px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: var(--accent-light);
        }

        .btn {
            padding: 15px 35px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            color: white;
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.25);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-light), var(--secondary));
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.35);
        }

        .btn-outline-secondary {
            border: 2px solid var(--border-color);
            color: var(--gray);
            background: white;
        }

        .btn-outline-secondary:hover {
            border-color: var(--secondary);
            color: var(--secondary);
            background: var(--light);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .alert i {
            font-size: 1.3rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(39, 174, 96, 0.05));
            border-left: 4px solid var(--success);
            color: var(--success);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.1), rgba(231, 76, 60, 0.05));
            border-left: 4px solid var(--accent);
            color: var(--accent);
        }

        .alert-danger ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }

        .alert-danger li {
            margin-bottom: 5px;
        }

        .settings-section {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.03), rgba(52, 152, 219, 0.01));
            border: 2px solid rgba(52, 152, 219, 0.1);
            border-radius: 16px;
            padding: 25px;
            margin: 25px 0;
            transition: var(--transition);
        }

        .settings-section:hover {
            border-color: rgba(52, 152, 219, 0.2);
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.05), rgba(52, 152, 219, 0.02));
        }

        .settings-section h6 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .settings-section h6 i {
            color: var(--secondary);
        }

        .step-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid rgba(0, 0, 0, 0.05);
        }

        .step-btn {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .step-btn.prev {
            background: var(--light);
            color: var(--primary);
            border: 2px solid var(--border-color);
        }

        .step-btn.prev:hover {
            background: var(--gray-light);
            border-color: var(--gray);
        }

        .step-btn.next {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            color: white;
            border: none;
        }

        .step-btn.next:hover {
            background: linear-gradient(135deg, var(--secondary-light), var(--secondary));
            transform: translateX(5px);
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--gray);
            transition: var(--transition);
            z-index: 2;
        }

        .step.active .step-circle {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            border-color: var(--secondary);
            color: white;
            transform: scale(1.1);
        }

        .step.completed .step-circle {
            background: var(--success);
            border-color: var(--success);
            color: white;
        }

        .step-label {
            margin-top: 8px;
            font-size: 0.85rem;
            color: var(--gray);
            font-weight: 500;
        }

        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }

        .step::before {
            content: '';
            position: absolute;
            top: 20px;
            left: -20px;
            right: 100%;
            height: 2px;
            background: var(--border-color);
            transition: var(--transition);
        }

        .step:first-child::before {
            display: none;
        }

        .step.completed::before,
        .step.active::before {
            background: var(--secondary);
        }

        .draggable-item {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: move;
            transition: var(--transition);
            position: relative;
        }

        .draggable-item:hover {
            border-color: var(--secondary);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
            transform: translateY(-2px);
        }

        .draggable-item.dragging {
            opacity: 0.5;
            border-style: dashed;
            border-color: var(--secondary);
        }

        .group-container {
            min-height: 400px;
            border: 2px solid var(--border-color);
        }

        .group-container.drop-active {
            border-color: var(--secondary);
            border-style: dashed;
            background: rgba(52, 152, 219, 0.05);
        }

        .group-header {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            margin: -1px;
        }

        .group-body {
            padding: 15px;
            min-height: 300px;
            max-height: 500px;
            overflow-y: auto;
        }

        .team-logo-placeholder {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            margin: 0 auto 10px;
        }

        .available-teams-container {
            background: rgba(241, 245, 249, 0.5);
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 20px;
            min-height: 200px;
            margin-bottom: 20px;
        }

        .team-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            transition: var(--transition);
            cursor: move;
        }

        .team-card:hover {
            border-color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
        }

        .team-card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .drag-info {
            background: var(--light);
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .tournament-preview {
            background: linear-gradient(135deg, #F8FAFC 0%, #EDF2F7 100%);
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .tournament-preview:hover {
            border-color: var(--secondary);
            background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%);
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .preview-item {
            padding: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
        }

        .preview-item label {
            font-size: 0.8rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
            font-weight: 600;
        }

        .preview-item .value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary);
        }

        .preview-item .value.badge {
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 20px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 25px;
        }

        .stat-box {
            text-align: center;
            padding: 25px 15px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .selected-teams-preview {
            max-height: 200px;
            overflow-y: auto;
            margin-top: 15px;
        }

        .selected-team-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .team-card.assigned {
            opacity: 0.6;
            border-color: var(--success);
            background-color: rgba(39, 174, 96, 0.05);
        }

        .team-card.assigned:hover {
            cursor: not-allowed;
            transform: none;
        }

        .team-card.available {
            border-color: var(--warning);
            background-color: rgba(243, 156, 18, 0.05);
        }

        .team-card.available:hover {
            cursor: move;
        }

        .remove-team-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 10;
        }

        .draggable-item:hover .remove-team-btn {
            opacity: 1;
        }

        .remove-team-btn:hover {
            background: var(--accent-light);
            transform: scale(1.1);
        }

        .group-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }

        .group-status .badge {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        .team-status {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 500;
        }

        .status-assigned {
            background: var(--success-light);
            color: var(--success);
        }

        .status-available {
            background: var(--warning-light);
            color: var(--warning);
        }

        .selected-team-item:last-child {
            border-bottom: none;
        }

        .selected-team-logo {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .info-box {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.08), rgba(23, 162, 184, 0.04));
            border-left: 4px solid var(--info);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .info-box i {
            color: var(--info);
            font-size: 1.4rem;
            margin-bottom: 12px;
        }

        .info-box p {
            color: var(--primary);
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        .warning-box {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.08), rgba(243, 156, 18, 0.04));
            border-left: 4px solid var(--warning);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .warning-box i {
            color: var(--warning);
            font-size: 1.4rem;
            margin-bottom: 12px;
        }

        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .bg-primary {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-light)) !important;
        }

        .sidebar::-webkit-scrollbar,
        .main-content::-webkit-scrollbar,
        .group-body::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-track,
        .main-content::-webkit-scrollbar-track,
        .group-body::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb,
        .main-content::-webkit-scrollbar-thumb,
        .group-body::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            border-radius: 4px;
        }

        @media (max-width: 1200px) {
            .main-content {
                padding: 30px;
            }

            .card-body {
                padding: 30px;
            }
        }

        @media (max-width: 992px) {
            .main-content {
                padding: 25px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 20px;
            }

            .preview-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px 15px;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }

            .page-header h1 i {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .card-header {
                padding: 20px 25px;
            }

            .card-body {
                padding: 25px;
            }

            .btn {
                padding: 12px 25px;
            }

            .step-indicator {
                flex-wrap: wrap;
                gap: 15px;
            }

            .step::before {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.6rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .card-body {
                padding: 20px;
            }

            .settings-section {
                padding: 20px;
            }

            .tournament-preview {
                padding: 20px;
            }

            .preview-grid {
                gap: 15px;
            }

            .step-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        .is-valid {
            border-color: var(--success) !important;
        }

        .is-invalid {
            border-color: var(--accent) !important;
        }


        .file-upload-container {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: rgba(241, 245, 249, 0.5);
        }

        .file-upload-container:hover {
            border-color: var(--secondary);
            background: rgba(52, 152, 219, 0.05);
        }

        .file-upload-container i {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 10px;
        }

        .file-preview {
            margin-top: 15px;
        }

        .file-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .file-name {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 8px;
            word-break: break-all;
        }

        .file-size {
            font-size: 0.75rem;
            color: var(--gray-light);
        }
    </style>
</head>

<body>
    <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="main-content" id="mainContent">
        <nav aria-label="breadcrumb" class="mb-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tournaments.index') }}">Tournaments</a></li>
                <li class="breadcrumb-item active">Create New Tournament</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center page-header">
            <div>
                <h1>
                    <i class="bi bi-plus-circle"></i>
                    <span>Create New Tournament</span>
                </h1>
                <p class="lead">Set up a new tournament with teams, rules, and match settings</p>
            </div>
            <a href="{{ route('admin.tournaments.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>

        <div class="step-indicator">
            <div class="step {{ $currentStep == 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}"
                data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Basic Info</div>
            </div>
            <div class="step {{ $currentStep == 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}"
                data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Teams</div>
            </div>
            <div class="step {{ $currentStep == 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}"
                data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Groups</div>
            </div>
            <div class="step {{ $currentStep == 4 ? 'active' : '' }} {{ $currentStep > 4 ? 'completed' : '' }}"
                data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Match Rules</div>
            </div>
            <div class="step {{ $currentStep == 5 ? 'active' : '' }}" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Review</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('admin.tournaments.store.step', ['step' => $currentStep ?? $step ?? 1]) }}"
                    method="POST" id="tournamentForm" class="multi-step-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="current_step" value="{{ $currentStep }}">

                    <div class="step-content" id="step1" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <i class="bi bi-info-circle"></i>
                                    Tournament Basic Information
                                </h5>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle"></i>
                                        <div>
                                            <strong>Success!</strong> {{ session('success') }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <div>
                                            <strong>Please fix the following errors:</strong>
                                            <ul class="mt-2 mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">
                                                <i class="bi bi-card-heading"></i>
                                                Tournament Name
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name"
                                                value="{{ old('name', $tournamentData['name'] ?? '') }}" required
                                                placeholder="e.g., Ofs Champions League 2025">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug" class="form-label">
                                                <i class="bi bi-link"></i>
                                                URL Slug
                                            </label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                                id="slug" name="slug"
                                                value="{{ old('slug', $tournamentData['slug'] ?? '') }}"
                                                placeholder="Auto-generates from name">
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="bi bi-text-paragraph"></i>
                                        Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="3"
                                        placeholder="Brief description of the tournament">{{ old('description', $tournamentData['description'] ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label">
                                                <i class="bi bi-calendar-event"></i>
                                                Start Date
                                                <span class="required">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                id="start_date" name="start_date"
                                                value="{{ old('start_date', $tournamentData['start_date'] ?? '') }}"
                                                required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label">
                                                <i class="bi bi-calendar-check"></i>
                                                End Date
                                                <span class="required">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                id="end_date" name="end_date"
                                                value="{{ old('end_date', $tournamentData['end_date'] ?? '') }}"
                                                required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location" class="form-label">
                                                <i class="bi bi-geo-alt"></i>
                                                Location
                                            </label>
                                            <input type="text"
                                                class="form-control @error('location') is-invalid @enderror"
                                                id="location" name="location"
                                                value="{{ old('location', $tournamentData['location'] ?? '') }}"
                                                placeholder="e.g., Gor Ofs">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizer" class="form-label">
                                                <i class="bi bi-building"></i>
                                                Organizer
                                            </label>
                                            <input type="text"
                                                class="form-control @error('organizer') is-invalid @enderror"
                                                id="organizer" name="organizer"
                                                value="{{ old('organizer', $tournamentData['organizer'] ?? '') }}"
                                                placeholder="e.g., OFs Sports Club">
                                            @error('organizer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- TAMBAHAN FIELD UNTUK LOGO DAN BANNER -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo" class="form-label">
                                                <i class="bi bi-image"></i>
                                                Tournament Logo
                                            </label>
                                            <div class="file-upload-container" onclick="document.getElementById('logo').click()">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p class="mb-2">Click to upload logo</p>
                                                <small class="text-muted">Recommended: Square image, max 2MB, PNG/JPG format</small>
                                                <input type="file" class="form-control d-none @error('logo') is-invalid @enderror" 
                                                       id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                                                @error('logo')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="file-preview" id="logoPreviewContainer" style="display: none;">
                                                <img id="logoPreview" alt="Logo Preview">
                                                <div class="file-name" id="logoFileName"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="banner" class="form-label">
                                                <i class="bi bi-image-fill"></i>
                                                Tournament Banner
                                            </label>
                                            <div class="file-upload-container" onclick="document.getElementById('banner').click()">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p class="mb-2">Click to upload banner</p>
                                                <small class="text-muted">Recommended: Wide image (16:9), max 5MB, PNG/JPG format</small>
                                                <input type="file" class="form-control d-none @error('banner') is-invalid @enderror" 
                                                       id="banner" name="banner" accept="image/*" onchange="previewBanner(event)">
                                                @error('banner')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="file-preview" id="bannerPreviewContainer" style="display: none;">
                                                <img id="bannerPreview" alt="Banner Preview">
                                                <div class="file-name" id="bannerFileName"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type" class="form-label">
                                                <i class="bi bi-diagram-3"></i>
                                                Tournament Type
                                                <span class="required">*</span>
                                            </label>
                                            <select class="form-select @error('type') is-invalid @enderror" id="type"
                                                name="type" required>
                                                <!-- <option value="" disabled {{ old('type', $tournamentData['type'] ?? '') ? '' : 'selected' }}>Select tournament format</option> -->
                                                <option value="group_knockout" {{ (old('type', $tournamentData['type'] ?? '') == 'group_knockout') ? 'selected' : '' }}>Group Stage +
                                                    Knockout</option>
                                                <!-- <option value="league" {{ (old('type', $tournamentData['type'] ?? '') == 'league') ? 'selected' : '' }}>League</option> -->
                                                <!-- <option value="knockout" {{ (old('type', $tournamentData['type'] ?? '') == 'knockout') ? 'selected' : '' }}>Knockout</option> -->
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="form-label">
                                                <i class="bi bi-clock-history"></i>
                                                Initial Status
                                                <span class="required">*</span>
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="upcoming" {{ (old('status', $tournamentData['status'] ?? '') == 'upcoming') ? 'selected' : '' }}>Upcoming</option>
                                                <option value="ongoing" {{ (old('status', $tournamentData['status'] ?? '') == 'ongoing') ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ (old('status', $tournamentData['status'] ?? '') == 'completed') ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ (old('status', $tournamentData['status'] ?? '') == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div id="groupSettings" class="settings-section"
                                    style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'group_knockout') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-grid-3x3"></i> Group Stage Configuration</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="groups_count" class="form-label">
                                                    Number of Groups
                                                    <span class="required">*</span>
                                                </label>
                                                <input type="number"
                                                    class="form-control @error('groups_count') is-invalid @enderror"
                                                    id="groups_count" name="groups_count"
                                                    value="{{ old('groups_count', $tournamentData['groups_count'] ?? 2) }}"
                                                    min="1" max="8">
                                                @error('groups_count')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="teams_per_group" class="form-label">
                                                    Teams per Group
                                                    <span class="required">*</span>
                                                </label>
                                                <input type="number"
                                                    class="form-control @error('teams_per_group') is-invalid @enderror"
                                                    id="teams_per_group" name="teams_per_group"
                                                    value="{{ old('teams_per_group', $tournamentData['teams_per_group'] ?? 4) }}"
                                                    min="2" max="10">
                                                @error('teams_per_group')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="qualify_per_group" class="form-label">
                                                    Teams Qualify per Group
                                                    <span class="required">*</span>
                                                </label>
                                                <input type="number"
                                                    class="form-control @error('qualify_per_group') is-invalid @enderror"
                                                    id="qualify_per_group" name="qualify_per_group"
                                                    value="{{ old('qualify_per_group', $tournamentData['qualify_per_group'] ?? 2) }}"
                                                    min="1" max="4">
                                                @error('qualify_per_group')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" id="step2" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-people"></i> Team Selection</h5>
                            </div>
                            <div class="card-body">
                                <div class="settings-section">
                                    <h6><i class="bi bi-filter"></i> Filter Teams</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="team_search" class="form-label">Search Teams</label>
                                                <input type="text" class="form-control" id="team_search"
                                                    placeholder="Search by team name or coach...">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-list-check"></i> Select Participating Teams</h6>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <span class="text-muted">Total Teams Available: </span>
                                            <span class="fw-bold" id="totalTeamsCount">{{ $teams->count() }}</span>
                                        </div>
                                        <div>
                                            <span class="text-muted">Selected: </span>
                                            <span class="fw-bold text-success" id="selectedTeamsCount">0</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="teams" class="form-label">
                                            <i class="bi bi-check2-square"></i>
                                            Select Teams
                                            <span class="required">*</span>
                                        </label>
                                        <select class="form-control @error('teams') is-invalid @enderror" id="teams"
                                            name="teams[]" multiple="multiple">
                                            @foreach($teams as $team)
                                                @php
                                                    $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                                @endphp
                                                <option value="{{ $team->id }}" data-name="{{ $team->name }}"
                                                    data-logo="{{ $logoUrl }}" data-coach="{{ $team->coach_name }}" {{ in_array($team->id, old('teams', $tournamentData['teams'] ?? [])) ? 'selected' : '' }}>
                                                    {{ $team->name }}
                                                    @if($team->coach_name)
                                                        ({{ $team->coach_name }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="teams_required" id="teams_required"
                                            value="{{ count(old('teams', $tournamentData['teams'] ?? [])) > 0 ? '1' : '0' }}">

                                        @error('teams')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="bi bi-info-circle"></i>
                                            Select all teams that will participate in this tournament
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" id="step3" style="display: {{ $currentStep == 3 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-grid-3x3"></i> Group Assignment</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Drag and drop teams between groups.</strong> Drag teams from "Available
                                    Teams" to any group, or between groups.
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="groups_count_assign" class="form-label">
                                                <i class="bi bi-hash"></i>
                                                Number of Groups
                                            </label>
                                            <input type="number" class="form-control" id="groups_count_assign"
                                                name="groups_count_assign"
                                                value="{{ old('groups_count', $tournamentData['groups_count'] ?? 2) }}"
                                                min="1" max="8">
                                            <div class="form-text">How many groups do you want?</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-end h-100">
                                            <button type="button" class="btn btn-outline-primary me-2"
                                                onclick="autoDistribute()">
                                                <i class="bi bi-shuffle"></i> Auto-distribute
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="resetGroups()">
                                                <i class="bi bi-arrow-clockwise"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0"><i class="bi bi-people"></i> Available Teams</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="drag-info">
                                            <i class="bi bi-arrow-down-up"></i> Drag teams from here to groups below
                                        </div>
                                        <div class="row" id="availableTeamsContainer">
                                            @if(!empty($tournamentData['teams']))
                                                @foreach($teams->whereIn('id', $tournamentData['teams']) as $team)
                                                    @php
                                                        $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                                    @endphp
                                                    <div class="col-md-3 mb-3 team-card-container"
                                                        id="team-container-{{ $team->id }}">
                                                        <div class="team-card draggable-item" data-team-id="{{ $team->id }}"
                                                            draggable="true">
                                                            <div class="team-logo-placeholder">
                                                                @if($logoUrl)
                                                                    <img src="{{ $logoUrl }}" alt="{{ $team->name }}"
                                                                        style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                                                @else
                                                                    {{ substr($team->name, 0, 2) }}
                                                                @endif
                                                            </div>
                                                            <h6 class="mb-1">{{ $team->name }}</h6>
                                                            @if($team->coach_name)
                                                                <small class="text-muted">Coach: {{ $team->coach_name }}</small>
                                                            @endif
                                                            <div class="mt-2">
                                                                <span class="badge bg-light text-dark"
                                                                    id="team-group-{{ $team->id }}">Unassigned</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="groupsContainer">
                                </div>

                                <input type="hidden" name="group_assignments" id="groupAssignments"
                                    value="{{ json_encode($tournamentData['group_assignments'] ?? []) }}">

                                <div class="alert alert-light mt-4">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="bi bi-check-circle text-success"></i>
                                            <span id="assignedCount">0</span> teams assigned
                                        </div>
                                        <div>
                                            <i class="bi bi-clock text-warning"></i>
                                            <span
                                                id="unassignedCount">{{ count($tournamentData['teams'] ?? []) }}</span>
                                            teams unassigned
                                        </div>
                                        <div>
                                            <i class="bi bi-grid-3x3 text-primary"></i>
                                            <span id="groupsCount">0</span> groups created
                                        </div>
                                    </div>
                                </div>

                                <div class="info-box">
                                    <i class="bi bi-lightbulb"></i>
                                    <p>
                                        <strong>How to assign teams:</strong><br>
                                        1. Drag teams from "Available Teams" to any group<br>
                                        2. Drag teams between groups to move them<br>
                                        3. Click "Auto-distribute" for random distribution<br>
                                        4. Each group should have similar number of teams
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" id="step4" style="display: {{ $currentStep == 4 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <i class="bi bi-joystick"></i>
                                    Match Rules & Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="settings-section">
                                    <h6><i class="bi bi-clock"></i> Match Duration</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="match_duration" class="form-label">Regular Time
                                                    (minutes) <span class="required">*</span></label>
                                                <input type="number"
                                                    class="form-control @error('match_duration') is-invalid @enderror"
                                                    id="match_duration" name="match_duration"
                                                    value="{{ old('match_duration', $tournamentData['match_duration'] ?? 40) }}"
                                                    min="10" max="120" required>
                                                @error('match_duration')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="half_time" class="form-label">Half Time (minutes)
                                                    <span class="required">*</span></label>
                                                <input type="number"
                                                    class="form-control @error('half_time') is-invalid @enderror"
                                                    id="half_time" name="half_time"
                                                    value="{{ old('half_time', $tournamentData['half_time'] ?? 10) }}"
                                                    min="5" max="30" required>
                                                @error('half_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="extra_time" class="form-label">Extra Time (minutes)</label>
                                                <input type="number"
                                                    class="form-control @error('extra_time') is-invalid @enderror"
                                                    id="extra_time" name="extra_time"
                                                    value="{{ old('extra_time', $tournamentData['extra_time'] ?? 10) }}"
                                                    min="0" max="30">
                                                @error('extra_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-flag"></i> Points System</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="points_win" class="form-label">Win <span
                                                        class="required">*</span></label>
                                                <input type="number"
                                                    class="form-control @error('points_win') is-invalid @enderror"
                                                    id="points_win" name="points_win"
                                                    value="{{ old('points_win', $tournamentData['points_win'] ?? 3) }}"
                                                    min="1" max="10" required>
                                                @error('points_win')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="points_draw" class="form-label">Draw <span
                                                        class="required">*</span></label>
                                                <input type="number"
                                                    class="form-control @error('points_draw') is-invalid @enderror"
                                                    id="points_draw" name="points_draw"
                                                    value="{{ old('points_draw', $tournamentData['points_draw'] ?? 1) }}"
                                                    min="0" max="5" required>
                                                @error('points_draw')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="points_loss" class="form-label">Loss <span
                                                        class="required">*</span></label>
                                                <input type="number"
                                                    class="form-control @error('points_loss') is-invalid @enderror"
                                                    id="points_loss" name="points_loss"
                                                    value="{{ old('points_loss', $tournamentData['points_loss'] ?? 0) }}"
                                                    min="0" max="5" required>
                                                @error('points_loss')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="points_no_show" class="form-label">No Show</label>
                                                <input type="number"
                                                    class="form-control @error('points_no_show') is-invalid @enderror"
                                                    id="points_no_show" name="points_no_show"
                                                    value="{{ old('points_no_show', $tournamentData['points_no_show'] ?? -1) }}"
                                                    min="-10" max="0">
                                                @error('points_no_show')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-card-checklist"></i> Match Rules</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="max_substitutes" class="form-label">Maximum
                                                    Substitutes <span class="required">*</span></label>
                                                <input type="number"
                                                    class="form-control @error('max_substitutes') is-invalid @enderror"
                                                    id="max_substitutes" name="max_substitutes"
                                                    value="{{ old('max_substitutes', $tournamentData['max_substitutes'] ?? 5) }}"
                                                    min="0" max="20" required>
                                                @error('max_substitutes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="yellow_card_suspension" class="form-label">Yellow Cards for
                                                    Suspension</label>
                                                <input type="number"
                                                    class="form-control @error('yellow_card_suspension') is-invalid @enderror"
                                                    id="yellow_card_suspension" name="yellow_card_suspension"
                                                    value="{{ old('yellow_card_suspension', $tournamentData['yellow_card_suspension'] ?? 3) }}"
                                                    min="1" max="10">
                                                @error('yellow_card_suspension')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="allow_draw"
                                                    name="allow_draw" value="1" {{ old('allow_draw', $tournamentData['allow_draw'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="allow_draw">
                                                    Allow draws in group stage
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="extra_time_enabled"
                                                    name="extra_time_enabled" value="1" {{ old('extra_time_enabled', $tournamentData['extra_time_enabled'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="extra_time_enabled">
                                                    Extra time for knockout matches
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="penalty_shootout"
                                                    name="penalty_shootout" value="1" {{ old('penalty_shootout', $tournamentData['penalty_shootout'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="penalty_shootout">
                                                    Penalty shootout after extra time
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="var_enabled"
                                                    name="var_enabled" value="1" {{ old('var_enabled', $tournamentData['var_enabled'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="var_enabled">
                                                    Enable VAR (Video Assistant Referee)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-calendar-week"></i> Schedule Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="matches_per_day" class="form-label">Maximum Matches per
                                                    Day <span class="required">*</span></label>
                                                <input type="number"
                                                    class="form-control @error('matches_per_day') is-invalid @enderror"
                                                    id="matches_per_day" name="matches_per_day"
                                                    value="{{ old('matches_per_day', $tournamentData['matches_per_day'] ?? 4) }}"
                                                    min="1" max="20" required>
                                                @error('matches_per_day')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="match_interval" class="form-label">Match Interval
                                                    (minutes) <span class="required">*</span></label>
                                                <input type="number"
                                                    class="form-control @error('match_interval') is-invalid @enderror"
                                                    id="match_interval" name="match_interval"
                                                    value="{{ old('match_interval', $tournamentData['match_interval'] ?? 30) }}"
                                                    min="15" max="120" required>
                                                @error('match_interval')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="match_time_slots" class="form-label">Preferred Match Times</label>
                                        <input type="text"
                                            class="form-control @error('match_time_slots') is-invalid @enderror"
                                            id="match_time_slots" name="match_time_slots"
                                            value="{{ old('match_time_slots', $tournamentData['match_time_slots'] ?? '14:00, 16:00, 18:00, 20:00') }}"
                                            placeholder="Enter preferred match times separated by commas">
                                        @error('match_time_slots')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" id="step5" style="display: {{ $currentStep == 5 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <i class="bi bi-eye"></i>
                                    Review & Create Tournament
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="tournament-preview mb-4">
                                    <h6>TOURNAMENT SUMMARY</h6>
                                    <h4 id="reviewName">
                                        {{ old('name', $tournamentData['name'] ?? 'Ofs Champions League 2025') }}
                                    </h4>

                                    <div class="preview-grid">
                                        <div class="preview-item">
                                            <label>DATES</label>
                                            <div class="value" id="reviewDates">
                                                @if(!empty($tournamentData['start_date']) && !empty($tournamentData['end_date']))
                                                    {{ date('d M Y', strtotime($tournamentData['start_date'])) }} to
                                                    {{ date('d M Y', strtotime($tournamentData['end_date'])) }}
                                                @else
                                                    -- -- ---- to -- -- ----
                                                @endif
                                            </div>
                                        </div>
                                        <div class="preview-item">
                                            <label>TYPE</label>
                                            <div class="value badge bg-primary" id="reviewType">
                                                @php
                                                    $typeNames = [
                                                        'league' => 'League',
                                                        'knockout' => 'Knockout',
                                                        'group_knockout' => 'Group + Knockout'
                                                    ];
                                                    $selectedType = old('type', $tournamentData['type'] ?? '');
                                                @endphp
                                                {{ $typeNames[$selectedType] ?? 'Select Type' }}
                                            </div>
                                        </div>
                                        <div class="preview-item">
                                            <label>TEAMS</label>
                                            <div class="value" id="reviewTeams">
                                                {{ count(old('teams', $tournamentData['teams'] ?? [])) }} teams
                                            </div>
                                        </div>
                                        <div class="preview-item">
                                            <label>MATCHES</label>
                                            <div class="value" id="reviewMatches">0 matches</div>
                                        </div>
                                        <div class="preview-item">
                                            <label>LOCATION</label>
                                            <div class="value" id="reviewLocation">
                                                {{ old('location', $tournamentData['location'] ?? '--') }}
                                            </div>
                                        </div>
                                        <div class="preview-item">
                                            <label>ORGANIZER</label>
                                            <div class="value" id="reviewOrganizer">
                                                {{ old('organizer', $tournamentData['organizer'] ?? '--') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-people"></i> Participating Teams</h6>
                                    <div class="selected-teams-preview" id="reviewTeamsList">
                                        @if(!empty($tournamentData['teams']))
                                            @foreach($teams->whereIn('id', $tournamentData['teams']) as $team)
                                                @php
                                                    $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                                @endphp
                                                <div class="selected-team-item">
                                                    @if($logoUrl)
                                                        <img src="{{ $logoUrl }}" alt="{{ $team->name }}"
                                                            style="width: 30px; height: 30px; border-radius: 6px; object-fit: cover;">
                                                    @else
                                                        <div class="selected-team-logo">{{ substr($team->name, 0, 1) }}</div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $team->name }}</div>
                                                        @if($team->coach_name)
                                                            <small class="text-muted">Coach: {{ $team->coach_name }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-muted">No teams selected yet</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-joystick"></i> Match Rules Summary</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="preview-item">
                                                <label>Match Duration</label>
                                                <div class="value" id="reviewDuration">
                                                    {{ old('match_duration', $tournamentData['match_duration'] ?? 40) }}
                                                    mins
                                                    ({{ old('half_time', $tournamentData['half_time'] ?? 10) }} mins
                                                    half)
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="preview-item">
                                                <label>Points System</label>
                                                <div class="value" id="reviewPoints">
                                                    Win: {{ old('points_win', $tournamentData['points_win'] ?? 3) }},
                                                    Draw: {{ old('points_draw', $tournamentData['points_draw'] ?? 1) }},
                                                    Loss: {{ old('points_loss', $tournamentData['points_loss'] ?? 0) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="preview-item">
                                                <label>Max Substitutes</label>
                                                <div class="value" id="reviewSubstitutes">
                                                    {{ old('max_substitutes', $tournamentData['max_substitutes'] ?? 5) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAMBAHAN PREVIEW LOGO DAN BANNER -->
                                <div class="settings-section">
                                    <h6><i class="bi bi-images"></i> Tournament Media</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <p class="mb-2"><strong>Logo</strong></p>
                                                <div id="reviewLogoContainer" class="mb-3">
                                                    @if(!empty($tournamentData['logo']))
                                                        <img src="{{ Storage::url($tournamentData['logo']) }}" 
                                                             alt="Tournament Logo" 
                                                             style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                                    @else
                                                        <div class="text-muted">No logo uploaded</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <p class="mb-2"><strong>Banner</strong></p>
                                                <div id="reviewBannerContainer" class="mb-3">
                                                    @if(!empty($tournamentData['banner']))
                                                        <img src="{{ Storage::url($tournamentData['banner']) }}" 
                                                             alt="Tournament Banner" 
                                                             style="max-width: 300px; max-height: 150px; border-radius: 8px;">
                                                    @else
                                                        <div class="text-muted">No banner uploaded</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-box mt-4">
                                    <i class="bi bi-lightbulb"></i>
                                    <p>
                                        <strong>Ready to create your tournament?</strong><br>
                                        Review all the information above. Once created, you'll be able to:
                                        1. Generate the match schedule automatically
                                        2. Add team players and staff
                                        3. Start managing matches and results
                                    </p>
                                </div>

                                <div class="warning-box mt-3">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <p>
                                        <strong>Important:</strong> Make sure all information is correct before
                                        proceeding.
                                        Tournament name, dates, type, and selected teams cannot be easily changed after
                                        creation.
                                    </p>
                                </div>

                                <div class="form-check mt-4">
                                    <input class="form-check-input @error('confirmTournament') is-invalid @enderror"
                                        type="checkbox" id="confirmTournament" name="confirmTournament" value="1" {{ old('confirmTournament') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="confirmTournament">
                                        I confirm that all information provided is correct and I want to create this
                                        tournament
                                        <span class="required">*</span>
                                    </label>
                                    @error('confirmTournament')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <input type="hidden" name="confirmTournament_required" id="confirmTournament_required"
                                    value="{{ old('confirmTournament') ? '1' : '0' }}">
                            </div>
                        </div>
                    </div>

                    <div class="step-navigation">
                        <button type="button" class="step-btn prev" id="prevBtn"
                            style="display: {{ $currentStep > 1 ? 'inline-flex' : 'none' }};">
                            <i class="bi bi-arrow-left"></i>
                            <span>Previous</span>
                        </button>

                        @if($currentStep < 5)
                            <button type="submit" class="step-btn next" id="nextBtn" name="action" value="next">
                                <i class="bi bi-arrow-right"></i>
                                <span>Next:
                                    @if($currentStep == 1) Team Selection
                                    @elseif($currentStep == 2) Group Assignment
                                    @elseif($currentStep == 3) Match Rules
                                    @elseif($currentStep == 4) Review
                                    @endif
                                </span>
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary btn-lg" id="createBtn" name="action"
                                value="create">
                                <i class="bi bi-check-circle"></i>
                                <span>Create Tournament</span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-eye"></i>
                            Tournament Preview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="tournament-preview mb-4">
                            <h6>QUICK PREVIEW</h6>
                            <h4 id="previewName">{{ old('name', $tournamentData['name'] ?? '--') }}</h4>

                            <div class="preview-grid">
                                <div class="preview-item">
                                    <label>TEAMS</label>
                                    <div class="value" id="previewTeamCount">
                                        {{ count(old('teams', $tournamentData['teams'] ?? [])) }}
                                    </div>
                                </div>
                                <div class="preview-item">
                                    <label>MATCHES</label>
                                    <div class="value" id="previewMatchCount">0</div>
                                </div>
                                <div class="preview-item">
                                    <label>DURATION</label>
                                    <div class="value" id="previewDurationDays">
                                        @if(!empty($tournamentData['start_date']) && !empty($tournamentData['end_date']))
                                            @php
                                                $start = new DateTime($tournamentData['start_date']);
                                                $end = new DateTime($tournamentData['end_date']);
                                                $interval = $start->diff($end);
                                                echo ($interval->days + 1) . ' days';
                                            @endphp
                                        @else
                                            0 days
                                        @endif
                                    </div>
                                </div>
                                <div class="preview-item">
                                    <label>TYPE</label>
                                    <div class="value" id="previewTournamentType">
                                        @php
                                            $type = old('type', $tournamentData['type'] ?? '');
                                            $typeNames = [
                                                'league' => 'League',
                                                'knockout' => 'Knockout',
                                                'group_knockout' => 'Group + Knockout'
                                            ];
                                        @endphp
                                        {{ $typeNames[$type] ?? '--' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="settings-section">
                            <h6><i class="bi bi-people"></i> Selected Teams</h6>
                            <div class="selected-teams-preview" id="selectedTeamsPreview">
                                @if(!empty($tournamentData['teams']))
                                    @foreach($teams->whereIn('id', $tournamentData['teams'])->take(5) as $team)
                                        @php
                                            $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                        @endphp
                                        <div class="selected-team-item">
                                            @if($logoUrl)
                                                <img src="{{ $logoUrl }}" alt="{{ $team->name }}"
                                                    style="width: 30px; height: 30px; border-radius: 6px; object-fit: cover;">
                                            @else
                                                <div class="selected-team-logo">{{ substr($team->name, 0, 1) }}</div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $team->name }}</div>
                                                @if($team->coach_name)
                                                    <small class="text-muted">Coach: {{ $team->coach_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(count($tournamentData['teams']) > 5)
                                        <div class="text-center text-muted mt-2">
                                            + {{ count($tournamentData['teams']) - 5 }} more teams
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted">No teams selected yet</div>
                                @endif
                            </div>
                        </div>

                        <div class="quick-stats mt-4">
                            <div class="stat-box">
                                <div class="stat-value" id="quickTotalTeams">
                                    {{ count(old('teams', $tournamentData['teams'] ?? [])) }}
                                </div>
                                <div class="stat-label">Total Teams</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value" id="quickTotalMatches">0</div>
                                <div class="stat-label">Total Matches</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value" id="quickGroups">
                                    {{ old('groups_count', $tournamentData['groups_count'] ?? 0) }}
                                </div>
                                <div class="stat-label">Groups</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value" id="quickDuration">
                                    @if(!empty($tournamentData['start_date']) && !empty($tournamentData['end_date']))
                                        @php
                                            $start = new DateTime($tournamentData['start_date']);
                                            $end = new DateTime($tournamentData['end_date']);
                                            echo $start->diff($end)->days + 1;
                                        @endphp
                                    @else
                                        0
                                    @endif
                                </div>
                                <div class="stat-label">Days</div>
                            </div>
                        </div>

                        <!-- TAMBAHAN PREVIEW LOGO -->
                        <div class="settings-section mt-4">
                            <h6><i class="bi bi-image"></i> Tournament Logo</h6>
                            <div class="text-center">
                                <div id="previewLogoContainer">
                                    @if(!empty($tournamentData['logo']))
                                        <img src="{{ Storage::url($tournamentData['logo']) }}" 
                                             alt="Tournament Logo" 
                                             style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                    @else
                                        <div class="text-muted">
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                            <p>No logo uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="info-box mt-4">
                            <i class="bi bi-lightbulb"></i>
                            <p>
                                <strong>Tournament Preview</strong><br>
                                This preview updates in real-time as you fill out the form. All calculations are based
                                on your current selections.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    
    <script>
        // Fungsi untuk preview logo
        function previewLogo(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('logoPreview');
            const container = document.getElementById('logoPreviewContainer');
            const fileName = document.getElementById('logoFileName');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                    fileName.textContent = file.name;
                    
                    // Update preview di sidebar
                    updateLogoPreview(e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                container.style.display = 'none';
            }
        }

        // Fungsi untuk preview banner
        function previewBanner(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('bannerPreview');
            const container = document.getElementById('bannerPreviewContainer');
            const fileName = document.getElementById('bannerFileName');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                    fileName.textContent = file.name;
                };
                reader.readAsDataURL(file);
            } else {
                container.style.display = 'none';
            }
        }

        // Fungsi untuk update preview logo di sidebar
        function updateLogoPreview(imageSrc) {
            const previewContainer = document.getElementById('previewLogoContainer');
            if (previewContainer) {
                previewContainer.innerHTML = `<img src="${imageSrc}" alt="Tournament Logo" style="max-width: 150px; max-height: 150px; border-radius: 8px;">`;
            }
        }

        let teamsData = {};
        let groupAssignments = {};
        const groupLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

        $(document).ready(function () {
            $('#teams').select2({
                placeholder: 'Select teams to participate',
                allowClear: true,
                width: '100%',
                closeOnSelect: false
            });

            @if(!empty($tournamentData['teams']))
                @foreach($teams->whereIn('id', $tournamentData['teams']) as $team)
                    @php
                        $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                    @endphp
                    teamsData[{{ $team->id }}] = {
                        id: {{ $team->id }},
                        name: "{{ $team->name }}",
                        logo: "{{ $logoUrl }}",
                        coach: "{{ $team->coach_name }}",
                        group: null,
                        seed: 0,
                        assigned: false
                    };
                @endforeach
            @endif

            const existingAssignments = JSON.parse($('#groupAssignments').val() || '[]');
            if (existingAssignments.length > 0) {
                existingAssignments.forEach(assignment => {
                    if (assignment.group && assignment.team_id && teamsData[assignment.team_id]) {
                        teamsData[assignment.team_id].group = assignment.group;
                        teamsData[assignment.team_id].seed = assignment.seed;
                        teamsData[assignment.team_id].assigned = true;
                    }
                });
            }

            updateGroups();

            $('#teams').on('change', function () {
                updateTeamCount();
                updatePreview();
                loadTeamsData();
                updateGroups();
            });

            $('#name').on('input', function () {
                const name = $(this).val();
                const slugInput = $('#slug');

                if (!slugInput.data('customized')) {
                    const slug = name.toLowerCase()
                        .replace(/[^\w\s]/gi, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-+|-+$/g, '');

                    if (slug) {
                        slugInput.val(slug);
                    }
                }

                updatePreview();
            });

            $('#slug').on('input', function () {
                $(this).data('customized', true);
            });

            $('#type').on('change', function () {
                const groupSettings = $('#groupSettings');
                if ($(this).val() === 'group_knockout') {
                    groupSettings.show();
                } else {
                    groupSettings.hide();
                }
                updatePreview();
            });

            $('#groups_count_assign').on('change', function () {
                updateGroups();
            });

            const previewFields = [
                'name', 'start_date', 'end_date', 'location', 'organizer', 'type',
                'groups_count', 'teams_per_group', 'qualify_per_group',
                'match_duration', 'half_time', 'extra_time',
                'points_win', 'points_draw', 'points_loss', 'points_no_show',
                'max_substitutes', 'yellow_card_suspension',
                'matches_per_day', 'match_interval', 'match_time_slots'
            ];

            previewFields.forEach(fieldId => {
                $('#' + fieldId).on('input change', updatePreview);
            });

            updateTeamCount();
            updatePreview();

            $('#prevBtn').on('click', function () {
                const currentStep = {{ $currentStep }};
                if (currentStep > 1) {
                    $('input[name="current_step"]').val(currentStep - 1);
                    $('input[name="action"]').val('prev');
                    $('#tournamentForm').submit();
                }
            });

            $('#tournamentForm').on('submit', function (e) {
                const currentStep = {{ $currentStep }};
                const action = $('input[name="action"]').val();

                if (action === 'next') {
                    if (!validateStep(currentStep)) {
                        e.preventDefault();
                        return false;
                    }

                    if (currentStep === 3) {
                        saveGroupAssignments();
                    }
                }

                // Tambahkan validasi untuk step 1 dengan logo dan banner
                if (currentStep === 1) {
                    const logoInput = document.getElementById('logo');
                    const bannerInput = document.getElementById('banner');
                    
                    if (logoInput && logoInput.files.length > 0) {
                        const logoFile = logoInput.files[0];
                        if (logoFile.size > 2 * 1024 * 1024) { // 2MB
                            showError('Logo file size must be less than 2MB');
                            e.preventDefault();
                            return false;
                        }
                    }
                    
                    if (bannerInput && bannerInput.files.length > 0) {
                        const bannerFile = bannerInput.files[0];
                        if (bannerFile.size > 5 * 1024 * 1024) { // 5MB
                            showError('Banner file size must be less than 5MB');
                            e.preventDefault();
                            return false;
                        }
                    }
                }

                return true;
            });
        });

        // Perbaikan untuk drag and drop yang lebih baik
        function initializeDragAndDrop() {
            const availableContainer = document.getElementById('availableTeamsContainer');
            
            if (availableContainer) {
                new Sortable(availableContainer, {
                    group: {
                        name: 'shared',
                        pull: 'clone',
                        put: true
                    },
                    animation: 150,
                    sort: false,
                    onAdd: function (evt) {
                        const teamId = evt.item.dataset.teamId;
                        removeTeamFromGroup(teamId);
                    }
                });
            }

            // Initialize Sortable for each group
            document.querySelectorAll('.group-body').forEach(groupBody => {
                new Sortable(groupBody, {
                    group: 'shared',
                    animation: 150,
                    onAdd: function (evt) {
                        const teamId = evt.item.dataset.teamId;
                        const group = evt.to.parentElement.dataset.group;
                        if (teamId && group) {
                            assignTeamToGroup(teamId, group);
                            updateAvailableTeams();
                        }
                    },
                    onUpdate: function (evt) {
                        updateSeedsInGroup(evt.to.parentElement.dataset.group);
                        saveGroupAssignments();
                    }
                });
            });
        }

        function loadTeamsData() {
            const selectedTeams = $('#teams').val() || [];
            teamsData = {};

            selectedTeams.forEach(teamId => {
                const teamOption = $('#teams option[value="' + teamId + '"]');
                teamsData[teamId] = {
                    id: teamId,
                    name: teamOption.data('name') || teamOption.text().split('(')[0].trim(),
                    logo: teamOption.data('logo'),
                    coach: teamOption.data('coach'),
                    group: null,
                    seed: 0,
                    assigned: false
                };
            });

            const existingAssignments = JSON.parse($('#groupAssignments').val() || '[]');
            existingAssignments.forEach(assignment => {
                if (assignment.team_id && teamsData[assignment.team_id]) {
                    teamsData[assignment.team_id].group = assignment.group;
                    teamsData[assignment.team_id].seed = assignment.seed;
                    teamsData[assignment.team_id].assigned = true;
                }
            });
        }

        function updateGroups() {
            const groupsCount = parseInt($('#groups_count_assign').val()) || 2;
            const container = $('#groupsContainer');
            container.empty();

            for (let i = 0; i < groupsCount; i++) {
                const groupLetter = groupLetters[i];
                const groupId = `group-${groupLetter}`;

                const groupHtml = `
                <div class="col-md-${Math.min(12 / groupsCount, 6)} mb-4">
                    <div class="card group-container" id="${groupId}" data-group="${groupLetter}">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-folder"></i> Group ${groupLetter}
                                <span class="badge bg-light text-dark float-end" id="group-count-${groupLetter}">0</span>
                            </h6>
                        </div>
                        <div class="card-body group-body" id="group-body-${groupLetter}">
                            <div class="empty-group-message text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2">Drag teams here</p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                <i class="bi bi-people"></i>
                                <span id="group-teams-count-${groupLetter}">0</span> teams
                            </small>
                        </div>
                    </div>
                </div>
            `;

                container.append(groupHtml);
            }

            updateAvailableTeams();
            updateAssignedTeamsInGroups();
            updateCounts();
            
            // Re-initialize drag and drop setelah groups diupdate
            setTimeout(() => {
                initializeDragAndDrop();
            }, 100);
        }

        function updateAssignedTeamsInGroups() {
            groupLetters.forEach(letter => {
                const groupBody = document.getElementById(`group-body-${letter}`);
                if (groupBody) {
                    groupBody.innerHTML = '<div class="empty-group-message text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">Drag teams here</p></div>';
                }
            });

            Object.values(teamsData).forEach(team => {
                if (team.group && team.assigned) {
                    addTeamToGroupDisplay(team.id, team.group, team.seed);
                }
            });
        }

        function assignTeamToGroup(teamId, group) {
            if (!teamsData[teamId]) return;

            const previousGroup = teamsData[teamId].group;
            if (previousGroup && previousGroup !== group) {
                removeTeamFromGroupDisplay(teamId, previousGroup);
            }

            teamsData[teamId].group = group;
            teamsData[teamId].assigned = true;

            const groupTeams = Object.values(teamsData).filter(t => t.group === group && t.assigned);
            teamsData[teamId].seed = groupTeams.length;

            addTeamToGroupDisplay(teamId, group, teamsData[teamId].seed);

            updateCounts();
            saveGroupAssignments();
        }

        function removeTeamFromGroupDisplay(teamId, group) {
            const groupBody = document.getElementById(`group-body-${group}`);
            if (groupBody) {
                const teamElement = groupBody.querySelector(`[data-team-id="${teamId}"]`);
                if (teamElement) {
                    teamElement.remove();
                }

                if (groupBody.children.length === 0) {
                    groupBody.innerHTML = '<div class="empty-group-message text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">Drag teams here</p></div>';
                }
            }
        }

        function addTeamToGroupDisplay(teamId, group, seed) {
            const team = teamsData[teamId];
            if (!team) return;

            const groupBody = document.getElementById(`group-body-${group}`);
            if (!groupBody) return;

            const emptyMessage = groupBody.querySelector('.empty-group-message');
            if (emptyMessage) {
                emptyMessage.remove();
            }

            const existingElement = groupBody.querySelector(`[data-team-id="${teamId}"]`);
            if (existingElement) {
                existingElement.remove();
            }

            const teamElement = document.createElement('div');
            teamElement.className = 'draggable-item';
            teamElement.dataset.teamId = teamId;
            teamElement.draggable = true;

            let logoHtml = '';
            if (team.logo) {
                logoHtml = `<img src="${team.logo}" alt="${team.name}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">`;
            } else {
                logoHtml = `<div class="team-logo-placeholder small me-2" style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary), var(--secondary-light)); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem;">${team.name.substring(0, 2)}</div>`;
            }

            teamElement.innerHTML = `
            <div class="d-flex align-items-center">
                ${logoHtml}
                <div class="flex-grow-1">
                    <div class="fw-bold">${team.name}</div>
                    ${team.coach ? `<small class="text-muted">Coach: ${team.coach}</small>` : ''}
                </div>
                <div class="ms-2">
                    <span class="badge bg-primary">Seed ${seed}</span>
                </div>
            </div>
        `;

            teamElement.addEventListener('dragstart', function (e) {
                e.dataTransfer.setData('teamId', teamId);
                this.classList.add('dragging');
            });

            teamElement.addEventListener('dragend', function () {
                this.classList.remove('dragging');
            });

            groupBody.appendChild(teamElement);
            updateGroupCount(group);
        }

        function updateSeedsInGroup(group) {
            const groupBody = document.getElementById(`group-body-${group}`);
            if (!groupBody) return;

            const teamElements = groupBody.querySelectorAll('.draggable-item');
            teamElements.forEach((element, index) => {
                const teamId = element.dataset.teamId;
                if (teamsData[teamId]) {
                    teamsData[teamId].seed = index + 1;

                    const badge = element.querySelector('.badge');
                    if (badge) {
                        badge.textContent = `Seed ${index + 1}`;
                    }
                }
            });

            saveGroupAssignments();
        }

        function updateAvailableTeams() {
            const container = $('#availableTeamsContainer');
            container.empty();

            const unassignedTeams = Object.values(teamsData).filter(team => !team.assigned);

            if (unassignedTeams.length === 0) {
                container.html('<div class="text-center text-muted py-4">All teams have been assigned to groups</div>');
                return;
            }

            unassignedTeams.forEach(team => {
                const teamCard = `
                <div class="col-md-3 mb-3 team-card-container" id="available-team-${team.id}">
                    <div class="team-card draggable-item" 
                         data-team-id="${team.id}"
                         draggable="true">
                        <div class="team-logo-placeholder">
                            ${team.logo ? `<img src="${team.logo}" alt="${team.name}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">` : team.name.substring(0, 2)}
                        </div>
                        <h6 class="mb-1">${team.name}</h6>
                        ${team.coach ? `<small class="text-muted">Coach: ${team.coach}</small>` : ''}
                        <div class="mt-2">
                            <span class="badge bg-warning">Available</span>
                        </div>
                    </div>
                </div>
            `;
                container.append(teamCard);
            });

            // Initialize drag and drop untuk available teams
            setTimeout(() => {
                initializeDragAndDrop();
            }, 50);
        }

        function updateGroupCount(group) {
            const groupTeams = Object.values(teamsData).filter(t => t.group === group && t.assigned);
            $(`#group-count-${group}`).text(groupTeams.length);
            $(`#group-teams-count-${group}`).text(groupTeams.length);
        }

        function updateCounts() {
            const totalTeams = Object.keys(teamsData).length;
            const assignedTeams = Object.values(teamsData).filter(t => t.assigned).length;
            const unassignedTeams = totalTeams - assignedTeams;
            const groupsCount = parseInt($('#groups_count_assign').val()) || 0;

            $('#assignedCount').text(assignedTeams);
            $('#unassignedCount').text(unassignedTeams);
            $('#groupsCount').text(groupsCount);

            for (let i = 0; i < groupsCount; i++) {
                const groupLetter = groupLetters[i];
                updateGroupCount(groupLetter);
            }
        }

        function autoDistribute() {
            const groupsCount = parseInt($('#groups_count_assign').val()) || 2;
            const allTeams = Object.keys(teamsData);

            Object.values(teamsData).forEach(team => {
                team.group = null;
                team.seed = 0;
                team.assigned = false;
            });

            const shuffledTeams = [...allTeams].sort(() => Math.random() - 0.5);

            shuffledTeams.forEach((teamId, index) => {
                const groupIndex = index % groupsCount;
                const groupLetter = groupLetters[groupIndex];
                teamsData[teamId].group = groupLetter;
                teamsData[teamId].assigned = true;

                const groupTeams = shuffledTeams.filter((tId, idx) =>
                    idx % groupsCount === groupIndex && idx <= index
                );
                teamsData[teamId].seed = groupTeams.length;
            });

            updateAvailableTeams();
            updateAssignedTeamsInGroups();
            updateCounts();
            saveGroupAssignments();
        }

        function resetGroups() {
            Object.values(teamsData).forEach(team => {
                team.group = null;
                team.seed = 0;
                team.assigned = false;
            });

            groupLetters.forEach(letter => {
                const groupBody = document.getElementById(`group-body-${letter}`);
                if (groupBody) {
                    groupBody.innerHTML = '<div class="empty-group-message text-center text-muted py-4"><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">Drag teams here</p></div>';
                    $(`#group-count-${letter}`).text('0');
                    $(`#group-teams-count-${letter}`).text('0');
                }
            });

            updateAvailableTeams();
            updateCounts();
            saveGroupAssignments();
        }

        function removeTeamFromGroup(teamId) {
            if (!teamsData[teamId]) return;

            const group = teamsData[teamId].group;
            teamsData[teamId].group = null;
            teamsData[teamId].seed = 0;
            teamsData[teamId].assigned = false;

            removeTeamFromGroupDisplay(teamId, group);

            if (group) {
                updateSeedsInGroup(group);
            }

            updateAvailableTeams();
            updateCounts();
            saveGroupAssignments();
        }

        function saveGroupAssignments() {
            const assignments = [];

            Object.values(teamsData).forEach(team => {
                if (team.assigned && team.group) {
                    assignments.push({
                        team_id: team.id,
                        group: team.group,
                        seed: team.seed
                    });
                }
            });

            $('#groupAssignments').val(JSON.stringify(assignments));
        }

        function updateTeamCount() {
            const selectedTeams = $('#teams').val() || [];
            const totalTeams = $('#teams option').length;

            $('#totalTeamsCount').text(totalTeams);
            $('#selectedTeamsCount').text(selectedTeams.length);

            updatePreview();
        }

        function validateStep(step) {
            let isValid = true;

            if (step === 1) {
                if (!$('#name').val()) {
                    showError('Please enter tournament name', $('#name'));
                    isValid = false;
                }

                if (!$('#start_date').val()) {
                    showError('Please select start date', $('#start_date'));
                    isValid = false;
                }

                if (!$('#end_date').val()) {
                    showError('Please select end date', $('#end_date'));
                    isValid = false;
                }

                if (!$('#type').val()) {
                    showError('Please select tournament type', $('#type'));
                    isValid = false;
                }
            }

            if (step === 2) {
                const selectedTeams = $('#teams').val();
                if (!selectedTeams || selectedTeams.length === 0) {
                    showError('Please select at least one team');
                    isValid = false;
                } else if (selectedTeams.length < 2) {
                    showError('Please select at least 2 teams');
                    isValid = false;
                }
            }

            if (step === 3) {
                const tournamentType = $('#type').val();
                if (tournamentType === 'group_knockout') {
                    const assignedTeams = Object.values(teamsData).filter(t => t.assigned).length;
                    const totalTeams = Object.keys(teamsData).length;

                    if (assignedTeams === 0) {
                        showError('Please assign teams to groups. You can use "Auto-distribute" button.');
                        isValid = false;
                    } else if (assignedTeams !== totalTeams) {
                        showError('Please assign all teams to groups. There are still unassigned teams.');
                        isValid = false;
                    }
                }
            }

            return isValid;
        }

        function showError(message, element = null) {
            const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <div>
                    <strong>Validation Error:</strong> ${message}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

            $('.step-content:visible .card-body').prepend(alertHtml);

            window.scrollTo({ top: 0, behavior: 'smooth' });

            if (element) {
                element.focus();
                element[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function updatePreview() {
            const name = $('#name').val() || '--';
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            const location = $('#location').val() || '--';
            const organizer = $('#organizer').val() || '--';
            const type = $('#type').val() || 'league';
            const selectedTeams = $('#teams').val() || [];
            const teamCount = selectedTeams.length;

            $('#previewName').text(name);
            $('#previewTeamCount').text(teamCount);

            const typeNames = {
                'league': 'League',
                'knockout': 'Knockout',
                'group_knockout': 'Group + Knockout'
            };

            $('#previewTournamentType').text(typeNames[type] || '--');

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

                $('#previewDurationDays').text(duration + ' days');
                $('#quickDuration').text(duration);
            } else {
                $('#previewDurationDays').text('0 days');
                $('#quickDuration').text('0');
            }
        }
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebarToggle');

            // Toggle sidebar on button click
            if (toggleButton) {
                toggleButton.addEventListener('click', function () {
                    sidebar.classList.toggle('open');
                });
            }

            // Close sidebar when clicking outside on mobile
            if (sidebar) {
                document.addEventListener('click', function (event) {
                    if (window.innerWidth < 992) {
                        const isClickInsideSidebar = sidebar.contains(event.target);
                        const isClickOnToggle = toggleButton && toggleButton.contains(event.target);

                        if (sidebar.classList.contains('open') && !isClickInsideSidebar && !isClickOnToggle) {
                            sidebar.classList.remove('open');
                        }
                    }
                });
            }
            
            // Initialize drag and drop jika di step 3
            @if($currentStep == 3)
                setTimeout(() => {
                    initializeDragAndDrop();
                }, 500);
            @endif
        });
    </script>
</body>
</html>