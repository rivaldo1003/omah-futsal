{{-- CSS & dependensi halaman buat turnamen --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
:root {
            --primary: var(--text-primary);
            --secondary: var(--accent);
            --secondary-light: var(--accent-hover);
            --accent: #c01c28;
            --accent-light: #e5484d;
            --success: #1E7A46;
            --success-light: #F0F9F4;
            --warning: #B45309;
            --warning-light: #FDF6EC;
            --info: var(--accent);
            --light: var(--surface);
            --dark: var(--text-primary);
            --gray: var(--text-secondary);
            --gray-light: #B4B4B8;
            --border-color: var(--border);
            --transition: all 0.15s ease;
        }.page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0;
        }

        .btn-back {
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-primary);
            border-radius: 6px;
            height: 40px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-back:hover {
            border-color: var(--secondary);
            color: var(--secondary);
        }

        

        .card {
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header h5 {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body {
            padding: 24px;
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
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            transition: var(--transition);
            background: white;
            color: var(--primary);
        }

        .form-control:focus,
        .form-select:focus,
        .select2-selection--multiple:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.12);
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
            border: 1px solid var(--border-color);
            border-radius: 6px;
            min-height: 40px;
            padding: 5px 10px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.12);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--accent);
            border: none;
            border-radius: 6px;
            color: white;
            padding: 4px 8px;
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
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            color: white;
        }

        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            background: var(--bg);
        }

        .btn-outline-secondary:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--bg);
        }

        .alert {
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert i {
            font-size: 1.3rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-success {
            background: var(--success-light);
            border-left: 4px solid var(--success);
            color: var(--success);
        }

        .alert-danger {
            background: var(--danger-light);
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
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }

        .settings-section h6 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
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
            background: var(--accent);
            color: white;
            border: none;
        }

        .step-btn.next:hover {
            background: var(--accent-hover);
            color: white;
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
            background: var(--accent);
            border-color: var(--accent);
            color: white;
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
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            cursor: move;
            transition: var(--transition);
            position: relative;
        }

        .draggable-item:hover {
            border-color: var(--secondary);
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
            background: var(--accent);
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
            background: var(--surface);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
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
            background: var(--surface);
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 20px;
            min-height: 200px;
            margin-bottom: 20px;
        }

        .team-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            transition: border-color 0.15s ease;
            cursor: move;
        }

        .team-card:hover {
            border-color: var(--secondary);
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
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .preview-item {
            padding: 12px;
            background: var(--bg-card);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .preview-item label {
            font-size: 12px;
            color: var(--gray);
            margin-bottom: 4px;
            display: block;
            font-weight: 500;
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
            padding: 16px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 4px;
            color: var(--text-primary);
        }

        .stat-label {
            color: var(--gray);
            font-size: 12px;
            font-weight: 500;
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
            background: var(--surface);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .info-box {
            background: var(--surface);
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
            background: var(--warning-light);
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
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 12px;
        }

        .bg-primary {
            background: var(--accent) !important;
        }@media (max-width: 1200px) {
            .card-body {
                padding: 30px;
            }
        }@media (max-width: 992px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 20px;
            }
            .preview-grid {
                grid-template-columns: 1fr;
            }
        }@media (max-width: 768px) {
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
        }@media (max-width: 576px) {
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
            background: var(--surface);
        }

        .file-upload-container:hover {
            border-color: var(--secondary);
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
        /* Tie-breaker presets */
        .tie-preset {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .btn-preset {
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 13px;
            font-weight: 500;
        }

        .btn-preset:hover {
            border-color: var(--secondary);
            color: var(--secondary);
        }

        .btn-preset.active {
            border-color: var(--secondary);
            color: var(--secondary);
            background: rgba(52, 152, 219, 0.05);
        }
    </style>
