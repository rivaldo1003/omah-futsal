@extends('layouts.admin')

@section('title', 'Pengaturan Lapangan')

@php
    $memberWeekdays = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
@endphp

@push('styles')
    <style>
        .court-settings-page {
            --settings-ink: #111111;
            --settings-charcoal: #39393b;
            --settings-muted: #707072;
            --settings-line: #cacacb;
            --settings-line-soft: #e5e5e5;
            --settings-cloud: #f5f5f5;
            --settings-canvas: #ffffff;
            --settings-success: #007d48;
            --settings-danger: #b42318;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--settings-ink);
            font-size: 16px;
            line-height: 1.5;
        }

        .court-settings-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 32px;
        }

        .court-settings-heading h1 {
            margin: 0 0 8px;
            color: var(--settings-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .court-settings-heading p {
            max-width: 66ch;
            margin: 0;
            color: var(--settings-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .court-settings-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .court-settings-page .btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 9999px;
            box-shadow: none;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            transition: background-color 150ms ease-out, color 150ms ease-out, border-color 150ms ease-out;
        }

        .court-settings-page .btn-primary {
            border-color: var(--settings-ink);
            background: var(--settings-ink);
            color: #ffffff;
        }

        .court-settings-page .btn-primary:hover,
        .court-settings-page .btn-primary:active {
            border-color: var(--settings-charcoal);
            background: var(--settings-charcoal);
            color: #ffffff;
        }

        .court-settings-page .btn-outline-primary,
        .court-settings-page .btn-outline-secondary,
        .court-settings-page .btn-secondary {
            border-color: transparent;
            background: var(--settings-cloud);
            color: var(--settings-ink);
        }

        .court-settings-page .btn-outline-primary:hover,
        .court-settings-page .btn-outline-secondary:hover,
        .court-settings-page .btn-secondary:hover {
            border-color: transparent;
            background: #e5e5e5;
            color: var(--settings-ink);
        }

        .court-settings-page :is(a, button):focus-visible {
            outline: 2px solid var(--settings-ink);
            outline-offset: 3px;
        }

        .court-settings-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid var(--settings-line-soft);
            background: var(--settings-cloud);
            color: var(--settings-charcoal);
            font-size: 14px;
            line-height: 1.5;
        }

        .court-settings-alert i {
            margin-top: 2px;
            color: var(--settings-ink);
        }

        .court-settings-alert--success {
            color: var(--settings-success);
        }

        .court-settings-alert--error {
            color: var(--settings-danger);
        }

        .court-settings-alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .court-settings-grid {
            display: grid;
            grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
            gap: 24px;
            align-items: start;
        }

        .court-settings-column {
            min-width: 0;
        }

        .court-settings-section {
            min-width: 0;
            padding: 24px;
            border: 1px solid var(--settings-line-soft);
            border-radius: 8px;
            background: var(--settings-canvas);
        }

        .court-settings-section+.court-settings-section {
            margin-top: 24px;
        }

        .court-settings-section-heading {
            margin-bottom: 22px;
        }

        .court-settings-section-heading h2 {
            margin: 0 0 6px;
            color: var(--settings-ink);
            font-size: 22px;
            font-weight: 500;
            line-height: 1.25;
        }

        .court-settings-section-heading p {
            max-width: 68ch;
            margin: 0;
            color: var(--settings-muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .court-settings-section-heading--split {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
        }

        .court-settings-section-heading-copy {
            min-width: 0;
        }

        .court-settings-page .form-label {
            margin-bottom: 7px;
            color: var(--settings-ink);
            font-size: 14px;
            font-weight: 500;
        }

        .court-settings-page .form-control,
        .court-settings-page .form-select {
            min-height: 46px;
            border: 1px solid var(--settings-line);
            border-radius: 24px;
            background-color: var(--settings-canvas);
            color: var(--settings-ink);
            font-size: 16px;
            line-height: 1.4;
        }

        .court-settings-page textarea.form-control {
            min-height: auto;
            padding: 12px 16px;
            border-radius: 18px;
            resize: vertical;
        }

        .court-settings-page .form-control::placeholder {
            color: var(--settings-muted);
            opacity: 1;
        }

        .court-settings-page input[type="file"].form-control {
            height: 46px;
            padding: 5px 8px;
            overflow: hidden;
            line-height: 34px;
            white-space: nowrap;
        }

        .court-settings-page input[type="file"].form-control::file-selector-button {
            height: 34px;
            margin: 0 12px 0 0;
            padding: 0 16px;
            border: 0;
            border-radius: 9999px;
            background: var(--settings-cloud);
            color: var(--settings-ink);
            font: inherit;
            font-size: 14px;
            font-weight: 500;
            line-height: 1;
            vertical-align: middle;
            cursor: pointer;
            transition: background-color 150ms ease-out;
        }

        .court-settings-page input[type="file"].form-control::file-selector-button:hover {
            background: #e5e5e5;
        }

        .court-settings-page input[type="file"].form-control::-webkit-file-upload-button {
            height: 34px;
            margin: 0 12px 0 0;
            padding: 0 16px;
            border: 0;
            border-radius: 9999px;
            background: var(--settings-cloud);
            color: var(--settings-ink);
            font: inherit;
            font-size: 14px;
            font-weight: 500;
            line-height: 1;
            vertical-align: middle;
            cursor: pointer;
            transition: background-color 150ms ease-out;
        }

        .court-settings-page input[type="file"].form-control::-webkit-file-upload-button:hover {
            background: #e5e5e5;
        }

        .court-settings-page .form-control:focus,
        .court-settings-page .form-select:focus {
            border: 2px solid var(--settings-ink);
            box-shadow: none;
            outline: none;
        }

        .court-settings-page .form-text {
            margin-top: 6px;
            color: var(--settings-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .court-settings-page .form-check-input:checked {
            border-color: var(--settings-ink);
            background-color: var(--settings-ink);
        }

        .court-settings-page .form-check-input:focus {
            border-color: var(--settings-ink);
            box-shadow: 0 0 0 3px var(--settings-cloud);
        }

        .court-settings-page .form-check-label {
            color: var(--settings-charcoal);
            font-size: 14px;
            line-height: 1.45;
        }

        .court-settings-form-stack {
            display: grid;
            gap: 17px;
        }

        .court-settings-field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .court-settings-field-grid--four {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .court-settings-form-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 8px;
            margin-top: 4px;
        }

        .court-settings-qris {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 14px;
            padding-top: 2px;
        }

        .court-settings-qris img {
            display: block;
            width: 96px;
            height: 96px;
            object-fit: contain;
            padding: 6px;
            border: 1px solid var(--settings-line-soft);
            background: var(--settings-canvas);
        }

        .court-settings-court-list {
            border-top: 1px solid var(--settings-line-soft);
        }

        .court-settings-court {
            padding: 24px 0;
            border-bottom: 1px solid var(--settings-line-soft);
        }

        .court-settings-court-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px 20px;
            margin-bottom: 20px;
        }

        .court-settings-court-title {
            margin: 0;
            color: var(--settings-ink);
            font-size: 18px;
            font-weight: 500;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .court-settings-active-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            margin: 0;
        }

        .court-settings-active-toggle .form-check-input {
            float: none;
            flex: 0 0 auto;
            margin: 0;
        }

        .court-settings-court-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }

        .court-settings-empty {
            padding: 24px 0;
            color: var(--settings-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .court-member-schedule-create {
            display: grid;
            gap: 18px;
            margin-bottom: 24px;
            padding: 20px;
            border: 1px solid var(--settings-line-soft);
            border-radius: 8px;
            background: #fafafa;
        }

        .court-member-schedule-create-heading h3,
        .court-member-schedule-list-heading h3 {
            margin: 0 0 4px;
            color: var(--settings-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.35;
        }

        .court-member-schedule-create-heading p,
        .court-member-schedule-list-heading p {
            margin: 0;
            color: var(--settings-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .court-member-schedule-fields {
            display: grid;
            gap: 16px;
        }

        .court-member-schedule-fields--three {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .court-member-schedule-fields--four {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .court-member-schedule-note-field {
            max-width: 50%;
        }

        .court-member-schedule-form-actions {
            display: flex;
            justify-content: flex-end;
            padding-top: 2px;
        }

        .court-member-schedule-list-heading {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 16px;
            margin: 0 0 8px;
        }

        .court-member-schedule-list {
            border-top: 1px solid var(--settings-line-soft);
        }

        .court-member-schedule-row {
            padding: 20px 0;
            border-bottom: 1px solid var(--settings-line-soft);
        }

        .court-member-schedule-row-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .court-member-schedule-identity {
            min-width: 0;
        }

        .court-member-schedule-identity-line {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px 10px;
        }

        .court-member-schedule-identity h4 {
            margin: 0;
            color: var(--settings-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .court-member-schedule-court {
            margin: 4px 0 0;
            color: var(--settings-muted);
            font-size: 13px;
        }

        .court-member-schedule-status {
            display: inline-flex;
            align-items: center;
            min-height: 24px;
            padding: 2px 9px;
            border-radius: 4px;
            background: #e8f4ed;
            color: #145c3d;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }

        .court-member-schedule-status--paused {
            background: var(--settings-cloud);
            color: var(--settings-charcoal);
        }

        .court-member-schedule-row-controls {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            gap: 8px;
        }

        .court-member-schedule-row-controls .btn,
        .court-member-schedule-exceptions-heading .btn {
            min-height: 36px;
            padding: 7px 13px;
            font-size: 13px;
        }

        .court-member-schedule-facts {
            display: grid;
            grid-template-columns: minmax(180px, .8fr) minmax(260px, 1.2fr);
            gap: 20px;
            margin-top: 16px;
        }

        .court-member-schedule-fact {
            min-width: 0;
        }

        .court-member-schedule-fact span,
        .court-member-schedule-exceptions-heading h5 {
            display: block;
            margin: 0 0 4px;
            color: var(--settings-muted);
            font-size: 12px;
            font-weight: 400;
            line-height: 1.4;
        }

        .court-member-schedule-fact strong {
            display: block;
            color: var(--settings-charcoal);
            font-size: 14px;
            font-weight: 500;
            line-height: 1.45;
            overflow-wrap: anywhere;
        }

        .court-member-schedule-exceptions {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid var(--settings-line-soft);
        }

        .court-member-schedule-exceptions-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .court-member-schedule-exceptions-heading h5 {
            margin: 0;
            color: var(--settings-ink);
            font-size: 13px;
            font-weight: 600;
        }

        .court-member-schedule-day-offs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 10px 0 0;
            padding: 0;
            list-style: none;
        }

        .court-member-schedule-day-off {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 34px;
            padding: 4px 6px 4px 10px;
            border: 1px solid var(--settings-line-soft);
            border-radius: 6px;
            background: var(--settings-canvas);
            color: var(--settings-charcoal);
            font-size: 13px;
        }

        .court-member-schedule-day-off form {
            margin: 0;
        }

        .court-member-schedule-day-off button {
            padding: 3px 6px;
            border: 0;
            border-radius: 4px;
            background: transparent;
            color: var(--settings-danger);
            font-size: 12px;
            font-weight: 500;
        }

        .court-member-schedule-no-days-off {
            margin: 10px 0 0;
            color: var(--settings-muted);
            font-size: 13px;
        }

        .court-member-schedule-empty {
            margin: 0;
            padding: 18px 0 4px;
            color: var(--settings-muted);
            font-size: 14px;
        }

        .court-member-day-off-modal .modal-content {
            border: 1px solid var(--settings-line-soft);
            border-radius: 8px;
            box-shadow: 0 12px 36px rgb(17 17 17 / 14%);
        }

        .court-member-day-off-modal .modal-header,
        .court-member-day-off-modal .modal-footer {
            padding: 18px 22px;
            border-color: var(--settings-line-soft);
        }

        .court-member-day-off-modal .modal-body {
            padding: 22px;
        }

        .court-member-day-off-modal .modal-title {
            color: var(--settings-ink);
            font-size: 18px;
            font-weight: 600;
        }

        .court-add-modal .modal-content {
            border: 1px solid var(--settings-line-soft);
            border-radius: 8px;
            box-shadow: 0 12px 36px rgb(17 17 17 / 14%);
        }

        .court-add-modal .modal-header,
        .court-add-modal .modal-footer {
            padding: 18px 22px;
            border-color: var(--settings-line-soft);
        }

        .court-add-modal .modal-body {
            padding: 22px;
        }

        .court-add-modal .modal-title {
            margin: 0;
            color: var(--settings-ink);
            font-size: 20px;
            font-weight: 600;
        }

        .court-add-intro {
            margin: 0 0 20px;
            color: var(--settings-muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .court-add-form-stack {
            display: grid;
            gap: 20px;
        }

        .court-add-section {
            display: grid;
            gap: 14px;
        }

        .court-add-section + .court-add-section {
            padding-top: 18px;
            border-top: 1px solid var(--settings-line-soft);
        }

        .court-add-section-title {
            margin: 0;
            color: var(--settings-ink);
            font-size: 15px;
            font-weight: 600;
            line-height: 1.4;
        }

        .court-add-fields {
            display: grid;
            gap: 16px;
        }

        .court-add-fields--three {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .court-add-fields--two {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .court-add-advanced {
            padding-top: 2px;
            color: var(--settings-charcoal);
            font-size: 14px;
        }

        .court-add-advanced summary {
            width: fit-content;
            min-height: 44px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--settings-ink);
            font-weight: 500;
            cursor: pointer;
        }

        .court-add-advanced-content {
            max-width: 260px;
            padding-top: 8px;
        }

        .court-add-note {
            margin: 0;
            padding: 12px 14px;
            background: var(--settings-cloud);
            color: var(--settings-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .court-member-day-off-help {
            margin: 12px 0 0;
            color: var(--settings-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        @media (max-width: 1100px) {
            .court-settings-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 24px;
            }

            .court-member-schedule-fields--four {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .court-member-schedule-note-field {
                max-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .court-settings-header {
                gap: 18px;
                margin-bottom: 26px;
            }

            .court-settings-actions {
                width: 100%;
            }

            .court-settings-actions .btn {
                flex: 1 1 auto;
            }

            .court-settings-field-grid,
            .court-settings-field-grid--four {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px 12px;
            }

            .court-member-schedule-fields--three,
            .court-member-schedule-fields--four,
            .court-member-schedule-facts {
                grid-template-columns: minmax(0, 1fr);
            }

            .court-member-schedule-row-header {
                flex-direction: column;
                gap: 14px;
            }

            .court-member-schedule-row-controls {
                width: 100%;
            }

            .court-member-schedule-row-controls .btn {
                flex: 1 1 auto;
            }

            .court-member-schedule-exceptions-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .court-member-schedule-exceptions-heading .btn {
                width: 100%;
            }

            .court-member-schedule-create {
                padding: 16px;
            }

            .court-settings-section-heading h2 {
                font-size: 20px;
            }

            .court-settings-section-heading--split {
                flex-direction: column;
            }

            .court-settings-section-heading--split > .btn {
                width: 100%;
            }

            .court-settings-section {
                padding: 18px;
            }

            .court-settings-court {
                padding: 20px 0;
            }

            .court-settings-court-actions .btn {
                width: 100%;
            }
        }

        @media (max-width: 420px) {

            .court-settings-field-grid,
            .court-settings-field-grid--four {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 640px) {
            .court-add-modal .modal-body,
            .court-add-modal .modal-header,
            .court-add-modal .modal-footer {
                padding: 16px;
            }

            .court-add-fields--three,
            .court-add-fields--two {
                grid-template-columns: minmax(0, 1fr);
            }

            .court-add-modal .modal-footer .btn {
                flex: 1 1 auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .court-settings-page .btn {
                transition: none;
            }

            .court-settings-page input[type="file"].form-control::file-selector-button,
            .court-settings-page input[type="file"].form-control::-webkit-file-upload-button {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $formatRupiah = fn($value) => filled($value)
            ? 'Rp ' . number_format((float) $value, 0, ',', '.')
            : '';
        $addCourtErrors = $errors->getBag('addCourt');
        $addCourtOld = fn($key, $default = null) => session('open_add_court_modal')
            ? old($key, $default)
            : $default;
    @endphp

    <main class="court-settings-page">
        <header class="court-settings-header">
            <div class="court-settings-heading">
                <h1>Pengaturan Booking Lapangan</h1>
                <p>Atur tarif, jam operasional, dan informasi pembayaran yang dilihat pelanggan sebelum memesan.</p>
            </div>
            <nav class="court-settings-actions" aria-label="Navigasi booking lapangan">
                <a href="{{ route('admin.court-rentals.settings') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-box-seam" aria-hidden="true"></i>
                    Pengaturan perlengkapan
                </a>
                <a href="{{ route('admin.court-bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Kembali ke booking
                </a>
            </nav>
        </header>

        @if(session('success'))
            <div class="court-settings-alert court-settings-alert--success" role="status" aria-live="polite">
                <i class="bi bi-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any() || $addCourtErrors->any())
            <div class="court-settings-alert court-settings-alert--error" role="alert">
                <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                <ul>
                    @foreach($errors->getBag('default')->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    @foreach($addCourtErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="court-settings-grid">
            <div class="court-settings-column">
                <section class="court-settings-section" aria-labelledby="payment-settings-heading">
                    <div class="court-settings-section-heading">
                        <h2 id="payment-settings-heading">Informasi pembayaran</h2>
                        <p>Rincian transfer dan aturan booking ditampilkan kepada pelanggan. Pembayaran tetap diperiksa
                            admin.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.court-bookings.settings.payment') }}"
                        enctype="multipart/form-data" class="court-settings-form-stack">
                        @csrf

                        <div>
                            <label class="form-label" for="bankName">Nama bank</label>
                            <input class="form-control" id="bankName" name="bank_name" maxlength="100"
                                value="{{ old('bank_name', $settings->bank_name) }}" placeholder="Contoh: BCA">
                        </div>

                        <div class="court-settings-field-grid">
                            <div>
                                <label class="form-label" for="bankNumber">Nomor rekening</label>
                                <input class="form-control" id="bankNumber" name="bank_account_number" maxlength="100"
                                    value="{{ old('bank_account_number', $settings->bank_account_number) }}">
                            </div>
                            <div>
                                <label class="form-label" for="bankOwner">Nama pemilik rekening</label>
                                <input class="form-control" id="bankOwner" name="bank_account_name" maxlength="120"
                                    value="{{ old('bank_account_name', $settings->bank_account_name) }}">
                            </div>
                        </div>

                        <div>
                            <label class="form-label" for="qrisImage">Gambar QRIS statis <span
                                    class="text-secondary">(opsional)</span></label>
                            <input class="form-control" id="qrisImage" name="qris_image" type="file"
                                accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">QRIS ditampilkan kepada pelanggan. Pembayaran tetap diverifikasi manual.
                            </div>
                        </div>

                        @if($settings->qris_image_path)
                            <div class="court-settings-qris">
                                <img src="{{ asset('storage/' . $settings->qris_image_path) }}" alt="QRIS tersimpan">
                                <label class="form-check court-settings-active-toggle">
                                    <input class="form-check-input" type="checkbox" name="remove_qris_image" value="1">
                                    <span class="form-check-label">Hapus gambar QRIS yang tersimpan</span>
                                </label>
                            </div>
                        @endif

                        <div>
                            <label class="form-label" for="paymentInstructions">Instruksi pembayaran</label>
                            <textarea class="form-control" id="paymentInstructions" name="payment_instructions" rows="3"
                                maxlength="3000"
                                placeholder="Contoh: Cantumkan kode booking pada catatan transfer.">{{ old('payment_instructions', $settings->payment_instructions) }}</textarea>
                        </div>

                        <div>
                            <label class="form-label" for="bookingPolicy">Kebijakan booking <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="bookingPolicy" name="booking_policy" rows="4"
                                maxlength="3000" required
                                placeholder="Jelaskan aturan pembayaran, pembatalan, perubahan jadwal, dan pengembalian dana.">{{ old('booking_policy', $settings->booking_policy) }}</textarea>
                            <div class="form-text">Pelanggan menyetujui aturan ini sebelum membuat booking. Salinannya
                                disimpan bersama booking.</div>
                        </div>

                        <div>
                            <label class="form-label" for="holdMinutes">Lama slot ditahan tanpa bukti (menit)</label>
                            <input class="form-control" id="holdMinutes" name="payment_hold_minutes" type="number" min="10"
                                max="240" value="{{ old('payment_hold_minutes', $settings->payment_hold_minutes) }}">
                            <div class="form-text">Setelah bukti dikirim, slot menunggu verifikasi admin dan tidak
                                kedaluwarsa otomatis.</div>
                        </div>

                        <div class="court-settings-form-actions">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-save" aria-hidden="true"></i>
                                Simpan informasi pembayaran
                            </button>
                        </div>
                    </form>
                </section>

            </div>

            <section class="court-settings-column court-settings-section" aria-labelledby="court-list-heading">
                <div class="court-settings-section-heading court-settings-section-heading--split">
                    <div class="court-settings-section-heading-copy">
                        <h2 id="court-list-heading">Daftar lapangan</h2>
                        <p>Atur nama, tarif, dan jam operasional setiap lapangan. Jika venue pertandingan memakai lapangan yang
                            sama, samakan namanya di pengaturan pertandingan agar jadwal tersebut menutup slot booking publik.
                        </p>
                    </div>
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                        data-bs-target="#addCourtModal" aria-haspopup="dialog" aria-controls="addCourtModal">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        Tambah lapangan
                    </button>
                </div>

                <div class="court-settings-court-list">
                    @forelse($courts as $court)
                        <form class="court-settings-court" method="POST"
                            action="{{ route('admin.court-bookings.courts.update', $court) }}">
                            @csrf
                            @method('PUT')

                            <div class="court-settings-court-header">
                                <h3 class="court-settings-court-title">{{ $court->name }}</h3>
                                <label class="form-check court-settings-active-toggle">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        @checked($court->is_active)>
                                    <span class="form-check-label">Tampilkan untuk booking</span>
                                </label>
                            </div>

                            <div class="court-settings-form-stack">
                                <div>
                                    <label class="form-label" for="courtName{{ $court->id }}">Nama lapangan</label>
                                    <input class="form-control" id="courtName{{ $court->id }}" name="name" required
                                        maxlength="100" value="{{ $court->name }}">
                                </div>

                                <div class="court-settings-field-grid">
                                    <div>
                                        <label class="form-label" for="courtDayRate{{ $court->id }}">Tarif siang per jam</label>
                                        <input class="form-control" id="courtDayRate{{ $court->id }}" name="day_hourly_rate"
                                            type="text" inputmode="numeric" autocomplete="off" maxlength="18" data-rupiah-input
                                            required value="{{ $formatRupiah($court->day_hourly_rate) }}"
                                            placeholder="Rp 300.000">
                                    </div>
                                    <div>
                                        <label class="form-label" for="courtNightRate{{ $court->id }}">Tarif malam per
                                            jam</label>
                                        <input class="form-control" id="courtNightRate{{ $court->id }}" name="night_hourly_rate"
                                            type="text" inputmode="numeric" autocomplete="off" maxlength="18" data-rupiah-input
                                            required value="{{ $formatRupiah($court->night_hourly_rate) }}"
                                            placeholder="Rp 400.000">
                                    </div>
                                    <div>
                                        <label class="form-label" for="courtNightStart{{ $court->id }}">Tarif malam
                                            mulai</label>
                                        <input class="form-control" id="courtNightStart{{ $court->id }}"
                                            name="night_rate_starts_at" type="time" required
                                            value="{{ $court->night_rate_starts_at_formatted }}">
                                        <div class="form-text">Slot yang melewati waktu ini dihitung secara proporsional.</div>
                                    </div>
                                </div>

                                <div class="court-settings-field-grid">
                                    <div>
                                        <label class="form-label" for="courtWeekendDayRate{{ $court->id }}">Tarif siang
                                            Sabtu/Minggu per jam</label>
                                        <input class="form-control" id="courtWeekendDayRate{{ $court->id }}"
                                            name="weekend_day_hourly_rate" type="text" inputmode="numeric" autocomplete="off"
                                            maxlength="18" data-rupiah-input required
                                            value="{{ $formatRupiah($court->weekend_day_hourly_rate) }}">
                                    </div>
                                    <div>
                                        <label class="form-label" for="courtWeekendNightRate{{ $court->id }}">Tarif malam
                                            Sabtu/Minggu per jam</label>
                                        <input class="form-control" id="courtWeekendNightRate{{ $court->id }}"
                                            name="weekend_night_hourly_rate" type="text" inputmode="numeric" autocomplete="off"
                                            maxlength="18" data-rupiah-input required
                                            value="{{ $formatRupiah($court->weekend_night_hourly_rate) }}">
                                    </div>
                                </div>

                                <div class="court-settings-field-grid court-settings-field-grid--four">
                                    <div>
                                        <label class="form-label" for="courtOpen{{ $court->id }}">Buka</label>
                                        <input class="form-control" id="courtOpen{{ $court->id }}" name="opening_time"
                                            type="time" required value="{{ $court->opening_time_formatted }}">
                                    </div>
                                    <div>
                                        <label class="form-label" for="courtClose{{ $court->id }}">Tutup</label>
                                        <input class="form-control" id="courtClose{{ $court->id }}" name="closing_time"
                                            type="time" required value="{{ $court->closing_time_formatted }}">
                                        <div class="form-text">Pilih 00.00 jika tutup setelah lewat tengah malam.</div>
                                    </div>
                                    <div>
                                        <label class="form-label" for="courtDuration{{ $court->id }}">Durasi slot</label>
                                        <input type="hidden" name="slot_duration_minutes" value="30">
                                        <input class="form-control" id="courtDuration{{ $court->id }}" type="text"
                                            value="30 menit" readonly>
                                        <div class="form-text">Durasi booking menggunakan kelipatan 30 menit.</div>
                                    </div>
                                    <div>
                                        <label class="form-label" for="courtOrder{{ $court->id }}">Urutan tampil</label>
                                        <input class="form-control" id="courtOrder{{ $court->id }}" name="sort_order"
                                            type="number" min="0" value="{{ $court->sort_order }}">
                                    </div>
                                </div>
                            </div>

                            <div class="court-settings-court-actions">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bi bi-save" aria-hidden="true"></i>
                                    Simpan perubahan
                                </button>
                            </div>
                        </form>
                    @empty
                        <p class="court-settings-empty">Belum ada lapangan. Tambahkan lapangan agar jadwal booking dapat
                            ditampilkan di website.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="modal fade court-add-modal" id="addCourtModal" tabindex="-1"
            aria-labelledby="addCourtModalTitle" aria-describedby="addCourtModalDescription" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.court-bookings.courts.store') }}">
                        @csrf
                        <div class="modal-header">
                            <div>
                                <h2 class="modal-title" id="addCourtModalTitle">Tambah lapangan</h2>
                                <p class="court-add-intro mb-0" id="addCourtModalDescription">
                                    Atur tarif dan jam operasional. Lapangan baru langsung aktif untuk booking.
                                </p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="court-add-form-stack">
                                <div>
                                    <label class="form-label" for="newCourtName">Nama lapangan</label>
                                    <input class="form-control @error('name', 'addCourt') is-invalid @enderror"
                                        id="newCourtName" name="name" required maxlength="100"
                                        value="{{ $addCourtOld('name', '') }}" placeholder="Contoh: Lapangan 1"
                                        @error('name', 'addCourt') aria-invalid="true" aria-describedby="newCourtNameError" @enderror>
                                    @error('name', 'addCourt')
                                        <div class="invalid-feedback" id="newCourtNameError">{{ $message }}</div>
                                    @enderror
                                </div>

                                <section class="court-add-section" aria-labelledby="courtWeekdayRatesHeading">
                                    <h3 class="court-add-section-title" id="courtWeekdayRatesHeading">Tarif hari biasa</h3>
                                    <div class="court-add-fields court-add-fields--three">
                                        <div>
                                            <label class="form-label" for="newCourtDayRate">Tarif siang per jam</label>
                                            <input class="form-control @error('day_hourly_rate', 'addCourt') is-invalid @enderror"
                                                id="newCourtDayRate" name="day_hourly_rate" type="text" inputmode="numeric"
                                                autocomplete="off" maxlength="18" data-rupiah-input required
                                                value="{{ $formatRupiah($addCourtOld('day_hourly_rate')) }}" placeholder="Rp 300.000"
                                                @error('day_hourly_rate', 'addCourt') aria-invalid="true" aria-describedby="newCourtDayRateError" @enderror>
                                            @error('day_hourly_rate', 'addCourt')
                                                <div class="invalid-feedback" id="newCourtDayRateError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label" for="newCourtNightRate">Tarif malam per jam</label>
                                            <input class="form-control @error('night_hourly_rate', 'addCourt') is-invalid @enderror"
                                                id="newCourtNightRate" name="night_hourly_rate" type="text" inputmode="numeric"
                                                autocomplete="off" maxlength="18" data-rupiah-input required
                                                value="{{ $formatRupiah($addCourtOld('night_hourly_rate')) }}" placeholder="Rp 400.000"
                                                @error('night_hourly_rate', 'addCourt') aria-invalid="true" aria-describedby="newCourtNightRateError" @enderror>
                                            @error('night_hourly_rate', 'addCourt')
                                                <div class="invalid-feedback" id="newCourtNightRateError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label" for="newCourtNightStart">Tarif malam mulai</label>
                                            <input class="form-control @error('night_rate_starts_at', 'addCourt') is-invalid @enderror"
                                                id="newCourtNightStart" name="night_rate_starts_at" type="time"
                                                value="{{ $addCourtOld('night_rate_starts_at', '18:00') }}" required
                                                @error('night_rate_starts_at', 'addCourt') aria-invalid="true" aria-describedby="newCourtNightStartError" @enderror>
                                            @error('night_rate_starts_at', 'addCourt')
                                                <div class="invalid-feedback" id="newCourtNightStartError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </section>

                                <section class="court-add-section" aria-labelledby="courtWeekendRatesHeading">
                                    <h3 class="court-add-section-title" id="courtWeekendRatesHeading">Tarif Sabtu dan Minggu</h3>
                                    <div class="court-add-fields court-add-fields--two">
                                        <div>
                                            <label class="form-label" for="newCourtWeekendDayRate">Tarif siang per jam</label>
                                            <input class="form-control @error('weekend_day_hourly_rate', 'addCourt') is-invalid @enderror"
                                                id="newCourtWeekendDayRate" name="weekend_day_hourly_rate" type="text"
                                                inputmode="numeric" autocomplete="off" maxlength="18" data-rupiah-input required
                                                value="{{ $formatRupiah($addCourtOld('weekend_day_hourly_rate')) }}" placeholder="Rp 300.000"
                                                @error('weekend_day_hourly_rate', 'addCourt') aria-invalid="true" aria-describedby="newCourtWeekendDayRateError" @enderror>
                                            @error('weekend_day_hourly_rate', 'addCourt')
                                                <div class="invalid-feedback" id="newCourtWeekendDayRateError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label" for="newCourtWeekendNightRate">Tarif malam per jam</label>
                                            <input class="form-control @error('weekend_night_hourly_rate', 'addCourt') is-invalid @enderror"
                                                id="newCourtWeekendNightRate" name="weekend_night_hourly_rate" type="text"
                                                inputmode="numeric" autocomplete="off" maxlength="18" data-rupiah-input required
                                                value="{{ $formatRupiah($addCourtOld('weekend_night_hourly_rate')) }}" placeholder="Rp 400.000"
                                                @error('weekend_night_hourly_rate', 'addCourt') aria-invalid="true" aria-describedby="newCourtWeekendNightRateError" @enderror>
                                            @error('weekend_night_hourly_rate', 'addCourt')
                                                <div class="invalid-feedback" id="newCourtWeekendNightRateError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </section>

                                <section class="court-add-section" aria-labelledby="courtHoursHeading">
                                    <h3 class="court-add-section-title" id="courtHoursHeading">Jam operasional</h3>
                                    <div class="court-add-fields court-add-fields--two">
                                        <div>
                                            <label class="form-label" for="newOpen">Buka</label>
                                            <input class="form-control @error('opening_time', 'addCourt') is-invalid @enderror"
                                                id="newOpen" name="opening_time" type="time"
                                                value="{{ $addCourtOld('opening_time', '08:00') }}" required
                                                @error('opening_time', 'addCourt') aria-invalid="true" aria-describedby="newOpenError" @enderror>
                                            @error('opening_time', 'addCourt')
                                                <div class="invalid-feedback" id="newOpenError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label" for="newClose">Tutup</label>
                                            <input class="form-control @error('closing_time', 'addCourt') is-invalid @enderror"
                                                id="newClose" name="closing_time" type="time"
                                                value="{{ $addCourtOld('closing_time', '23:00') }}" required
                                                @error('closing_time', 'addCourt') aria-invalid="true" aria-describedby="newCloseError" @enderror>
                                            <div class="form-text">Pilih 00.00 jika tutup setelah lewat tengah malam.</div>
                                            @error('closing_time', 'addCourt')
                                                <div class="invalid-feedback" id="newCloseError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </section>

                                <details class="court-add-advanced" @if($addCourtErrors->has('sort_order')) open @endif>
                                    <summary>Pengaturan tambahan</summary>
                                    <div class="court-add-advanced-content">
                                        <label class="form-label" for="newOrder">Urutan tampil</label>
                                        <input class="form-control @error('sort_order', 'addCourt') is-invalid @enderror"
                                            id="newOrder" name="sort_order" type="number" min="0" max="1000"
                                            value="{{ $addCourtOld('sort_order', 0) }}"
                                            @error('sort_order', 'addCourt') aria-invalid="true" aria-describedby="newOrderError" @enderror>
                                        @error('sort_order', 'addCourt')
                                            <div class="invalid-feedback" id="newOrderError">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </details>

                                <p class="court-add-note">Pelanggan dapat memesan dalam kelipatan 30 menit. Lapangan baru langsung ditampilkan pada halaman booking.</p>
                            </div>
                            <input type="hidden" name="slot_duration_minutes" value="30">
                            <input type="hidden" name="is_active" value="1">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                Simpan lapangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <section class="court-settings-section mt-4" aria-labelledby="memberScheduleHeading">
            <div class="court-settings-section-heading">
                <h2 id="memberScheduleHeading">Jadwal member</h2>
                <p>Slot rutin ditahan untuk member. Pada tanggal libur, slot dibuka kembali untuk booking umum.</p>
            </div>

            <form method="POST" action="{{ route('admin.court-bookings.member-schedules.store') }}"
                class="court-member-schedule-create">
                @csrf
                <div class="court-member-schedule-create-heading">
                    <h3>Tambah jadwal member</h3>
                    <p>Tentukan lapangan, hari rutin, dan waktu pemakaian member.</p>
                </div>

                <div class="court-member-schedule-fields court-member-schedule-fields--three">
                    <div>
                        <label class="form-label" for="memberScheduleCourt">Lapangan</label>
                        <select class="form-select" id="memberScheduleCourt" name="court_id" required>
                            @foreach($courts as $court)
                            <option value="{{ $court->id }}">{{ $court->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="memberScheduleName">Nama member / grup</label>
                        <input class="form-control" id="memberScheduleName" name="member_name" maxlength="120" required
                            placeholder="Contoh: Member Jumat malam">
                    </div>
                    <div>
                        <label class="form-label" for="memberScheduleDay">Hari rutin</label>
                        <select class="form-select" id="memberScheduleDay" name="day_of_week" required>
                            @foreach($memberWeekdays as $day => $label)
                            <option value="{{ $day }}">{{ $label }}</option>@endforeach
                        </select>
                    </div>
                </div>

                <div class="court-member-schedule-fields court-member-schedule-fields--four">
                    <div>
                        <label class="form-label" for="memberScheduleStart">Jam mulai</label>
                        <input class="form-control" id="memberScheduleStart" name="time_start" type="time" required>
                    </div>
                    <div>
                        <label class="form-label" for="memberScheduleEnd">Jam selesai</label>
                        <input class="form-control" id="memberScheduleEnd" name="time_end" type="time" required>
                    </div>
                    <div>
                        <label class="form-label" for="memberScheduleStartsOn">Berlaku mulai</label>
                        <input class="form-control" id="memberScheduleStartsOn" name="starts_on" type="date">
                    </div>
                    <div>
                        <label class="form-label" for="memberScheduleEndsOn">Berlaku sampai</label>
                        <input class="form-control" id="memberScheduleEndsOn" name="ends_on" type="date">
                    </div>
                </div>

                <div class="court-member-schedule-note-field">
                    <div>
                        <label class="form-label" for="memberScheduleNotes">Catatan</label>
                        <input class="form-control" id="memberScheduleNotes" name="notes" maxlength="500"
                            placeholder="Opsional">
                    </div>
                </div>
                <input type="hidden" name="is_active" value="1">
                <div class="court-member-schedule-form-actions">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        Simpan jadwal
                    </button>
                </div>
            </form>

            <div class="court-member-schedule-list-heading">
                <h3>Jadwal terdaftar</h3>
                <p>{{ $memberSchedules->count() }} jadwal</p>
            </div>

            <div class="court-member-schedule-list">
                @forelse($memberSchedules as $schedule)
                    @php
                        $dayLabel = $memberWeekdays[$schedule->day_of_week] ?? 'Hari tidak valid';
                        $dayOffModalId = 'memberDayOffModal' . $schedule->id;
                    @endphp
                    <article class="court-member-schedule-row" aria-labelledby="memberScheduleName{{ $schedule->id }}">
                        <div class="court-member-schedule-row-header">
                            <div class="court-member-schedule-identity">
                                <div class="court-member-schedule-identity-line">
                                    <h4 id="memberScheduleName{{ $schedule->id }}">{{ $schedule->member_name }}</h4>
                                    <span
                                        class="court-member-schedule-status {{ $schedule->is_active ? '' : 'court-member-schedule-status--paused' }}">
                                        {{ $schedule->is_active ? 'Aktif' : 'Dijeda' }}
                                    </span>
                                </div>
                                <p class="court-member-schedule-court">{{ $schedule->court->name }}</p>
                            </div>

                            <div class="court-member-schedule-row-controls">
                                <form method="POST"
                                    action="{{ route('admin.court-bookings.member-schedules.update', $schedule) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="court_id" value="{{ $schedule->court_id }}">
                                    <input type="hidden" name="member_name" value="{{ $schedule->member_name }}">
                                    <input type="hidden" name="day_of_week" value="{{ $schedule->day_of_week }}">
                                    <input type="hidden" name="time_start" value="{{ substr($schedule->time_start, 0, 5) }}">
                                    <input type="hidden" name="time_end" value="{{ substr($schedule->time_end, 0, 5) }}">
                                    <input type="hidden" name="starts_on" value="{{ $schedule->starts_on?->toDateString() }}">
                                    <input type="hidden" name="ends_on" value="{{ $schedule->ends_on?->toDateString() }}">
                                    <input type="hidden" name="notes" value="{{ $schedule->notes }}">
                                    <input type="hidden" name="is_active" value="{{ $schedule->is_active ? 0 : 1 }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        {{ $schedule->is_active ? 'Jeda jadwal' : 'Aktifkan jadwal' }}
                                    </button>
                                </form>
                                <form method="POST"
                                    action="{{ route('admin.court-bookings.member-schedules.destroy', $schedule) }}"
                                    onsubmit="return confirm(@js('Hapus jadwal member ' . $schedule->member_name . '?'))">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger" type="submit">Hapus jadwal</button>
                                </form>
                            </div>
                        </div>

                        <div class="court-member-schedule-facts">
                            <div class="court-member-schedule-fact">
                                <span>Jadwal rutin</span>
                                <strong>{{ $dayLabel }}, {{ substr($schedule->time_start, 0, 5) }} sampai
                                    {{ substr($schedule->time_end, 0, 5) }}</strong>
                            </div>
                            <div class="court-member-schedule-fact">
                                <span>Masa berlaku</span>
                                <strong>{{ $schedule->starts_on?->format('d M Y') ?? 'Tanpa batas awal' }} sampai
                                    {{ $schedule->ends_on?->format('d M Y') ?? 'Tanpa batas akhir' }}</strong>
                            </div>
                        </div>

                        <div class="court-member-schedule-exceptions">
                            <div class="court-member-schedule-exceptions-heading">
                                <h5>Tanggal libur</h5>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal"
                                    data-bs-target="#{{ $dayOffModalId }}">
                                    <i class="bi bi-calendar-plus" aria-hidden="true"></i>
                                    Jadwalkan tanggal libur
                                </button>
                            </div>

                            @if($schedule->daysOff->isNotEmpty())
                                <ul class="court-member-schedule-day-offs">
                                    @foreach($schedule->daysOff as $dayOff)
                                        <li class="court-member-schedule-day-off">
                                            <span>
                                                <strong>{{ $dayOff->off_date->format('d M Y') }}</strong>
                                                @if($dayOff->note)<span> · {{ $dayOff->note }}</span>@endif
                                            </span>
                                            <form method="POST"
                                                action="{{ route('admin.court-bookings.member-schedules.days-off.destroy', [$schedule, $dayOff]) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    aria-label="Hapus tanggal libur {{ $dayOff->off_date->format('d M Y') }}">Hapus</button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="court-member-schedule-no-days-off">Belum ada tanggal libur untuk jadwal ini.</p>
                            @endif
                        </div>
                    </article>

                    <div class="modal fade court-member-day-off-modal" id="{{ $dayOffModalId }}" tabindex="-1"
                        aria-labelledby="{{ $dayOffModalId }}Title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST"
                                    action="{{ route('admin.court-bookings.member-schedules.days-off.store', $schedule) }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="{{ $dayOffModalId }}Title">Jadwalkan tanggal libur</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-3">
                                            {{ $schedule->member_name }} biasanya memakai lapangan {{ strtolower($dayLabel) }}.
                                            Pilih tanggal ketika jadwal rutin ini libur.
                                        </p>
                                        <label class="form-label" for="{{ $dayOffModalId }}Date">Tanggal libur</label>
                                        <input class="form-control" id="{{ $dayOffModalId }}Date" type="date" name="off_date"
                                            min="{{ now('Asia/Jakarta')->toDateString() }}" required>
                                        <p class="court-member-day-off-help">
                                            Tanggal harus jatuh pada hari {{ strtolower($dayLabel) }} dan masuk masa berlaku
                                            jadwal.
                                            Pada tanggal itu, slot kembali tersedia untuk booking umum.
                                        </p>
                                        <div class="mt-3">
                                            <label class="form-label" for="{{ $dayOffModalId }}Note">Catatan</label>
                                            <input class="form-control" id="{{ $dayOffModalId }}Note" type="text" name="note"
                                                maxlength="255" placeholder="Opsional">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-outline-secondary" type="button"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary" type="submit">Simpan tanggal libur</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="court-member-schedule-empty">Belum ada jadwal member. Tambahkan jadwal rutin agar slot khusus
                        tidak ditawarkan ke booking umum.</p>
                @endforelse
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        (() => {
            const formatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
            const fields = document.querySelectorAll('[data-rupiah-input]');
            const digitsOnly = (value) => value.replace(/\D/g, '');
            const format = (digits) => digits ? `Rp ${formatter.format(Number(digits))}` : '';
            const digitCountBefore = (value, position) => (value.slice(0, position).match(/\d/g) || []).length;
            const positionAfterDigit = (value, digitIndex) => {
                if (digitIndex === 0) return Math.min(3, value.length);

                let digitsSeen = 0;
                for (let index = 0; index < value.length; index += 1) {
                    if (/\d/.test(value[index]) && ++digitsSeen === digitIndex) return index + 1;
                }

                return value.length;
            };

            fields.forEach((field) => {
                field.addEventListener('input', () => {
                    const previousValue = field.value;
                    const caret = field.selectionStart ?? previousValue.length;
                    const digitIndex = digitCountBefore(previousValue, caret);
                    const formatted = format(digitsOnly(previousValue));

                    field.value = formatted;
                    const nextCaret = positionAfterDigit(formatted, digitIndex);
                    field.setSelectionRange(nextCaret, nextCaret);
                });
            });

            @if(session('open_add_court_modal'))
                const openAddCourtModal = () => {
                    const modal = document.getElementById('addCourtModal');
                    if (modal && window.bootstrap?.Modal) {
                        window.bootstrap.Modal.getOrCreateInstance(modal).show();
                    }
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', openAddCourtModal, { once: true });
                } else {
                    openAddCourtModal();
                }
            @endif
        })();
    </script>
@endpush
