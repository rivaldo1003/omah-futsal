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
            margin-left: var(--sidebar-width);
        }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
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

        /* Tambahan untuk step content */
        .step-content.step3-league,
        .step-content.step3-knockout,
        .step-content.step3-group {
            display: none;
        }

        .step-content[id^="step3"] {
            transition: opacity 0.3s ease;
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

        .bracket-preview {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .bracket-round {
            margin-bottom: 20px;
        }

        .bracket-match {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            min-height: 60px;
        }

        .bracket-team {
            display: flex;
            align-items: center;
            padding: 5px;
            margin: 2px 0;
            border-radius: 4px;
            cursor: pointer;
        }

        .bracket-team:hover {
            background-color: #f1f3f4;
        }

        .bracket-team.empty {
            min-height: 30px;
            border: 1px dashed #ccc;
        }

        .knockout-seed {
            font-size: 0.7rem;
            color: #6c757d;
            margin-left: auto;
        }

        /* Tambahan untuk knockout bracket */
        .bracket-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .bracket-match-knockout {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            min-width: 200px;
        }

        .bracket-team-knockout {
            padding: 8px;
            margin: 4px 0;
            border-radius: 4px;
            background: #f8f9fa;
            cursor: move;
        }

        .bracket-team-knockout.empty {
            background: #e9ecef;
            border: 1px dashed #adb5bd;
            color: #6c757d;
            text-align: center;
        }

        .bracket-round-title {
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid var(--secondary);
        }
    </style>
</head>

<body>
    <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="bi bi-trophy"></i>
                <span>Futsal Admin</span>
            </div>
            <div class="tagline">Tournament Management System</div>
        </div>
        <nav class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.tournaments.index') }}" class="nav-link active">
                <i class="bi bi-trophy"></i>
                Tournaments
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-people"></i>
                Teams
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-calendar-event"></i>
                Matches
            </a>
            <div class="nav-divider">Settings</div>
            <a href="#" class="nav-link utility-link">
                <i class="bi bi-gear"></i>
                Settings
            </a>
        </nav>
        <div class="user-profile-section">
            <div class="d-flex align-items-center mb-3">
                <div class="user-avatar">A</div>
                <div>
                    <div class="fw-bold">Administrator</div>
                    <small class="text-muted">Super Admin</small>
                </div>
            </div>
            <button class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>
        </div>
    </div>

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

        <!-- Debug session -->
        @php
            $sessionData = session()->get('tournament_data', []);
            \Log::info('View create step ' . ($currentStep ?? 1) . ': session data count - ' . count($sessionData));
        @endphp

        @if(config('app.debug'))
        <div class="alert alert-info d-none" id="debugInfo">
            <strong>Debug Info:</strong>
            Step: {{ $currentStep }}, 
            Session Data: {{ !empty($sessionData) ? count($sessionData) . ' items' : 'Empty' }}
        </div>
        @endif

        <!-- Step indicator -->
        <div class="step-indicator">
            <div class="step {{ $currentStep == 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Basic Info</div>
            </div>
            <div class="step {{ $currentStep == 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Teams</div>
            </div>
            
            <div class="step {{ $currentStep == 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label" id="step3Label">
                    @php
                        $tournamentType = old('type', $tournamentData['type'] ?? 'group_knockout');
                        $step3Labels = [
                            'league' => 'League Setup',
                            'knockout' => 'Bracket Setup',
                            'group_knockout' => 'Groups'
                        ];
                        echo $step3Labels[$tournamentType] ?? 'Groups';
                    @endphp
                </div>
            </div>
            
            <div class="step {{ $currentStep == 4 ? 'active' : '' }} {{ $currentStep > 4 ? 'completed' : '' }}" data-step="4">
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
                <!-- Debug Info -->
                @php
                    $sessionData = session()->get('tournament_data', []);
                    $flashData = session()->get('tournament_data_flash', []);
                    \Log::info('View create step ' . ($currentStep ?? 1) . ': session data count - ' . count($sessionData));
                @endphp

                @if(config('app.debug') || auth()->user()->role === 'admin')
                <div class="alert alert-warning alert-dismissible fade show mb-4" id="debugAlert">
                    <i class="bi bi-bug"></i>
                    <strong>Debug Info:</strong> 
                    Step: {{ $currentStep }}, 
                    Session Data: {{ !empty($sessionData) ? count($sessionData) . ' items' : 'Empty' }},
                    Session ID: {{ session()->getId() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <script>
                // Auto-hide debug alert after 10 seconds
                setTimeout(() => {
                    document.getElementById('debugAlert')?.remove();
                }, 10000);
                </script>
                @endif
                
                <form action="{{ route('admin.tournaments.store.step', ['step' => $currentStep ?? $step ?? 1]) }}"
                    method="POST" id="tournamentForm" class="multi-step-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="current_step" value="{{ $currentStep }}">
                    <!-- <input type="hidden" name="form_action" id="formAction" value="{{ $currentStep < 5 ? 'next' : 'create' }}"> -->

                    <!-- Step 1: Basic Info -->
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

                                <!-- Field untuk logo dan banner -->
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
                                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                                <option value="" disabled {{ old('type', $tournamentData['type'] ?? '') ? '' : 'selected' }}>Select tournament format</option>
                                                <option value="league" {{ (old('type', $tournamentData['type'] ?? '') == 'league') ? 'selected' : '' }}>League (Round Robin)</option>
                                                <option value="knockout" {{ (old('type', $tournamentData['type'] ?? '') == 'knockout') ? 'selected' : '' }}>Knockout (Cup)</option>
                                                <option value="group_knockout" {{ (old('type', $tournamentData['type'] ?? '') == 'group_knockout') ? 'selected' : '' }}>Group Stage + Knockout</option>
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

                                <!-- Group Stage Configuration -->
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

                                <!-- League Configuration -->
                                <div id="leagueSettings" class="settings-section" style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'league') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-trophy"></i> League Configuration</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="league_rounds" class="form-label">
                                                    Number of Rounds
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('league_rounds') is-invalid @enderror" id="league_rounds" name="league_rounds">
                                                    <option value="1" {{ (old('league_rounds', $tournamentData['league_rounds'] ?? 1) == 1) ? 'selected' : '' }}>Single Round</option>
                                                    <option value="2" {{ (old('league_rounds', $tournamentData['league_rounds'] ?? 1) == 2) ? 'selected' : '' }}>Double Round</option>
                                                </select>
                                                @error('league_rounds')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Single round: Each team plays once. Double round: Each team plays home and away.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="league_standings_type" class="form-label">
                                                    Standings Tiebreaker
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('league_standings_type') is-invalid @enderror" id="league_standings_type" name="league_standings_type">
                                                    <option value="total_points" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'total_points') ? 'selected' : '' }}>Total Points</option>
                                                    <option value="head_to_head" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'head_to_head') ? 'selected' : '' }}>Head-to-Head</option>
                                                    <option value="goal_difference" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'goal_difference') ? 'selected' : '' }}>Goal Difference</option>
                                                </select>
                                                @error('league_standings_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    How to break ties when teams have equal points
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="league_allow_draw" name="league_allow_draw" value="1" {{ old('league_allow_draw', $tournamentData['league_allow_draw'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="league_allow_draw">
                                            Allow draws in league matches
                                        </label>
                                    </div>
                                </div>

                                <!-- Knockout Settings -->
                                <div id="knockoutSettings" class="settings-section" style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'knockout') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-trophy"></i> Knockout (Cup) Configuration</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_format" class="form-label">
                                                    Tournament Format
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_format') is-invalid @enderror" id="knockout_format" name="knockout_format">
                                                    <option value="single_elimination" {{ (old('knockout_format', $tournamentData['knockout_format'] ?? 'single_elimination') == 'single_elimination') ? 'selected' : '' }}>Single Elimination</option>
                                                    <option value="double_elimination" {{ (old('knockout_format', $tournamentData['knockout_format'] ?? 'single_elimination') == 'double_elimination') ? 'selected' : '' }}>Double Elimination</option>
                                                </select>
                                                @error('knockout_format')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_teams" class="form-label">
                                                    Number of Teams
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_teams') is-invalid @enderror" id="knockout_teams" name="knockout_teams">
                                                    <option value="2" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 2) ? 'selected' : '' }}>2 Teams (Final)</option>
                                                    <option value="4" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 4) ? 'selected' : '' }}>4 Teams (Semi-Finals)</option>
                                                    <option value="8" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 8) ? 'selected' : '' }}>8 Teams (Quarter-Finals)</option>
                                                    <option value="16" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 16) ? 'selected' : '' }}>16 Teams (Round of 16)</option>
                                                    <option value="32" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 32) ? 'selected' : '' }}>32 Teams</option>
                                                </select>
                                                @error('knockout_teams')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_seeding" class="form-label">
                                                    Seeding Method
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_seeding') is-invalid @enderror" id="knockout_seeding" name="knockout_seeding">
                                                    <option value="random" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'random') ? 'selected' : '' }}>Random Draw</option>
                                                    <option value="ranked" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'ranked') ? 'selected' : '' }}>Ranked Seeding</option>
                                                    <option value="manual" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'manual') ? 'selected' : '' }}>Manual Assignment</option>
                                                </select>
                                                @error('knockout_seeding')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_byes" class="form-label">
                                                    Number of Byes
                                                </label>
                                                <input type="number" class="form-control @error('knockout_byes') is-invalid @enderror" id="knockout_byes" name="knockout_byes"
                                                    value="{{ old('knockout_byes', $tournamentData['knockout_byes'] ?? 0) }}" min="0" max="16">
                                                @error('knockout_byes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Teams that get a free pass to next round
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="knockout_third_place" name="knockout_third_place" value="1" {{ old('knockout_third_place', $tournamentData['knockout_third_place'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="knockout_third_place">
                                            Include third place match
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Team Selection -->
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

                    <!-- STEP 3 CONTENT - GROUP KNOCKOUT -->
                    <div class="step-content step3-group" id="step3Group" 
                         style="display: {{ ($currentStep == 3 && (old('type', $tournamentData['type'] ?? 'group_knockout') == 'group_knockout')) ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-grid-3x3"></i> Group Assignment</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Drag and drop teams between groups.</strong> Drag teams from "Available Teams" to any group, or between groups.
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
                                    <!-- Groups will be generated by JavaScript -->
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

                    <!-- STEP 3 CONTENT - LEAGUE -->
                    <div class="step-content step3-league" id="step3League" 
                         style="display: {{ ($currentStep == 3 && (old('type', $tournamentData['type'] ?? 'group_knockout') == 'league')) ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-trophy"></i> League Configuration</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>League Tournament Setup</strong><br>
                                    In a league tournament, all teams play against each other in a round-robin format.
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="league_seeding" class="form-label">
                                                <i class="bi bi-sort-numeric-down"></i>
                                                Initial Seeding
                                            </label>
                                            <select class="form-select" id="league_seeding" name="league_seeding">
                                                <option value="random" {{ old('league_seeding', $tournamentData['league_seeding'] ?? 'random') == 'random' ? 'selected' : '' }}>Random</option>
                                                <option value="manual" {{ old('league_seeding', $tournamentData['league_seeding'] ?? 'random') == 'manual' ? 'selected' : '' }}>Manual</option>
                                                <option value="rating" {{ old('league_seeding', $tournamentData['league_seeding'] ?? 'random') == 'rating' ? 'selected' : '' }}>By Team Rating</option>
                                            </select>
                                            <div class="form-text">How teams are initially ranked</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="league_match_order" class="form-label">
                                                <i class="bi bi-calendar-week"></i>
                                                Match Schedule Order
                                            </label>
                                            <select class="form-select" id="league_match_order" name="league_match_order">
                                                <option value="sequential" {{ old('league_match_order', $tournamentData['league_match_order'] ?? 'sequential') == 'sequential' ? 'selected' : '' }}>Sequential</option>
                                                <option value="balanced" {{ old('league_match_order', $tournamentData['league_match_order'] ?? 'sequential') == 'balanced' ? 'selected' : '' }}>Balanced</option>
                                                <option value="random" {{ old('league_match_order', $tournamentData['league_match_order'] ?? 'sequential') == 'random' ? 'selected' : '' }}>Random</option>
                                            </select>
                                            <div class="form-text">How matches are scheduled</div>
                                        </div>
                                    </div>
                                </div>

                                @if(!empty($tournamentData['teams']))
                                    <div class="settings-section">
                                        <h6><i class="bi bi-list-ol"></i> Team Seeding (Optional)</h6>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Seed</th>
                                                        <th>Team</th>
                                                        <th>Coach</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="leagueSeedingTable">
                                                    @foreach($teams->whereIn('id', $tournamentData['teams']) as $index => $team)
                                                        <tr data-team-id="{{ $team->id }}">
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    @php
                                                                        $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                                                    @endphp
                                                                    @if($logoUrl)
                                                                        <img src="{{ $logoUrl }}" alt="{{ $team->name }}" 
                                                                             class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                                                    @endif
                                                                    {{ $team->name }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $team->coach_name ?? '-' }}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-outline-primary move-up" onclick="moveTeamUp(this)">
                                                                    <i class="bi bi-arrow-up"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-primary move-down" onclick="moveTeamDown(this)">
                                                                    <i class="bi bi-arrow-down"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" name="league_seeds" id="leagueSeeds" 
                                               value="{{ json_encode($tournamentData['league_seeds'] ?? []) }}">
                                        <div class="form-text">
                                            <i class="bi bi-info-circle"></i>
                                            Adjust team seeding order if needed. Higher seeds play easier schedules.
                                        </div>
                                    </div>
                                @endif

                                <div class="info-box">
                                    <i class="bi bi-calculator"></i>
                                    <p>
                                        <strong>League Calculation:</strong><br>
                                        Total teams: {{ count($tournamentData['teams'] ?? []) }}<br>
                                        Single round robin: {{ $totalMatches = count($tournamentData['teams'] ?? []) * (count($tournamentData['teams'] ?? []) - 1) / 2 }} matches<br>
                                        Double round robin: {{ $totalMatches * 2 }} matches<br>
                                        Each team plays: {{ count($tournamentData['teams'] ?? []) - 1 }} matches (single round)
                                    </p>
                                </div>

                                <div class="warning-box">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <p>
                                        <strong>Note:</strong> League standings will be determined by total points.
                                        Tiebreakers: {{ old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3 CONTENT - KNOCKOUT -->
                    <div class="step-content step3-knockout" id="step3Knockout" 
                         style="display: {{ ($currentStep == 3 && (old('type', $tournamentData['type'] ?? 'group_knockout') == 'knockout')) ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-diagram-2"></i> Knockout Bracket Setup</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Knockout (Cup) Tournament Setup</strong><br>
                                    Single elimination bracket. Teams that lose are eliminated from the tournament.
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bracket_size" class="form-label">
                                                <i class="bi bi-grid-3x3-gap"></i>
                                                Bracket Size
                                                <span class="required">*</span>
                                            </label>
                                            <select class="form-select" id="bracket_size" name="bracket_size" required>
                                                <option value="2" {{ old('bracket_size', $tournamentData['bracket_size'] ?? 8) == 2 ? 'selected' : '' }}>2 Teams (Final)</option>
                                                <option value="4" {{ old('bracket_size', $tournamentData['bracket_size'] ?? 8) == 4 ? 'selected' : '' }}>4 Teams (Semi-Finals)</option>
                                                <option value="8" {{ old('bracket_size', $tournamentData['bracket_size'] ?? 8) == 8 ? 'selected' : '' }}>8 Teams (Quarter-Finals)</option>
                                                <option value="16" {{ old('bracket_size', $tournamentData['bracket_size'] ?? 8) == 16 ? 'selected' : '' }}>16 Teams (Round of 16)</option>
                                                <option value="32" {{ old('bracket_size', $tournamentData['bracket_size'] ?? 8) == 32 ? 'selected' : '' }}>32 Teams</option>
                                            </select>
                                            <div class="form-text">Number of teams in the knockout bracket</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bracket_type" class="form-label">
                                                <i class="bi bi-diagram-3"></i>
                                                Bracket Type
                                            </label>
                                            <select class="form-select" id="bracket_type" name="bracket_type">
                                                <option value="single" {{ old('bracket_type', $tournamentData['bracket_type'] ?? 'single') == 'single' ? 'selected' : '' }}>Single Elimination</option>
                                                <option value="double" {{ old('bracket_type', $tournamentData['bracket_type'] ?? 'single') == 'double' ? 'selected' : '' }}>Double Elimination</option>
                                                <option value="consolation" {{ old('bracket_type', $tournamentData['bracket_type'] ?? 'single') == 'consolation' ? 'selected' : '' }}>With Consolation</option>
                                            </select>
                                            <div class="form-text">Tournament bracket format</div>
                                        </div>
                                    </div>
                                </div>

                                @if(!empty($tournamentData['teams']))
                                    <div class="settings-section">
                                        <h6><i class="bi bi-list-ol"></i> Bracket Seeding</h6>
                                        <p class="text-muted mb-3">Set bracket positions (drag and drop or use auto-seed):</p>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="autoSeedKnockout()">
                                                    <i class="bi bi-shuffle"></i> Auto-seed teams
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearKnockoutSeeds()">
                                                    <i class="bi bi-arrow-clockwise"></i> Clear seeds
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="bracket-preview mb-4">
                                                    <div class="bracket-container" id="bracketPreview">
                                                        <!-- Bracket will be generated by JavaScript -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="available-teams-knockout">
                                                    <h6><i class="bi bi-people"></i> Available Teams</h6>
                                                    <div class="list-group" id="availableKnockoutTeams">
                                                        @foreach($teams->whereIn('id', $tournamentData['teams']) as $team)
                                                            @php
                                                                $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                                            @endphp
                                                            <div class="list-group-item draggable-team" data-team-id="{{ $team->id }}" 
                                                                 data-team-name="{{ $team->name }}" data-team-logo="{{ $logoUrl }}" 
                                                                 draggable="true" ondragstart="dragKnockoutTeam(event)">
                                                                <div class="d-flex align-items-center">
                                                                    @if($logoUrl)
                                                                        <img src="{{ $logoUrl }}" alt="{{ $team->name }}" 
                                                                             class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                                                    @else
                                                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" 
                                                                             style="width: 30px; height: 30px;">
                                                                            {{ substr($team->name, 0, 2) }}
                                                                        </div>
                                                                    @endif
                                                                    <div>
                                                                        <div class="fw-bold">{{ $team->name }}</div>
                                                                        @if($team->coach_name)
                                                                            <small class="text-muted">Coach: {{ $team->coach_name }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="knockout_seeds" id="knockoutSeeds" 
                                               value="{{ json_encode($tournamentData['knockout_seeds'] ?? []) }}">
                                        <div class="form-text">
                                            <i class="bi bi-info-circle"></i>
                                            Drag teams to bracket positions or use auto-seed. Higher seeds have easier matchups.
                                        </div>
                                    </div>
                                @endif

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="knockout_third_place_step3" 
                                                   name="knockout_third_place" value="1" 
                                                   {{ old('knockout_third_place', $tournamentData['knockout_third_place'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="knockout_third_place_step3">
                                                Include third place match
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="knockout_byes_enabled" 
                                                   name="knockout_byes_enabled" value="1" 
                                                   {{ old('knockout_byes_enabled', $tournamentData['knockout_byes_enabled'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="knockout_byes_enabled">
                                                Allow byes (teams advance without playing)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-box">
                                    <i class="bi bi-calculator"></i>
                                    <p>
                                        <strong>Knockout Calculation:</strong><br>
                                        Total matches: <span id="knockoutMatchesCount">0</span><br>
                                        Rounds: <span id="knockoutRoundsCount">0</span><br>
                                        Byes available: <span id="availableByes">0</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Match Rules -->
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

                    <!-- Step 5: Review -->
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

                                <!-- Preview Logo dan Banner -->
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
                                        type="checkbox" id="confirmTournament" name="confirmTournament" value="1" 
                                        {{ old('confirmTournament', isset($tournamentData['confirmTournament']) ? 'checked' : '') }}>
                                    <label class="form-check-label" for="confirmTournament">
                                        <strong>I confirm that all information is correct and I want to create this tournament</strong>
                                        <span class="required">*</span>
                                    </label>
                                    @error('confirmTournament')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="step-navigation">
                        <button type="button" class="step-btn prev" id="prevBtn"
                            style="display: {{ $currentStep > 1 ? 'inline-flex' : 'none' }};"
                            onclick="goToPreviousStep()">
                            <i class="bi bi-arrow-left"></i>
                            <span>Previous</span>
                        </button>

                    <!-- Di bagian akhir form, ganti button create dengan ini -->
                    @if($currentStep == 5)
                        <button type="submit" class="btn btn-primary btn-lg" id="createBtn" name="form_action" value="create">
                            <i class="bi bi-check-circle"></i>
                            <span>Create Tournament</span>
                        </button>
                    @else
                        <button type="submit" class="step-btn next" id="nextBtn" name="form_action" value="next">
                            <i class="bi bi-arrow-right"></i>
                            <span>Next:
                                @if($currentStep == 1) Team Selection
                                @elseif($currentStep == 2) 
                                    @php
                                        $type = old('type', $tournamentData['type'] ?? 'group_knockout');
                                        $step3Labels = [
                                            'league' => 'League Setup',
                                            'knockout' => 'Bracket Setup',
                                            'group_knockout' => 'Groups'
                                        ];
                                    @endphp
                                    {{ $step3Labels[$type] ?? 'Setup' }}
                                @elseif($currentStep == 3) Match Rules
                                @elseif($currentStep == 4) Review
                                @endif
                            </span>
                        </button>
                    @endif
                    </div>
                </form>
            </div>

            <!-- Right Sidebar Preview -->
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

                        <!-- Preview Logo -->
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
    
   <!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script>

    // Quick debug untuk checkbox
    $('#confirmTournament').on('change', function() {
        console.log('Checkbox changed:', this.checked, 'Value:', this.value);
    });

    // Global variables
    let teamsData = {};
    let groupAssignments = {};
    const groupLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
    let knockoutTeams = {};
    let knockoutSeeds = [];

    // ========== UTILITY FUNCTIONS ==========

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
                
                // Update review section
                document.getElementById('reviewLogoContainer').innerHTML = 
                    `<img src="${e.target.result}" alt="Tournament Logo" style="max-width: 150px; max-height: 150px; border-radius: 8px;">`;
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
                
                // Update review section
                document.getElementById('reviewBannerContainer').innerHTML = 
                    `<img src="${e.target.result}" alt="Tournament Banner" style="max-width: 300px; max-height: 150px; border-radius: 8px;">`;
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

    // Fungsi untuk format date
    function formatDate(dateString) {
        if (!dateString) return '-- -- ----';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    // Fungsi untuk show error
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


    // ========== FORM VALIDATION ==========

    // Fungsi untuk validate step - PERBAIKAN
    // ========== FORM VALIDATION ==========

    // Fungsi untuk validate step - PERBAIKAN
    function validateStep(step) {
        let isValid = true;
        const tournamentType = $('#type').val();

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
            
            // Validasi khusus untuk knockout
            if (tournamentType === 'knockout') {
                const bracketSize = parseInt($('#knockout_teams').val()) || 8;
                if (selectedTeams.length > bracketSize) {
                    showError(`Knockout tournament can only have ${bracketSize} teams maximum. You selected ${selectedTeams.length} teams.`);
                    isValid = false;
                }
            }
            
            // Validasi untuk group_knockout - minimal tim berdasarkan groups
            if (tournamentType === 'group_knockout') {
                const groupsCount = parseInt($('#groups_count').val()) || 2;
                const teamsPerGroup = parseInt($('#teams_per_group').val()) || 4;
                const minTeams = groupsCount * 2; // Minimal 2 tim per group
                
                if (selectedTeams.length < minTeams) {
                    showError(`For ${groupsCount} groups, you need at least ${minTeams} teams (2 teams per group)`);
                    isValid = false;
                }
                
                const maxTeams = groupsCount * teamsPerGroup;
                if (selectedTeams.length > maxTeams) {
                    showError(`Maximum teams for ${groupsCount} groups with ${teamsPerGroup} teams per group is ${maxTeams}`);
                    isValid = false;
                }
            }
            
            // Validasi untuk league (tambahkan jika perlu)
            if (tournamentType === 'league') {
                const groupsCount = parseInt($('#groups_count').val()) || 1;
                if (groupsCount > 1) {
                    const minTeams = groupsCount * 2;
                    if (selectedTeams.length < minTeams) {
                        showError(`For ${groupsCount} groups league, you need at least ${minTeams} teams`);
                        isValid = false;
                    }
                }
            }
        }

        if (step === 3) {
            const tournamentType = $('#type').val();
            
            // Untuk League - TIDAK PERLU VALIDASI KHUSUS
            if (tournamentType === 'league') {
                // League tidak memerlukan validasi di step 3
                // Karena setup league sederhana (tidak wajib group assignment)
                console.log('League step 3 validation: passed automatically');
                return true; // Langsung return true untuk league
            }
            
            if (tournamentType === 'group_knockout') {
                // **PERBAIKAN: Tidak wajib semua tim di-assign**
                const assignedTeams = Object.values(teamsData).filter(t => t.assigned).length;
                const totalTeams = Object.keys(teamsData).length;

                if (assignedTeams === 0) {
                    showError('Please assign teams to groups. You can use "Auto-distribute" button.');
                    isValid = false;
                } else if (assignedTeams < totalTeams) {
                    // **HANYA WARNING, bukan error**
                    // Boleh ada tim yang belum di-assign, sistem akan auto-assign nanti
                    console.log(`${totalTeams - assignedTeams} teams are still unassigned, will be auto-assigned later`);
                }
                
                // Validasi jumlah groups
                const groupsCount = parseInt($('#groups_count_assign').val()) || 2;
                if (groupsCount < 1 || groupsCount > 8) {
                    showError('Number of groups must be between 1 and 8');
                    isValid = false;
                }
            }
            
            // Untuk Knockout, tidak perlu step 3 sama sekali
            if (tournamentType === 'knockout') {
                console.log('Knockout step 3: not required');
                return true;
            }
        }

        return isValid;
    }

    // ========== FORM SUBMISSION FIX ==========

   // **PERBAIKAN: Ganti seluruh bagian FORM SUBMISSION FIX dengan ini**
    $(document).ready(function () {
        // Debug logging
        console.log('Tournament form initialized. Current step: {{ $currentStep }}');
        
        // Tangani klik tombol Create
        $(document).on('click', '#createBtn', function(e) {
            console.log('=== CREATE BUTTON CLICKED ===');
            
            // Cek status checkbox
            const isConfirmed = $('#confirmTournament').is(':checked');
            console.log('Checkbox status:', isConfirmed);
            
            if (!isConfirmed) {
                // Tampilkan error jika checkbox tidak dicentang
                showError('You must confirm that all information is correct before creating tournament.');
                return false;
            }
            
            console.log('Submitting form for tournament creation...');
            
            // Submit form tanpa prevent default
            return true; // Biarkan form submit normal
        });
        
        // Tangani klik tombol Next
        $(document).on('click', '#nextBtn', function(e) {
            console.log('=== NEXT BUTTON CLICKED ===');
            
            // Validasi step saat ini
            const currentStep = {{ $currentStep }};
            console.log('Validating step:', currentStep);
            
            if (validateStep(currentStep)) {
                console.log('Validation passed, submitting form...');
                // Submit form tanpa prevent default
                return true; // Biarkan form submit normal
            } else {
                console.log('Validation failed, stopping submission');
                e.preventDefault(); // Hanya prevent default jika validasi gagal
                return false;
            }
        });
        
        // Tangani klik tombol Previous
        $(document).on('click', '#prevBtn', function(e) {
            console.log('=== PREVIOUS BUTTON CLICKED ===');
            console.log('Going to previous step...');
            // Submit form tanpa prevent default
            return true; // Biarkan form submit normal
        });
    });

   
    


    // ========== PREVIEW UPDATES ==========

    // Fungsi untuk update preview
    function updatePreview() {
        const name = $('#name').val() || '--';
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        const location = $('#location').val() || '--';
        const organizer = $('#organizer').val() || '--';
        const type = $('#type').val() || 'league';
        const selectedTeams = $('#teams').val() || [];
        const teamCount = selectedTeams.length;

        // Hitung jumlah pertandingan berdasarkan tipe
        let matchCount = 0;
        if (type === 'league') {
            const rounds = parseInt($('#league_rounds').val()) || 1;
            matchCount = (teamCount * (teamCount - 1) / 2) * rounds;
        } else if (type === 'knockout') {
            const bracketSize = parseInt($('#knockout_teams').val()) || 8;
            matchCount = bracketSize - 1;
            if ($('#knockout_third_place').is(':checked')) {
                matchCount += 1;
            }
        } else if (type === 'group_knockout') {
            const groupsCount = parseInt($('#groups_count').val()) || 2;
            const teamsPerGroup = parseInt($('#teams_per_group').val()) || 4;
            // Group stage matches
            const groupMatches = groupsCount * (teamsPerGroup * (teamsPerGroup - 1) / 2);
            // Knockout matches (qualifiers from each group)
            const qualifyPerGroup = parseInt($('#qualify_per_group').val()) || 2;
            const knockoutTeams = groupsCount * qualifyPerGroup;
            const knockoutMatches = knockoutTeams - 1;
            matchCount = groupMatches + knockoutMatches;
        }

        $('#previewName').text(name);
        $('#previewTeamCount').text(teamCount);
        $('#previewMatchCount').text(matchCount);
        $('#quickTotalMatches').text(matchCount);

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
            
            // Update review dates
            $('#reviewDates').text(
                `${formatDate(startDate)} to ${formatDate(endDate)}`
            );
        } else {
            $('#previewDurationDays').text('0 days');
            $('#quickDuration').text('0');
        }
        
        // Update review sections
        $('#reviewName').text(name);
        $('#reviewType').text(typeNames[type] || 'Select Type');
        $('#reviewTeams').text(teamCount + ' teams');
        $('#reviewMatches').text(matchCount + ' matches');
        $('#reviewLocation').text(location);
        $('#reviewOrganizer').text(organizer);
        $('#reviewDuration').text(
            `${$('#match_duration').val() || 40} mins (${$('#half_time').val() || 10} mins half)`
        );
        $('#reviewPoints').text(
            `Win: ${$('#points_win').val() || 3}, Draw: ${$('#points_draw').val() || 1}, Loss: ${$('#points_loss').val() || 0}`
        );
        $('#reviewSubstitutes').text($('#max_substitutes').val() || 5);
        
        // Update quick stats
        $('#quickTotalTeams').text(teamCount);
        $('#quickGroups').text($('#groups_count').val() || 0);
    }

    // Fungsi untuk update team count
    function updateTeamCount() {
        const selectedTeams = $('#teams').val() || [];
        const totalTeams = $('#teams option').length;

        $('#totalTeamsCount').text(totalTeams);
        $('#selectedTeamsCount').text(selectedTeams.length);
        updatePreview();
    }

    // ========== STEP 3 - GROUP KNOCKOUT ==========

    // Fungsi untuk update step 3 content
    function updateStep3Content(tournamentType) {
        // Hide semua step 3 content
        $('.step-content[id^="step3"]').hide();
        
        // Update label step 3
        const step3Labels = {
            'league': 'League Setup',
            'knockout': 'Bracket Setup',
            'group_knockout': 'Groups'
        };
        $('#step3Label').text(step3Labels[tournamentType] || 'Groups');
        
        // Show content yang sesuai
        if (tournamentType === 'league') {
            $('#step3League').show();
            initializeLeagueSeeding();
        } else if (tournamentType === 'knockout') {
            // **FIX: Knockout skip step 3, langsung ke step 4**
            // Tapi jika di step 3, tetap tampilkan content
            $('#step3Knockout').show();
            initializeKnockoutBracket();
        } else if (tournamentType === 'group_knockout') {
            $('#step3Group').show();
            initializeGroupAssignment();
        }
        
        // Update next button text
        updateNextButtonText();
    }

    // Fungsi untuk load teams data
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

    // Fungsi untuk initialize group assignment - PERBAIKAN
    function initializeGroupAssignment() {
        // Load teams data dari select2
        const selectedTeams = $('#teams').val() || [];
        
        // Reset teamsData
        teamsData = {};
        
        // Load teams dari select2
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
        
        // Load existing assignments dari hidden input
        const existingAssignments = JSON.parse($('#groupAssignments').val() || '[]');
        existingAssignments.forEach(assignment => {
            if (assignment.team_id && teamsData[assignment.team_id]) {
                teamsData[assignment.team_id].group = assignment.group;
                teamsData[assignment.team_id].seed = assignment.seed;
                teamsData[assignment.team_id].assigned = true;
            }
        });
        
        // Update groups dan UI
        updateGroups();
        
        // Auto-distribute jika belum ada assignments
        if (existingAssignments.length === 0 && Object.keys(teamsData).length > 0) {
            setTimeout(() => {
                autoDistribute();
            }, 500);
        }
    }

    // Fungsi untuk update groups
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

    // Fungsi untuk initialize drag and drop
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

    // Fungsi untuk assign team ke group
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

    // Fungsi untuk update available teams display
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

    // Fungsi untuk add team ke group display
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

    // Fungsi untuk remove team dari group display
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

    // Fungsi untuk update seeds in group
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

    // Fungsi untuk update group count
    function updateGroupCount(group) {
        const groupTeams = Object.values(teamsData).filter(t => t.group === group && t.assigned);
        $(`#group-count-${group}`).text(groupTeams.length);
        $(`#group-teams-count-${group}`).text(groupTeams.length);
    }

    // Fungsi untuk update counts
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

    // Fungsi untuk auto distribute teams - PERBAIKAN
    function autoDistribute() {
        const groupsCount = parseInt($('#groups_count_assign').val()) || 2;
        
        // Reset all teams
        Object.values(teamsData).forEach(team => {
            team.group = null;
            team.seed = 0;
            team.assigned = false;
        });

        const allTeams = Object.keys(teamsData);
        const shuffledTeams = [...allTeams].sort(() => Math.random() - 0.5);

        // Distribute teams secara merata ke semua groups
        shuffledTeams.forEach((teamId, index) => {
            const groupIndex = index % groupsCount;
            const groupLetter = groupLetters[groupIndex];
            teamsData[teamId].group = groupLetter;
            teamsData[teamId].assigned = true;

            // Hitung seed untuk group ini
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

    // Fungsi untuk reset groups
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

    // Fungsi untuk remove team dari group
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

    // Fungsi untuk save group assignments
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

    // Fungsi untuk handle form submission di step 5
    function handleStep5Submission(e) {
        console.log('=== HANDLE STEP 5 SUBMISSION ===');
        
        // Cek checkbox konfirmasi
        const confirmCheckbox = document.getElementById('confirmTournament');
        if (!confirmCheckbox || !confirmCheckbox.checked) {
            e.preventDefault();
            showError('You must confirm that all information is correct before creating tournament.');
            
            // Scroll ke checkbox
            confirmCheckbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            confirmCheckbox.focus();
            return false;
        }
        
        console.log('Checkbox confirmed, submitting form...');
        
        // **PERBAIKAN: Simpan semua data form ke localStorage sebagai backup**
        const formData = new FormData(document.getElementById('tournamentForm'));
        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });
        
        localStorage.setItem('tournament_form_backup', JSON.stringify(formObject));
        console.log('Form data backed up to localStorage');
        
        return true;
    }

// Pasang event listener untuk form submission di step 5
$(document).ready(function() {
    // Debug info
    console.log('Tournament form initialized. Current step: {{ $currentStep }}');
    console.log('Session tournament data count: {{ count($tournamentData) }}');
    
    // Tangani form submission
    $('#tournamentForm').on('submit', function(e) {
        const currentStep = {{ $currentStep }};
        const formAction = $('button[type="submit"]:focus').val() || $('input[name="form_action"]').val() || 'next';
        
        console.log('=== FORM SUBMISSION ===');
        console.log('Current step:', currentStep);
        console.log('Form action:', formAction);
        
        if (currentStep === 5) {
            return handleStep5Submission(e);
        }
        
        // Untuk step 1-4, validasi dulu
        if (!validateStep(currentStep)) {
            e.preventDefault();
            console.log('Validation failed for step:', currentStep);
            return false;
        }
        
        console.log('Validation passed, submitting form...');
        return true;
    });
    
    // **PERBAIKAN: Simpan form state ke localStorage secara berkala**
    setInterval(function() {
        if ($('#tournamentForm').length) {
            const currentStep = {{ $currentStep }};
            const formData = {
                step: currentStep,
                data: {}
            };
            
            // Simpan data form yang penting
            $('#tournamentForm').find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                if (name && !name.includes('_token')) {
                    if ($(this).is(':checkbox') || $(this).is(':radio')) {
                        formData.data[name] = $(this).is(':checked');
                    } else {
                        formData.data[name] = $(this).val();
                    }
                }
            });
            
            localStorage.setItem('tournament_form_autosave', JSON.stringify(formData));
        }
    }, 5000); // Autosave setiap 5 detik
    
    // **PERBAIKAN: Coba restore dari localStorage jika session kosong**
    @if(empty($tournamentData) && $currentStep > 1)
        console.log('Session data empty, checking localStorage...');
        const savedForm = localStorage.getItem('tournament_form_autosave');
        if (savedForm) {
            console.log('Found saved form data in localStorage');
            // Bisa digunakan untuk recovery jika diperlukan
        }
    @endif
});


// **PERBAIKAN: Function untuk handle semua tombol submit**
$(document).on('click', 'button[type="submit"]', function() {
    const currentStep = {{ $currentStep }};
    const buttonValue = $(this).val();
    
    console.log('Button clicked:', $(this).text(), 'Value:', buttonValue);
    
    // Set form action berdasarkan tombol yang diklik
    if (buttonValue === 'create') {
        $('#formAction').val('create');
    } else {
        $('#formAction').val('next');
    }
    
    // Untuk step 5, validasi checkbox
    if (currentStep === 5 && buttonValue === 'create') {
        const confirmCheckbox = document.getElementById('confirmTournament');
        if (!confirmCheckbox || !confirmCheckbox.checked) {
            showError('You must confirm that all information is correct before creating tournament.');
            confirmCheckbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            confirmCheckbox.focus();
            return false;
        }
    }
    
    return true;
});

    // Fungsi untuk update assigned teams in groups
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

    // ========== STEP 3 - LEAGUE ==========

    // Fungsi untuk initialize league seeding
    function initializeLeagueSeeding() {
        const selectedTeams = $('#teams').val() || [];
        const leagueSeeds = JSON.parse($('#leagueSeeds').val() || '[]');
        
        // Jika belum ada seeds, buat dari selected teams
        if (leagueSeeds.length === 0 && selectedTeams.length > 0) {
            const seeds = selectedTeams.map((teamId, index) => ({
                team_id: parseInt(teamId),
                seed: index + 1
            }));
            $('#leagueSeeds').val(JSON.stringify(seeds));
        }
    }

    // Fungsi untuk move team up/down di league seeding
    function moveTeamUp(button) {
        const row = $(button).closest('tr');
        const prevRow = row.prev();
        
        if (prevRow.length) {
            row.insertBefore(prevRow);
            updateLeagueSeeds();
        }
    }

    function moveTeamDown(button) {
        const row = $(button).closest('tr');
        const nextRow = row.next();
        
        if (nextRow.length) {
            row.insertAfter(nextRow);
            updateLeagueSeeds();
        }
    }

    function updateLeagueSeeds() {
        const seeds = [];
        $('#leagueSeedingTable tr').each(function(index) {
            const teamId = $(this).data('team-id');
            if (teamId) {
                seeds.push({
                    team_id: teamId,
                    seed: index + 1
                });
            }
        });
        $('#leagueSeeds').val(JSON.stringify(seeds));
    }

    // ========== STEP 3 - KNOCKOUT ==========

    // Fungsi untuk initialize knockout bracket
    function initializeKnockoutBracket() {
        const bracketSize = parseInt($('#bracket_size').val()) || 8;
        const selectedTeams = $('#teams').val() || [];
        
        // Load knockout teams data
        loadKnockoutTeamsData();
        
        // Update calculation
        updateKnockoutCalculation();
        
        // Generate bracket preview
        generateBracketPreview(bracketSize, selectedTeams);
    }

    // Fungsi untuk load knockout teams data
    function loadKnockoutTeamsData() {
        const selectedTeams = $('#teams').val() || [];
        knockoutTeams = {};
        
        selectedTeams.forEach(teamId => {
            const teamOption = $('#teams option[value="' + teamId + '"]');
            knockoutTeams[teamId] = {
                id: teamId,
                name: teamOption.data('name') || teamOption.text().split('(')[0].trim(),
                logo: teamOption.data('logo'),
                coach: teamOption.data('coach'),
                seed: 0,
                position: 0
            };
        });
        
        // Load existing seeds
        const existingSeeds = JSON.parse($('#knockoutSeeds').val() || '[]');
        knockoutSeeds = existingSeeds;
    }

    // Fungsi untuk update knockout calculation
    function updateKnockoutCalculation() {
        const bracketSize = parseInt($('#bracket_size').val()) || 8;
        const includeThirdPlace = $('#knockout_third_place_step3').is(':checked');
        
        // Hitung jumlah pertandingan
        let totalMatches = bracketSize - 1;
        if (includeThirdPlace) {
            totalMatches += 1;
        }
        
        // Hitung jumlah round
        let rounds = Math.log2(bracketSize);
        
        // Hitung available byes
        const selectedTeams = $('#teams').val() || [];
        const teamCount = selectedTeams.length;
        const availableByes = Math.max(0, bracketSize - teamCount);
        
        $('#knockoutMatchesCount').text(totalMatches);
        $('#knockoutRoundsCount').text(rounds);
        $('#availableByes').text(availableByes);
    }

    // Fungsi untuk generate bracket preview
    function generateBracketPreview(bracketSize, teams) {
        const container = $('#bracketPreview');
        container.empty();
        
        // Determine number of rounds
        const numRounds = Math.log2(bracketSize);
        
        // Generate each round
        for (let round = 1; round <= numRounds; round++) {
            const roundHtml = `
                <div class="bracket-round">
                    <div class="bracket-round-title">Round ${round}</div>
                    <div class="bracket-round-matches" id="round-${round}">
                        <!-- Matches for this round -->
                    </div>
                </div>
            `;
            container.append(roundHtml);
            
            // Generate matches for this round
            const matchesInRound = bracketSize / Math.pow(2, round);
            const matchContainer = $(`#round-${round}`);
            
            for (let match = 1; match <= matchesInRound; match++) {
                const position = (match - 1) * 2 + 1;
                const matchHtml = `
                    <div class="bracket-match-knockout" data-round="${round}" data-match="${match}">
                        <div class="bracket-team-knockout empty" 
                             data-position="${position}" 
                             data-seed="${position}"
                             ondragover="allowDrop(event)" 
                             ondrop="dropKnockoutTeam(event)" 
                             ondragenter="dragEnter(event)" 
                             ondragleave="dragLeave(event)">
                             Team ${position}
                        </div>
                        <div class="bracket-team-knockout empty" 
                             data-position="${position + 1}" 
                             data-seed="${position + 1}"
                             ondragover="allowDrop(event)" 
                             ondrop="dropKnockoutTeam(event)" 
                             ondragenter="dragEnter(event)" 
                             ondragleave="dragLeave(event)">
                             Team ${position + 1}
                        </div>
                    </div>
                `;
                matchContainer.append(matchHtml);
            }
        }
        
        // Final round
        if (numRounds > 0) {
            const finalRound = numRounds + 1;
            const finalHtml = `
                <div class="bracket-round">
                    <div class="bracket-round-title">Final</div>
                    <div class="bracket-match-knockout" data-round="${finalRound}" data-match="1">
                        <div class="bracket-team-knockout empty" 
                             data-position="1" 
                             data-seed="1"
                             ondragover="allowDrop(event)" 
                             ondrop="dropKnockoutTeam(event)" 
                             ondragenter="dragEnter(event)" 
                             ondragleave="dragLeave(event)">
                             Finalist 1
                        </div>
                        <div class="bracket-team-knockout empty" 
                             data-position="2" 
                             data-seed="2"
                             ondragover="allowDrop(event)" 
                             ondrop="dropKnockoutTeam(event)" 
                             ondragenter="dragEnter(event)" 
                             ondragleave="dragLeave(event)">
                             Finalist 2
                        </div>
                    </div>
                </div>
            `;
            container.append(finalHtml);
        }
        
        // Load existing seeds
        loadExistingKnockoutSeeds();
    }

    // Fungsi untuk drag knockout team
    function dragKnockoutTeam(event) {
        const teamId = event.target.dataset.teamId;
        const teamName = event.target.dataset.teamName;
        const teamLogo = event.target.dataset.teamLogo;
        
        event.dataTransfer.setData('teamId', teamId);
        event.dataTransfer.setData('teamName', teamName);
        event.dataTransfer.setData('teamLogo', teamLogo);
        event.dataTransfer.setData('source', 'available');
    }

    // Fungsi untuk allow drop
    function allowDrop(event) {
        event.preventDefault();
    }

    // Fungsi untuk drag enter
    function dragEnter(event) {
        event.target.classList.add('drop-hover');
    }

    // Fungsi untuk drag leave
    function dragLeave(event) {
        event.target.classList.remove('drop-hover');
    }

    // Fungsi untuk drop knockout team
    function dropKnockoutTeam(event) {
        event.preventDefault();
        event.target.classList.remove('drop-hover');
        
        const teamId = event.dataTransfer.getData('teamId');
        const teamName = event.dataTransfer.getData('teamName');
        const teamLogo = event.dataTransfer.getData('teamLogo');
        const source = event.dataTransfer.getData('source');
        const position = parseInt(event.target.dataset.position);
        const seed = parseInt(event.target.dataset.seed);
        
        if (teamId && teamName) {
            // Update UI
            let logoHtml = '';
            if (teamLogo) {
                logoHtml = `<img src="${teamLogo}" alt="${teamName}" class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">`;
            } else {
                logoHtml = `<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 20px; height: 20px; font-size: 0.7rem;">${teamName.substring(0, 2)}</div>`;
            }
            
            event.target.innerHTML = `${logoHtml} ${teamName}`;
            event.target.classList.remove('empty');
            event.target.dataset.teamId = teamId;
            
            // Update knockout seeds
            updateKnockoutSeeds(teamId, position, seed);
            
            // Jika source adalah available teams, hapus dari list
            if (source === 'available') {
                const teamElement = $(`.draggable-team[data-team-id="${teamId}"]`);
                teamElement.hide();
            }
        }
    }

    // Fungsi untuk update knockout seeds
    function updateKnockoutSeeds(teamId, position, seed) {
        // Remove existing entry for this team
        knockoutSeeds = knockoutSeeds.filter(seed => seed.team_id != teamId);
        
        // Add new entry
        knockoutSeeds.push({
            team_id: parseInt(teamId),
            position: position,
            seed: seed
        });
        
        // Update hidden input
        $('#knockoutSeeds').val(JSON.stringify(knockoutSeeds));
    }

    // Fungsi untuk load existing knockout seeds
    function loadExistingKnockoutSeeds() {
        const seeds = JSON.parse($('#knockoutSeeds').val() || '[]');
        
        seeds.forEach(seed => {
            const teamPosition = $(`.bracket-team-knockout[data-position="${seed.position}"]`);
            if (teamPosition.length > 0) {
                const team = knockoutTeams[seed.team_id];
                if (team) {
                    let logoHtml = '';
                    if (team.logo) {
                        logoHtml = `<img src="${team.logo}" alt="${team.name}" class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">`;
                    } else {
                        logoHtml = `<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 20px; height: 20px; font-size: 0.7rem;">${team.name.substring(0, 2)}</div>`;
                    }
                    
                    teamPosition.html(`${logoHtml} ${team.name}`);
                    teamPosition.removeClass('empty');
                    teamPosition[0].dataset.teamId = team.id;
                    
                    // Hide from available teams
                    $(`.draggable-team[data-team-id="${team.id}"]`).hide();
                }
            }
        });
    }

    // Fungsi untuk auto seed knockout
    function autoSeedKnockout() {
        const bracketSize = parseInt($('#bracket_size').val()) || 8;
        const selectedTeams = $('#teams').val() || [];
        
        // Clear existing seeds
        clearKnockoutSeeds();
        
        // Reset all bracket positions
        $('.bracket-team-knockout').each(function() {
            const position = $(this).data('position');
            $(this).html(`Team ${position}`);
            $(this).addClass('empty');
            $(this).removeAttr('data-team-id');
        });
        
        // Show all available teams
        $('.draggable-team').show();
        
        // Auto assign teams to positions
        const shuffledTeams = [...selectedTeams].sort(() => Math.random() - 0.5);
        const positions = Array.from({length: bracketSize}, (_, i) => i + 1);
        
        shuffledTeams.forEach((teamId, index) => {
            if (index < bracketSize) {
                const position = positions[index];
                const teamPosition = $(`.bracket-team-knockout[data-position="${position}"]`);
                const team = knockoutTeams[teamId];
                
                if (teamPosition.length > 0 && team) {
                    let logoHtml = '';
                    if (team.logo) {
                        logoHtml = `<img src="${team.logo}" alt="${team.name}" class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">`;
                    } else {
                        logoHtml = `<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 20px; height: 20px; font-size: 0.7rem;">${team.name.substring(0, 2)}</div>`;
                    }
                    
                    teamPosition.html(`${logoHtml} ${team.name}`);
                    teamPosition.removeClass('empty');
                    teamPosition[0].dataset.teamId = team.id;
                    
                    // Hide from available teams
                    $(`.draggable-team[data-team-id="${team.id}"]`).hide();
                    
                    // Update seeds
                    updateKnockoutSeeds(teamId, position, position);
                }
            }
        });
    }

    // Fungsi untuk clear knockout seeds
    function clearKnockoutSeeds() {
        knockoutSeeds = [];
        $('#knockoutSeeds').val('[]');
        
        // Clear all bracket positions
        $('.bracket-team-knockout').each(function() {
            const position = $(this).data('position');
            $(this).html(`Team ${position}`);
            $(this).addClass('empty');
            $(this).removeAttr('data-team-id');
        });
        
        // Show all available teams
        $('.draggable-team').show();
    }

    // ========== MAIN DOCUMENT READY ==========

    $(document).ready(function () {
        // Initialize Select2
        $('#teams').select2({
            placeholder: 'Select teams to participate',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });

        // Initialize step 3 content berdasarkan tipe yang dipilih
        const tournamentType = $('#type').val() || 'group_knockout';
        
        // Update step 3 label saat awal load
        const step3Labels = {
            'league': 'League Setup',
            'knockout': 'Bracket Setup',
            'group_knockout': 'Groups'
        };
        $('#step3Label').text(step3Labels[tournamentType] || 'Groups');
        
        // Jika di step 3, tampilkan konten yang sesuai
        if ({{ $currentStep }} == 3) {
            updateStep3Content(tournamentType);
        }

        // Update next button text saat awal
        updateNextButtonText();

        // Initialize teams data
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

        // Load existing assignments
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

        // Initialize groups
        updateGroups();

        // Event listeners
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

        // Tournament type change handler
        $('#type').on('change', function () {
        const selectedType = $(this).val();
        
        // Hide all settings sections
        $('#groupSettings, #leagueSettings, #knockoutSettings').hide();
        
        // Show relevant settings section
        if (selectedType === 'group_knockout') {
            $('#groupSettings').show();
            $('#groups_count').prop('required', true);
            $('#teams_per_group').prop('required', true);
            $('#qualify_per_group').prop('required', true);
        } else if (selectedType === 'league') {
            $('#leagueSettings').show();
            // League bisa memiliki grup atau tidak
            $('#groups_count').prop('required', false);
            $('#teams_per_group').prop('required', false);
            $('#qualify_per_group').prop('required', false);
            
            // **FIX: Reset nilai groups_count jika league tanpa grup**
            if (!$('#groups_count').val() || $('#groups_count').val() < 1) {
                $('#groups_count').val(1); // Default 1 group untuk league
            }
        } else if (selectedType === 'knockout') {
            $('#knockoutSettings').show();
            $('#groups_count').prop('required', false);
            $('#teams_per_group').prop('required', false);
            $('#qualify_per_group').prop('required', false);
        }
        
        // Jika di step 3, update konten
        if ({{ $currentStep }} == 3) {
            updateStep3Content(selectedType);
        }
        
        // Update step 3 label
        const step3Labels = {
            'league': 'League Setup',
            'knockout': 'Bracket Setup',
            'group_knockout': 'Groups'
        };
        $('#step3Label').text(step3Labels[selectedType] || 'Groups');
        
        // Update preview dan next button text
        updatePreview();
        updateNextButtonText();
    });

        // Bracket size change handler
        $('#bracket_size').on('change', function() {
            updateKnockoutCalculation();
            generateBracketPreview(parseInt($(this).val()), $('#teams').val() || []);
        });

        // Preview fields update
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

        // Initialize counts and preview
        updateTeamCount();
        updatePreview();
    });

    // Fungsi untuk update next button text
    function updateNextButtonText() {
        const currentStep = {{ $currentStep }};
        const tournamentType = $('#type').val();
        
        if (currentStep < 5) {
            let nextText = '';
            if (currentStep == 1) {
                nextText = 'Team Selection';
            } else if (currentStep == 2) {
                if (tournamentType === 'league') {
                    nextText = 'League Setup';
                } else if (tournamentType === 'knockout') {
                    nextText = 'Bracket Setup';
                } else if (tournamentType === 'group_knockout') {
                    nextText = 'Groups';
                } else {
                    nextText = 'Setup';
                }
            } else if (currentStep == 3) {
                nextText = 'Match Rules';
            } else if (currentStep == 4) {
                nextText = 'Review';
            }
            
            $('#nextBtn span').html(`Next: ${nextText}`);
        }
    }

    // Sidebar toggle
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
                if ($('#type').val() === 'group_knockout') {
                    initializeGroupAssignment();
                } else if ($('#type').val() === 'knockout') {
                    initializeKnockoutBracket();
                }
            }, 500);
        @endif
    });
</script>
</body>
</html>