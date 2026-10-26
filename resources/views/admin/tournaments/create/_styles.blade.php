{{-- CSS & dependensi halaman buat turnamen --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
.admin-tournament-create {
            --text-primary: #111111;
            --text-secondary: #707072;
            --bg: #f5f5f5;
            --bg-card: #ffffff;
            --surface: #f5f5f5;
            --border: #e5e5e5;
            --primary: #111111;
            --secondary: #39393b;
            --secondary-light: #4b4b4d;
            --accent: #111111;
            --accent-light: #39393b;
            --accent-hover: #39393b;
            --success: #007d48;
            --success-light: #edf7f2;
            --warning: #6b5b36;
            --warning-light: #f5f5f5;
            --danger: #b42318;
            --info: #39393b;
            --light: #f5f5f5;
            --dark: #111111;
            --gray: #707072;
            --gray-light: #9e9ea0;
            --border-color: #e5e5e5;
            --transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, opacity 0.15s ease, transform 0.15s ease;
            max-width: 1540px;
            margin-inline: auto;
            color: #111111;
        }

.page-header {
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
            min-height: 240px;
            border: 1px solid var(--border-color);
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
            padding: 12px;
            min-height: 160px;
            max-height: 400px;
            overflow-y: auto;
        }

        /* Toolbar step 3: input jumlah grup + aksi dalam satu baris */
        .group-toolbar {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .group-toolbar-field label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .group-toolbar-field .form-control {
            width: 90px;
            padding: 6px 10px;
        }

        .group-toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .group-toolbar-actions .btn {
            padding: 6px 14px;
        }

        .group-hint {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 0 0 16px;
        }

        .section-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .available-teams-section {
            margin-bottom: 20px;
        }

        /* Ringkasan status: satu baris teks kecil */
        .group-summary {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--text-secondary);
            padding-top: 12px;
            border-top: 1px solid var(--border-color);
            margin-top: 4px;
        }

        .group-summary span[id] {
            color: var(--text-primary);
            font-weight: 600;
        }

        .dot-sep {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--gray-light);
            display: inline-block;
        }

        .team-logo-placeholder {
            width: 32px;
            height: 32px;
            background: var(--surface);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .team-logo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            border-radius: 6px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: border-color 0.15s ease;
            cursor: move;
        }

        .team-card-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }

        .team-card-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .team-card .badge {
            font-size: 11px;
            padding: 1px 6px;
            align-self: flex-start;
        }

        .team-card:hover {
            border-color: var(--secondary);
        }

        .team-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tournament-preview {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .review-name {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 16px;
            line-height: 1.2;
        }

        /* Definition list untuk review: label kiri, nilai kanan */
        .review-list {
            margin: 0;
        }

        .review-row {
            display: flex;
            align-items: baseline;
            gap: 16px;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .review-row:last-child {
            border-bottom: none;
        }

        .review-row dt {
            width: 140px;
            flex-shrink: 0;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin: 0;
        }

        .review-row dd {
            margin: 0;
            font-size: 14px;
            color: var(--text-primary);
            font-weight: 500;
        }

        .team-card-coach {
            font-size: 12px;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Section dalam sidebar preview */
        .sidebar-section {
            padding-top: 16px;
            margin-top: 4px;
            border-top: 1px solid var(--border-color);
        }

        /* Team card non-interaktif (sidebar & review) */
        .team-card.static {
            cursor: default;
        }

        .team-card.static:hover {
            border-color: var(--border-color);
        }

        .review-media {
            max-width: 120px;
            max-height: 120px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        .review-banner {
            max-width: 280px;
            max-height: 120px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
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

        /* Section jadwal opsional (collapsible) */
        .schedule-settings summary {
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
            list-style: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .schedule-settings summary::before {
            content: '\F282';
            font-family: 'bootstrap-icons';
            font-size: 12px;
            color: var(--text-secondary);
            transition: transform 0.15s ease;
        }

        .schedule-settings[open] summary::before {
            transform: rotate(90deg);
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

        /* ===== Bracket Preview (Knockout Cup) ===== */
        .bracket-preview {
            display: flex;
            gap: 18px;
            overflow-x: auto;
            padding: 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: var(--surface, #f8fafc);
            min-height: 90px;
        }

        .bracket-preview-empty {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 13px;
            margin: auto;
        }

        .bracket-round {
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-width: 168px;
            flex-shrink: 0;
        }

        .bracket-round-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            text-align: center;
            padding-bottom: 4px;
            border-bottom: 1px solid var(--border-color);
        }

        .bracket-matches {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            gap: 10px;
            flex: 1;
        }

        .bracket-match {
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
            background: var(--surface, #fff);
        }

        .bracket-slot {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            font-size: 12px;
            line-height: 1.2;
            min-height: 26px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
        }

        .bracket-slot:last-child {
            border-bottom: none;
        }

        .bracket-slot .slot-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bracket-slot .slot-logo {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .bracket-slot.slot-bye {
            background: rgba(148, 163, 184, 0.12);
            color: var(--text-secondary);
            font-style: italic;
        }

        .bracket-slot.slot-tbd {
            color: var(--text-secondary);
        }

        .bracket-slot.slot-tbd .slot-name {
            opacity: 0.7;
        }

        .bracket-preview-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 8px;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .bracket-preview-legend .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            display: inline-block;
        }

        .legend-team { background: rgba(52, 152, 219, 0.35); }
        .legend-bye  { background: rgba(148, 163, 184, 0.45); }
        .legend-tbd  { background: transparent; border: 1px dashed var(--text-secondary); }

        /* ===== Team Picker (Step 2) ===== */
        .team-picker-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .team-picker-search {
            position: relative;
            flex: 1;
            min-width: 220px;
        }

        .team-picker-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
            font-size: 14px;
        }

        .team-picker-search .form-control {
            padding-left: 34px;
        }

        .team-picker-counter {
            display: flex;
            gap: 10px;
        }

        .team-picker-counter .counter-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 62px;
            padding: 6px 10px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--surface);
        }

        .team-picker-counter .counter-num {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.1;
        }

        .team-picker-counter .counter-lbl {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--text-secondary);
        }

        .team-picker-counter .counter-box.is-full .counter-num {
            color: var(--accent);
        }

        .team-picker-hint {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-secondary);
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 14px;
        }

        .team-picker-hint.is-warning {
            color: var(--accent);
            border-color: rgba(192, 28, 40, 0.35);
            background: rgba(192, 28, 40, 0.05);
        }

        .team-picker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 10px;
            max-height: 420px;
            overflow-y: auto;
            padding: 2px;
        }

        .team-pick-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
            padding: 10px 12px;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            background: var(--bg-card, #fff);
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
            width: 100%;
        }

        .team-pick-card:hover {
            border-color: var(--secondary);
        }

        .team-pick-card.is-selected {
            border-color: var(--secondary);
            background: rgba(52, 152, 219, 0.06);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.10);
        }

        .team-pick-card.is-disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .team-pick-card .team-pick-check {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--secondary);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
        }

        .team-pick-card.is-selected .team-pick-check {
            display: flex;
        }

        .team-pick-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--surface);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--text-secondary);
            flex-shrink: 0;
            overflow: hidden;
            font-size: 13px;
        }

        .team-pick-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 2px;
        }

        .team-pick-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
            gap: 2px;
        }

        .team-pick-name {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .team-pick-coach {
            font-size: 11.5px;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .team-picker-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 40px 20px;
            color: var(--text-secondary);
            font-size: 13px;
        }

        /* Halaman pembuatan turnamen: panel datar, kontrol konsisten, dan satu alur yang jelas. */
        .admin-tournament-create .page-header {
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 24px;
        }

        .admin-tournament-create .page-heading {
            min-width: 0;
        }

        .admin-tournament-create .page-header h1 {
            margin: 0 0 7px;
            color: #111111;
            font-size: clamp(27px, 2.3vw, 32px);
            font-weight: 600;
            letter-spacing: -0.035em;
            line-height: 1.15;
        }

        .admin-tournament-create .page-subtitle {
            max-width: 720px;
            margin: 0;
            color: #707072;
            font-size: 15px;
            line-height: 1.5;
        }

        .admin-tournament-create .btn-back {
            min-height: 44px;
            height: auto;
            padding: 10px 18px;
            border: 0;
            border-radius: 30px;
            background: #f5f5f5;
            color: #111111;
            white-space: nowrap;
            transition: background-color .15s ease, color .15s ease;
        }

        .admin-tournament-create .btn-back:hover {
            border-color: transparent;
            background: #eaeaec;
            color: #111111;
        }

        .admin-tournament-create .wizard-progress {
            display: block;
            margin-bottom: 24px;
            padding: 18px 20px 14px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #ffffff;
        }

        .admin-tournament-create .step-indicator {
            position: relative;
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            align-items: start;
            gap: 12px;
            margin: 0;
        }

        .admin-tournament-create .step-indicator::before {
            position: absolute;
            z-index: 0;
            top: 17px;
            right: 10%;
            left: 10%;
            height: 1px;
            background: #e5e5e5;
            content: "";
        }

        .admin-tournament-create .step {
            z-index: 1;
            min-width: 0;
            gap: 7px;
        }

        .admin-tournament-create .step::before {
            display: none;
        }

        .admin-tournament-create .step-circle {
            width: 36px;
            height: 36px;
            border: 1px solid #cacacb;
            background: #ffffff;
            color: #707072;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-tournament-create .step.active .step-circle {
            border-color: #111111;
            background: #111111;
            color: #ffffff;
        }

        .admin-tournament-create .step.completed .step-circle {
            border-color: #007d48;
            background: #007d48;
            color: #ffffff;
        }

        .admin-tournament-create .step-label {
            max-width: 100%;
            margin: 0;
            color: #707072;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.35;
            text-align: center;
        }

        .admin-tournament-create .step.active .step-label {
            color: #111111;
            font-weight: 600;
        }

        .admin-tournament-create .tournament-workspace {
            align-items: flex-start;
        }

        .admin-tournament-create .tournament-form-column,
        .admin-tournament-create .tournament-summary-column {
            min-width: 0;
        }

        .admin-tournament-create .tournament-summary-column > .card {
            position: sticky;
            top: 20px;
        }

        .admin-tournament-create .card {
            margin-bottom: 0;
            overflow: hidden;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .admin-tournament-create .card-header {
            min-height: 58px;
            display: flex;
            align-items: center;
            padding: 16px 22px;
            border-bottom: 1px solid #e5e5e5;
            background: #ffffff;
            color: #111111;
        }

        .admin-tournament-create .card-header h5 {
            margin: 0;
            color: #111111;
            font-size: 17px;
            font-weight: 600;
            line-height: 1.35;
        }

        .admin-tournament-create .card-header h5 i {
            color: #707072;
            font-size: 16px;
        }

        .admin-tournament-create .card-body {
            padding: 24px;
        }

        .admin-tournament-create .form-group {
            margin-bottom: 20px;
        }

        .admin-tournament-create .form-label {
            margin-bottom: 7px;
            color: #39393b;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
        }

        .admin-tournament-create .form-label > i {
            color: #707072;
            font-size: 14px;
        }

        .admin-tournament-create .form-control,
        .admin-tournament-create .form-select,
        .admin-tournament-create .select2-container--default .select2-selection--multiple {
            min-height: 44px;
            padding: 9px 12px;
            border: 1px solid #cacacb;
            border-radius: 8px;
            background-color: #ffffff;
            color: #111111;
            font-size: 14px;
            box-shadow: none;
            transition: border-color .15s ease, background-color .15s ease;
        }

        .admin-tournament-create textarea.form-control {
            min-height: 104px;
            resize: vertical;
        }

        .admin-tournament-create .form-control:focus,
        .admin-tournament-create .form-select:focus,
        .admin-tournament-create .select2-container--default.select2-container--focus .select2-selection--multiple {
            padding: 8px 11px;
            border: 2px solid #111111;
            outline: none;
            box-shadow: none;
        }

        .admin-tournament-create .form-control::placeholder {
            color: #8a8a8c;
            opacity: 1;
        }

        .admin-tournament-create .form-text,
        .admin-tournament-create .group-hint {
            margin-top: 6px;
            padding-left: 0;
            color: #707072;
            font-size: 13px;
            line-height: 1.5;
        }

        .admin-tournament-create .form-text i {
            color: inherit;
        }

        .admin-tournament-create .is-invalid,
        .admin-tournament-create .form-control.is-invalid,
        .admin-tournament-create .form-select.is-invalid {
            border-color: #b42318 !important;
        }

        .admin-tournament-create .form-control.is-invalid:focus,
        .admin-tournament-create .form-select.is-invalid:focus {
            border-width: 2px;
            box-shadow: none;
        }

        .admin-tournament-create .invalid-feedback {
            margin-top: 6px;
            color: #b42318;
            font-size: 12px;
        }

        .admin-tournament-create .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: .15em;
            border-color: #9e9ea0;
            box-shadow: none;
        }

        .admin-tournament-create .form-check-input:checked {
            border-color: #111111;
            background-color: #111111;
        }

        .admin-tournament-create .form-check-input:focus {
            border-color: #111111;
            box-shadow: 0 0 0 2px #ffffff, 0 0 0 3px #111111;
        }

        .admin-tournament-create .btn,
        .admin-tournament-create .step-btn,
        .admin-tournament-create .btn-preset {
            min-height: 44px;
            padding: 10px 18px;
            border: 0;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.2;
            box-shadow: none;
            transition: background-color .15s ease, color .15s ease, border-color .15s ease;
        }

        .admin-tournament-create .btn-primary,
        .admin-tournament-create .step-btn.next {
            min-height: 48px;
            padding-inline: 22px;
            background: #111111;
            color: #ffffff;
        }

        .admin-tournament-create .btn-primary:hover,
        .admin-tournament-create .step-btn.next:hover {
            background: #39393b;
            color: #ffffff;
        }

        .admin-tournament-create .step-btn.prev,
        .admin-tournament-create .btn-outline-secondary,
        .admin-tournament-create .btn-preset {
            background: #f5f5f5;
            color: #111111;
        }

        .admin-tournament-create .step-btn.prev:hover,
        .admin-tournament-create .btn-outline-secondary:hover,
        .admin-tournament-create .btn-preset:hover {
            border-color: transparent;
            background: #eaeaec;
            color: #111111;
        }

        .admin-tournament-create .btn-outline-primary {
            background: #f5f5f5;
            color: #111111;
        }

        .admin-tournament-create .btn-outline-primary:hover {
            background: #eaeaec;
            color: #111111;
        }

        .admin-tournament-create .btn-outline-danger {
            background: #fff7f6;
            color: #b42318;
        }

        .admin-tournament-create .btn-outline-danger:hover {
            background: #fce9e7;
            color: #8f1c13;
        }

        .admin-tournament-create .btn-preset.active {
            border-color: transparent;
            background: #111111;
            color: #ffffff;
        }

        .admin-tournament-create .step-navigation {
            gap: 12px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #e5e5e5;
        }

        .admin-tournament-create #createBtn {
            min-height: 48px;
            border-radius: 30px;
            font-size: 14px;
        }

        .admin-tournament-create .settings-section {
            margin: 24px 0 0;
            padding: 20px 0 0;
            border: 0;
            border-top: 1px solid #e5e5e5;
            border-radius: 0;
            background: transparent;
        }

        .admin-tournament-create .settings-section h6 {
            margin-bottom: 16px;
            color: #111111;
            font-size: 15px;
            font-weight: 600;
        }

        .admin-tournament-create .settings-section h6 i {
            color: #707072;
        }

        .admin-tournament-create .file-upload-container {
            min-height: 136px;
            padding: 20px 16px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #f5f5f5;
            color: #39393b;
            text-align: left;
        }

        .admin-tournament-create .file-upload-container:hover {
            border-color: #9e9ea0;
            background: #f5f5f5;
        }

        .admin-tournament-create .file-upload-container > i {
            display: block;
            margin-bottom: 10px;
            color: #39393b;
            font-size: 20px;
        }

        .admin-tournament-create .file-upload-container p {
            margin-bottom: 4px !important;
            color: #111111;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-tournament-create .file-upload-container small {
            color: #707072 !important;
            font-size: 12px;
            line-height: 1.45;
        }

        .admin-tournament-create .file-preview {
            margin-top: 10px;
            padding: 10px 12px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #ffffff;
        }

        .admin-tournament-create .file-preview img,
        .admin-tournament-create .review-media,
        .admin-tournament-create .review-banner {
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            object-fit: contain;
        }

        .admin-tournament-create .step-content .alert {
            padding: 14px 16px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #f5f5f5;
            color: #39393b;
        }

        .admin-tournament-create .step-content .alert-success {
            border-color: #cce7d8;
            background: #edf7f2;
            color: #00643b;
        }

        .admin-tournament-create .step-content .alert-danger {
            border-color: #f0c9c5;
            background: #fff7f6;
            color: #a5281d;
        }

        .admin-tournament-create .team-picker-header {
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .admin-tournament-create .team-picker-search {
            max-width: 440px;
        }

        .admin-tournament-create .team-picker-search .form-control {
            padding-left: 42px;
            border-radius: 24px;
        }

        .admin-tournament-create .team-picker-counter {
            gap: 0;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #ffffff;
        }

        .admin-tournament-create .team-picker-counter .counter-box {
            min-width: 80px;
            padding: 7px 12px;
            border: 0;
            border-left: 1px solid #e5e5e5;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .admin-tournament-create .team-picker-counter .counter-box:first-child {
            border-left: 0;
        }

        .admin-tournament-create .team-picker-counter .counter-num {
            color: #111111;
            font-size: 18px;
        }

        .admin-tournament-create .team-picker-counter .counter-lbl {
            color: #707072;
            font-size: 11px;
        }

        .admin-tournament-create .team-picker-hint {
            margin-bottom: 16px;
            padding: 10px 12px;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            background: #f5f5f5;
            color: #39393b;
            font-size: 13px;
        }

        .admin-tournament-create .team-picker-grid {
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 240px), 1fr));
            gap: 10px;
        }

        .admin-tournament-create .team-pick-card {
            min-height: 68px;
            padding: 12px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #ffffff;
            color: #111111;
            text-align: left;
            box-shadow: none;
        }

        .admin-tournament-create .team-pick-card:hover {
            border-color: #9e9ea0;
            background: #ffffff;
        }

        .admin-tournament-create .team-pick-card.is-selected {
            border: 2px solid #111111;
            padding: 11px;
            background: #f5f5f5;
        }

        .admin-tournament-create .team-pick-card.is-disabled {
            background: #f5f5f5;
        }

        .admin-tournament-create .team-pick-card .team-pick-check {
            top: 50%;
            right: 10px;
            width: 20px;
            height: 20px;
            transform: translateY(-50%);
            background: #111111;
            box-shadow: none;
        }

        .admin-tournament-create .team-pick-card.is-selected .team-pick-check {
            display: flex;
        }

        .admin-tournament-create .team-pick-name {
            padding-right: 24px;
            color: #111111;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-tournament-create .team-pick-coach {
            color: #707072;
            font-size: 12px;
        }

        .admin-tournament-create .team-pick-logo,
        .admin-tournament-create .team-logo-placeholder {
            border-color: #e5e5e5;
            border-radius: 6px;
            background: #f5f5f5;
            color: #39393b;
        }

        .admin-tournament-create .team-picker-empty {
            min-height: 100px;
            padding: 24px 16px;
            color: #707072;
        }

        .admin-tournament-create .group-container,
        .admin-tournament-create .available-teams-container {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #f5f5f5;
        }

        .admin-tournament-create .available-teams-container {
            min-height: 120px;
            padding: 16px;
        }

        .admin-tournament-create .group-container.drop-active {
            border-color: #111111;
            border-style: solid;
            background: #f5f5f5;
        }

        .admin-tournament-create .group-header {
            margin: -1px -1px 0;
            border-radius: 8px 8px 0 0;
            background: #f5f5f5;
            color: #111111;
        }

        .admin-tournament-create .draggable-item,
        .admin-tournament-create .team-card {
            border-color: #e5e5e5;
            border-radius: 6px;
            background: #ffffff;
            box-shadow: none;
        }

        .admin-tournament-create .team-card.available,
        .admin-tournament-create .team-card.assigned {
            border-color: #e5e5e5;
            background: #ffffff;
        }

        .admin-tournament-create .draggable-item:hover,
        .admin-tournament-create .team-card:hover {
            border-color: #9e9ea0;
        }

        .admin-tournament-create .tournament-preview,
        .admin-tournament-create .preview-item,
        .admin-tournament-create .stat-box {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .admin-tournament-create .tournament-preview {
            margin-bottom: 20px;
            padding: 18px;
        }

        .admin-tournament-create .review-name {
            margin-bottom: 14px;
            color: #111111;
            font-size: 18px;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .admin-tournament-create .review-row {
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-color: #e5e5e5;
        }

        .admin-tournament-create .review-row dt {
            width: 98px;
            color: #707072;
            font-size: 12px;
        }

        .admin-tournament-create .review-row dd {
            min-width: 0;
            color: #111111;
            font-size: 13px;
            font-weight: 500;
            overflow-wrap: anywhere;
        }

        .admin-tournament-create .sidebar-section {
            margin-top: 16px;
            padding-top: 16px;
            border-color: #e5e5e5;
        }

        .admin-tournament-create .section-label {
            color: #39393b;
            font-size: 12px;
            font-weight: 600;
        }

        .admin-tournament-create .group-summary {
            border-color: #e5e5e5;
            color: #707072;
        }

        .admin-tournament-create .badge,
        .admin-tournament-create .team-status {
            border-radius: 30px;
        }

        .admin-tournament-create .bg-primary {
            background: #111111 !important;
        }

        .admin-tournament-create .bracket-preview {
            border-color: #e5e5e5;
            border-radius: 8px;
            background: #ffffff;
        }

        .admin-tournament-create .bracket-round-title {
            color: #39393b;
        }

        .admin-tournament-create .bracket-slot,
        .admin-tournament-create .bracket-team-knockout,
        .admin-tournament-create .bracket-team {
            border-color: #e5e5e5;
            background: #ffffff;
            color: #39393b;
        }

        .admin-tournament-create .bracket-slot.slot-bye,
        .admin-tournament-create .bracket-slot.slot-tbd,
        .admin-tournament-create .bracket-team.empty,
        .admin-tournament-create .bracket-team-knockout.empty {
            background: #f5f5f5;
            color: #707072;
        }

        .admin-tournament-create .legend-team,
        .admin-tournament-create .legend-bye {
            background: #e5e5e5;
        }

        .admin-tournament-create .step-content.step3-league,
        .admin-tournament-create .step-content.step3-knockout,
        .admin-tournament-create .step-content.step3-group {
            display: none;
        }

        .admin-tournament-create .step-content[id^="step3"] {
            transition: none;
        }

        @media (max-width: 991.98px) {
            .admin-tournament-create .tournament-summary-column > .card {
                position: static;
            }

            .admin-tournament-create .tournament-summary-column {
                order: 2;
            }
        }

        @media (max-width: 767.98px) {
            .admin-tournament-create .page-header {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 20px;
            }

            .admin-tournament-create .page-header h1 {
                font-size: 27px;
            }

            .admin-tournament-create .wizard-progress {
                margin-bottom: 18px;
                padding: 14px 12px 12px;
            }

            .admin-tournament-create .step-indicator {
                gap: 5px;
            }

            .admin-tournament-create .step-indicator::before {
                top: 15px;
                right: 9%;
                left: 9%;
            }

            .admin-tournament-create .step-circle {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }

            .admin-tournament-create .step-label {
                font-size: 10px;
                overflow-wrap: anywhere;
            }

            .admin-tournament-create .card-header {
                min-height: 54px;
                padding: 14px 16px;
            }

            .admin-tournament-create .card-body {
                padding: 18px 16px;
            }

            .admin-tournament-create .step-navigation {
                align-items: stretch;
                flex-wrap: wrap;
            }

            .admin-tournament-create .step-navigation > * {
                flex: 1 1 auto;
                justify-content: center;
            }

            .admin-tournament-create .team-picker-header {
                align-items: stretch;
                flex-direction: column;
            }

            .admin-tournament-create .team-picker-search {
                max-width: none;
            }

            .admin-tournament-create .team-picker-counter {
                width: 100%;
                justify-content: stretch;
            }

            .admin-tournament-create .team-picker-counter .counter-box {
                flex: 1 1 0;
                min-width: 0;
            }
        }

        @media (max-width: 420px) {
            .admin-tournament-create .page-header h1 {
                font-size: 25px;
            }

            .admin-tournament-create .step-label {
                font-size: 10px;
                line-height: 1.2;
                overflow-wrap: normal;
            }

            .admin-tournament-create .review-row dt {
                width: 82px;
            }

            .admin-tournament-create .step-navigation > * {
                width: 100%;
            }
        }
    </style>
