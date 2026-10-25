@extends('layouts.admin')

@section('title', 'Jadwal Member')

@php
    $memberWeekdays = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
@endphp

@push('styles')
    <style>
        .court-member-schedules-page {
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

        .court-member-schedules-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px 24px;
            margin-bottom: 28px;
        }

        .court-member-schedules-header h1 {
            margin: 0 0 8px;
            color: var(--settings-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .court-member-schedules-header p {
            max-width: 68ch;
            margin: 0;
            color: var(--settings-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .court-member-schedules-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .court-member-schedules-page .btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 9999px;
            box-shadow: none;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            transition: background-color 150ms ease-out, color 150ms ease-out, border-color 150ms ease-out;
        }

        .court-member-schedules-page .btn-primary {
            border-color: var(--settings-ink);
            background: var(--settings-ink);
            color: #ffffff;
        }

        .court-member-schedules-page .btn-primary:hover,
        .court-member-schedules-page .btn-primary:active {
            border-color: var(--settings-charcoal);
            background: var(--settings-charcoal);
            color: #ffffff;
        }

        .court-member-schedules-page .btn-outline-primary,
        .court-member-schedules-page .btn-outline-secondary,
        .court-member-schedules-page .btn-secondary {
            border-color: transparent;
            background: var(--settings-cloud);
            color: var(--settings-ink);
        }

        .court-member-schedules-page .btn-outline-primary:hover,
        .court-member-schedules-page .btn-outline-secondary:hover,
        .court-member-schedules-page .btn-secondary:hover {
            border-color: transparent;
            background: #e5e5e5;
            color: var(--settings-ink);
        }

        .court-member-schedules-page :is(a, button, summary):focus-visible {
            outline: 2px solid var(--settings-ink);
            outline-offset: 3px;
        }

        .court-member-schedules-page .form-label {
            margin-bottom: 7px;
            color: var(--settings-ink);
            font-size: 14px;
            font-weight: 500;
        }

        .court-member-schedules-page .form-control,
        .court-member-schedules-page .form-select {
            min-height: 46px;
            border: 1px solid var(--settings-line);
            border-radius: 24px;
            background-color: var(--settings-canvas);
            color: var(--settings-ink);
            font-size: 16px;
            line-height: 1.4;
        }

        .court-member-schedules-page .form-control:focus,
        .court-member-schedules-page .form-select:focus {
            border: 2px solid var(--settings-ink);
            box-shadow: none;
            outline: none;
        }

        .court-member-schedules-page .form-check-input:checked {
            border-color: var(--settings-ink);
            background-color: var(--settings-ink);
        }

        .court-member-schedules-page .form-check-input:focus {
            border-color: var(--settings-ink);
            box-shadow: 0 0 0 3px var(--settings-cloud);
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

        .court-settings-alert--success { color: var(--settings-success); }
        .court-settings-alert--error { color: var(--settings-danger); }
        .court-settings-alert ul { margin: 0; padding-left: 18px; }

        .court-member-schedules-page .court-settings-section {
            min-width: 0;
            padding: 24px;
            border: 1px solid var(--settings-line-soft);
            border-radius: 8px;
            background: var(--settings-canvas);
        }

        .court-settings-section-heading { margin-bottom: 22px; }
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

        .court-member-schedule-field-help {
            display: block;
            margin-top: 6px;
            color: var(--settings-muted);
            font-size: 12px;
            line-height: 1.45;
        }

        .court-member-name-picker {
            display: grid;
            gap: 8px;
        }

        .court-member-name-picker [hidden] {
            display: none !important;
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
            display: grid;
            gap: 12px;
        }

        .court-member-schedule-row {
            padding: 18px;
            border: 1px solid var(--settings-line-soft);
            border-radius: 8px;
        }

        .court-member-schedule-group-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 4px;
        }

        .court-member-schedule-group-heading h4 {
            margin: 0;
            color: var(--settings-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .court-member-schedule-group-summary {
            margin: 4px 0 0;
            color: var(--settings-muted);
            font-size: 13px;
        }

        .court-member-schedule-count {
            flex: 0 0 auto;
            padding: 3px 9px;
            border: 1px solid var(--settings-line-soft);
            border-radius: 4px;
            color: var(--settings-charcoal);
            font-size: 12px;
            font-weight: 500;
        }

        .court-member-schedule-item {
            margin-top: 12px;
            overflow: hidden;
            border: 1px solid var(--settings-line-soft);
            border-radius: 6px;
            background: var(--settings-canvas);
        }

        .court-member-schedule-item > summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-height: 76px;
            padding: 14px 16px;
            list-style: none;
            cursor: pointer;
            transition: background-color 140ms ease;
        }

        .court-member-schedule-item > summary::-webkit-details-marker {
            display: none;
        }

        .court-member-schedule-item > summary:hover {
            background: #fafafa;
        }

        .court-member-schedule-item > summary:focus-visible {
            outline: 2px solid var(--settings-ink);
            outline-offset: -3px;
        }

        .court-member-schedule-item[open] > summary {
            border-bottom: 1px solid var(--settings-line-soft);
            background: #fafafa;
        }

        .court-member-schedule-summary-copy {
            min-width: 0;
        }

        .court-member-schedule-summary-meta {
            margin: 4px 0 0;
            color: var(--settings-muted);
            font-size: 13px;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .court-member-schedule-disclosure {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            gap: 8px;
            color: var(--settings-muted);
            font-size: 12px;
            font-weight: 500;
        }

        .court-member-schedule-disclosure-open {
            display: none;
        }

        .court-member-schedule-item[open] .court-member-schedule-disclosure-closed {
            display: none;
        }

        .court-member-schedule-item[open] .court-member-schedule-disclosure-open {
            display: inline;
        }

        .court-member-schedule-disclosure i {
            transition: transform 140ms ease;
        }

        .court-member-schedule-item[open] .court-member-schedule-disclosure i {
            transform: rotate(180deg);
        }

        .court-member-schedule-details {
            padding: 16px;
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

        .court-member-schedule-identity h5 {
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
            border: 1px solid #78c99b;
            background: #e6f6ed;
            color: #0e6841;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .court-member-schedule-status--paused {
            border-color: #e8bd58;
            background: #fff3d6;
            color: #785000;
        }

        .court-member-schedule-row-controls {
            display: flex;
            flex: 0 0 auto;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .court-member-schedule-row-controls form {
            margin: 0;
        }

        .court-member-schedule-detail-heading {
            margin: 0;
            color: var(--settings-ink);
            font-size: 13px;
            font-weight: 600;
        }

        .court-member-schedule-note {
            margin: 14px 0 0;
            color: var(--settings-charcoal);
            font-size: 13px;
            line-height: 1.5;
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

        .court-member-day-off-modal .modal-content,
        .court-member-edit-modal .modal-content {
            border: 1px solid var(--settings-line-soft);
            border-radius: 8px;
            box-shadow: 0 12px 36px rgb(17 17 17 / 14%);
        }

        .court-member-day-off-modal .modal-header,
        .court-member-day-off-modal .modal-footer,
        .court-member-edit-modal .modal-header,
        .court-member-edit-modal .modal-footer {
            padding: 18px 22px;
            border-color: var(--settings-line-soft);
        }

        .court-member-day-off-modal .modal-body,
        .court-member-edit-modal .modal-body {
            padding: 22px;
        }

        /* The form wraps the modal sections, so make it the scrollable flex column. */
        .court-member-edit-modal .modal-content {
            max-height: calc(100vh - 2rem);
            max-height: calc(100dvh - 2rem);
            overflow: hidden;
        }

        .court-member-edit-modal form {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }

        .court-member-edit-modal .modal-header,
        .court-member-edit-modal .modal-footer {
            flex: 0 0 auto;
        }

        .court-member-edit-modal .modal-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        .court-member-day-off-modal .modal-title,
        .court-member-edit-modal .modal-title {
            color: var(--settings-ink);
            font-size: 18px;
            font-weight: 600;
        }


        .court-member-day-off-help {
            margin: 12px 0 0;
            color: var(--settings-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        @media (max-width: 1100px) {
            .court-member-schedule-fields--four { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .court-member-schedule-note-field { max-width: 100%; }
        }

        @media (max-width: 640px) {
            .court-member-schedules-header { gap: 18px; margin-bottom: 24px; }
            .court-member-schedules-actions { width: 100%; }
            .court-member-schedules-actions .btn { flex: 1 1 auto; }
            .court-member-schedules-page .court-settings-section { padding: 18px; }
            .court-settings-section-heading h2 { font-size: 20px; }
            .court-member-schedule-fields--three,
            .court-member-schedule-fields--four,
            .court-member-schedule-facts { grid-template-columns: minmax(0, 1fr); }
            .court-member-schedule-row-header { flex-direction: column; gap: 14px; }
            .court-member-schedule-item > summary { align-items: flex-start; padding: 14px; }
            .court-member-schedule-disclosure { gap: 5px; padding-top: 3px; }
            .court-member-schedule-details { padding: 14px; }
            .court-member-schedule-row-controls { width: 100%; }
            .court-member-schedule-row-controls .btn { flex: 1 1 auto; }
            .court-member-schedule-row-controls form { display: flex; flex: 1 1 0; }
            .court-member-schedule-row-controls form .btn { width: 100%; }
            .court-member-schedule-exceptions-heading { align-items: flex-start; flex-direction: column; }
            .court-member-schedule-exceptions-heading .btn { width: 100%; }
            .court-member-schedule-create { padding: 16px; }
            .court-member-day-off-modal .modal-body,
            .court-member-day-off-modal .modal-header,
            .court-member-day-off-modal .modal-footer,
            .court-member-edit-modal .modal-body,
            .court-member-edit-modal .modal-header,
            .court-member-edit-modal .modal-footer { padding: 16px; }
            .court-member-edit-modal .modal-footer .btn,
            .court-member-day-off-modal .modal-footer .btn { flex: 1 1 auto; }
        }

        @media (prefers-reduced-motion: reduce) {
            .court-member-schedules-page .btn,
            .court-member-schedule-item > summary,
            .court-member-schedule-disclosure i { transition: none; }
        }
    </style>
@endpush

@section('content')
    <main class="court-member-schedules-page">
        <header class="court-member-schedules-header">
            <div>
                <h1>Jadwal Member</h1>
                <p>Kelola slot rutin member dan grup, termasuk perubahan jadwal, jeda, serta tanggal libur.</p>
            </div>
            <nav class="court-member-schedules-actions" aria-label="Navigasi booking lapangan">
                <a href="{{ route('admin.court-bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-calendar-check" aria-hidden="true"></i>
                    Booking lapangan
                </a>
                <a href="{{ route('admin.court-bookings.settings') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-sliders" aria-hidden="true"></i>
                    Pengaturan booking
                </a>
            </nav>
        </header>

        @if(session('success'))
            <div class="court-settings-alert court-settings-alert--success" role="status" aria-live="polite">
                <i class="bi bi-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->getBag('default')->any())
            <div class="court-settings-alert court-settings-alert--error" role="alert">
                <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                <ul>
                    @foreach($errors->getBag('default')->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="court-settings-section" aria-labelledby="memberScheduleHeading">
            <div class="court-settings-section-heading">
                <h2 id="memberScheduleHeading">Jadwal terdaftar</h2>
                <p>Setiap member atau grup ditampilkan dalam satu daftar. Jadwal dapat dijeda dan tanggal liburnya dapat diatur kapan saja.</p>
            </div>

            @php
                $createMemberNameValue = session('open_member_schedule_modal') ? null : old('member_name');
                $createKnownMemberName = filled($createMemberNameValue)
                    ? $memberNames->first(fn ($name) => mb_strtolower($name) === mb_strtolower(trim((string) $createMemberNameValue)))
                    : null;
                $createUsesNewMemberName = $createMemberNameValue !== null
                    ? $createKnownMemberName === null
                    : $memberNames->isEmpty();
            @endphp

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
                        <div class="court-member-name-picker" data-member-name-picker>
                            <select class="form-select" id="memberScheduleName" data-member-name-select
                                @if(!$createUsesNewMemberName) name="member_name" required @endif
                                aria-describedby="memberScheduleNameHelp">
                                <option value="" disabled @selected(!$createUsesNewMemberName && $createKnownMemberName === null)>Pilih nama member / grup</option>
                                @foreach($memberNames as $memberName)
                                    <option value="{{ $memberName }}" @selected($createKnownMemberName === $memberName)>{{ $memberName }}</option>
                                @endforeach
                                <option value="__new__" @selected($createUsesNewMemberName)>Tambah nama baru</option>
                            </select>
                            <input class="form-control" data-member-name-input maxlength="120"
                                @if($createUsesNewMemberName) name="member_name" required @else hidden disabled @endif
                                value="{{ $createUsesNewMemberName ? old('member_name') : '' }}"
                                placeholder="Tulis nama member / grup baru" autocomplete="off">
                            <input type="hidden" name="member_name_mode" data-member-name-mode
                                value="{{ $createUsesNewMemberName ? 'new' : 'existing' }}">
                        </div>
                        <small class="court-member-schedule-field-help" id="memberScheduleNameHelp">
                            Pilih nama yang sudah terdaftar untuk menambah hari atau jam lain. Nama baru hanya perlu dimasukkan sekali.
                        </small>
                        @error('member_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                <small class="court-member-schedule-field-help mb-3">
                    Masa berlaku menentukan periode jadwal rutin. Pemakaian pada tanggal tertentu tetap dicatat terpisah melalui <strong>Catat booking member</strong>.
                </small>

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
                <p>{{ $memberScheduleGroups->count() }} member / grup · {{ $memberSchedules->count() }} jadwal</p>
            </div>

            <div class="court-member-schedule-list">
                @forelse($memberScheduleGroups as $schedulesForMember)
                    @php
                        $groupMemberName = $schedulesForMember->first()->member_name;
                        $groupActiveCount = $schedulesForMember->where('is_active', true)->count();
                        $groupScheduleCount = $schedulesForMember->count();
                        $memberGroupId = 'memberScheduleGroup' . $loop->index;
                    @endphp
                    <article class="court-member-schedule-row" aria-labelledby="{{ $memberGroupId }}">
                        <header class="court-member-schedule-group-heading">
                            <div>
                                <h4 id="{{ $memberGroupId }}">{{ $groupMemberName }}</h4>
                                <p class="court-member-schedule-group-summary">
                                    {{ $groupActiveCount }} aktif · {{ $groupScheduleCount - $groupActiveCount }} dijeda
                                </p>
                            </div>
                            <span class="court-member-schedule-count">{{ $groupScheduleCount }} jadwal</span>
                        </header>

                        @foreach($schedulesForMember as $schedule)
                        @php
                            $dayLabel = $memberWeekdays[$schedule->day_of_week] ?? 'Hari tidak valid';
                            $dayOffModalId = 'memberDayOffModal' . $schedule->id;
                            $editScheduleModalId = 'editMemberScheduleModal' . $schedule->id;
                            $editScheduleErrors = $errors->getBag('memberSchedule' . $schedule->id);
                            $reopenThisEditModal = (int) session('open_member_schedule_modal') === (int) $schedule->id;
                            $editScheduleOld = fn($key, $default = null) => $reopenThisEditModal ? old($key, $default) : $default;
                            $editMemberNameValue = $editScheduleOld('member_name', $schedule->member_name);
                            $editKnownMemberName = $memberNames->first(fn ($name) => mb_strtolower($name) === mb_strtolower(trim((string) $editMemberNameValue)));
                            $editUsesNewMemberName = $reopenThisEditModal && $editMemberNameValue !== null && $editKnownMemberName === null;
                        @endphp
                        <details class="court-member-schedule-item" @if($groupScheduleCount === 1) open @endif>
                            <summary>
                                <div class="court-member-schedule-summary-copy">
                                    <div class="court-member-schedule-identity-line">
                                        <h5>{{ $dayLabel }} · {{ substr($schedule->time_start, 0, 5) }}–{{ substr($schedule->time_end, 0, 5) }}</h5>
                                        <span
                                            class="court-member-schedule-status {{ $schedule->is_active ? '' : 'court-member-schedule-status--paused' }}">
                                            {{ $schedule->is_active ? 'Aktif' : 'Dijeda' }}
                                        </span>
                                    </div>
                                    <p class="court-member-schedule-summary-meta">{{ $schedule->court->name }}</p>
                                </div>
                                <span class="court-member-schedule-disclosure" aria-hidden="true">
                                    <span class="court-member-schedule-disclosure-closed">Lihat detail</span>
                                    <span class="court-member-schedule-disclosure-open">Tutup detail</span>
                                    <i class="bi bi-chevron-down"></i>
                                </span>
                            </summary>

                            <div class="court-member-schedule-details">
                                <div class="court-member-schedule-row-header">
                                    <p class="court-member-schedule-detail-heading">Pengaturan jadwal</p>
                                    <div class="court-member-schedule-row-controls">
                                        <a class="btn btn-primary"
                                            href="{{ route('admin.court-bookings.member-schedules.booking.create', $schedule) }}">
                                            <i class="bi bi-cash-coin" aria-hidden="true"></i>
                                            Catat booking member
                                        </a>
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#{{ $editScheduleModalId }}">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                            Ubah jadwal
                                        </button>
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
                                        <span>Lapangan</span>
                                        <strong>{{ $schedule->court->name }}</strong>
                                    </div>
                                    <div class="court-member-schedule-fact">
                                        <span>Masa berlaku</span>
                                        <strong>{{ $schedule->starts_on?->format('d M Y') ?? 'Tanpa batas awal' }} sampai
                                            {{ $schedule->ends_on?->format('d M Y') ?? 'Tanpa batas akhir' }}</strong>
                                    </div>
                                </div>

                                @if($schedule->notes)
                                    <p class="court-member-schedule-note"><strong>Catatan:</strong> {{ $schedule->notes }}</p>
                                @endif

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
                            </div>
                        </details>

                        <div class="modal fade court-member-edit-modal" id="{{ $editScheduleModalId }}" tabindex="-1"
                            aria-labelledby="{{ $editScheduleModalId }}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <form method="POST"
                                        action="{{ route('admin.court-bookings.member-schedules.update', $schedule) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="is_active" value="0">
                                        <div class="modal-header">
                                            <div>
                                                <h3 class="modal-title" id="{{ $editScheduleModalId }}Title">Ubah jadwal member</h3>
                                                <p class="mb-0 mt-1 text-secondary small">Nama member yang sama dapat digunakan di jadwal lain.</p>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($editScheduleErrors->any())
                                                <div class="court-settings-alert court-settings-alert--error mb-3" role="alert">
                                                    <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                                                    <ul>
                                                        @foreach($editScheduleErrors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <div class="court-member-schedule-fields court-member-schedule-fields--three mb-3">
                                                <div>
                                                    <label class="form-label" for="{{ $editScheduleModalId }}Court">Lapangan</label>
                                                    <select class="form-select" id="{{ $editScheduleModalId }}Court" name="court_id" required>
                                                        @foreach($courts as $court)
                                                            <option value="{{ $court->id }}" @selected((string) $editScheduleOld('court_id', $schedule->court_id) === (string) $court->id)>{{ $court->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="form-label" for="{{ $editScheduleModalId }}Name">Nama member / grup</label>
                                                    <div class="court-member-name-picker" data-member-name-picker>
                                                        <select class="form-select" id="{{ $editScheduleModalId }}Name" data-member-name-select
                                                            @if(!$editUsesNewMemberName) name="member_name" required @endif>
                                                            @foreach($memberNames as $memberName)
                                                                <option value="{{ $memberName }}" @selected(!$editUsesNewMemberName && $editKnownMemberName === $memberName)>{{ $memberName }}</option>
                                                            @endforeach
                                                            <option value="__new__" @selected($editUsesNewMemberName)>Tambah nama baru</option>
                                                        </select>
                                                        <input class="form-control" data-member-name-input maxlength="120"
                                                            @if($editUsesNewMemberName) name="member_name" required @else hidden disabled @endif
                                                            value="{{ $editUsesNewMemberName ? $editMemberNameValue : '' }}"
                                                            placeholder="Tulis nama member / grup baru" autocomplete="off">
                                                        <input type="hidden" name="member_name_mode" data-member-name-mode
                                                            value="{{ $editUsesNewMemberName ? 'new' : 'existing' }}">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="form-label" for="{{ $editScheduleModalId }}Day">Hari rutin</label>
                                                    <select class="form-select" id="{{ $editScheduleModalId }}Day" name="day_of_week" required>
                                                        @foreach($memberWeekdays as $day => $label)
                                                            <option value="{{ $day }}" @selected((string) $editScheduleOld('day_of_week', $schedule->day_of_week) === (string) $day)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="court-member-schedule-fields court-member-schedule-fields--four mb-3">
                                                <div>
                                                    <label class="form-label" for="{{ $editScheduleModalId }}Start">Jam mulai</label>
                                                    <input class="form-control" id="{{ $editScheduleModalId }}Start" name="time_start" type="time"
                                                        value="{{ $editScheduleOld('time_start', substr($schedule->time_start, 0, 5)) }}" required>
                                                </div>
                                                <div>
                                                    <label class="form-label" for="{{ $editScheduleModalId }}End">Jam selesai</label>
                                                    <input class="form-control" id="{{ $editScheduleModalId }}End" name="time_end" type="time"
                                                        value="{{ $editScheduleOld('time_end', substr($schedule->time_end, 0, 5)) }}" required>
                                                </div>
                                                <div>
                                                    <label class="form-label" for="{{ $editScheduleModalId }}StartsOn">Berlaku mulai</label>
                                                    <input class="form-control" id="{{ $editScheduleModalId }}StartsOn" name="starts_on" type="date"
                                                        value="{{ $editScheduleOld('starts_on', $schedule->starts_on?->toDateString()) }}">
                                                </div>
                                                <div>
                                                    <label class="form-label" for="{{ $editScheduleModalId }}EndsOn">Berlaku sampai</label>
                                                    <input class="form-control" id="{{ $editScheduleModalId }}EndsOn" name="ends_on" type="date"
                                                        value="{{ $editScheduleOld('ends_on', $schedule->ends_on?->toDateString()) }}">
                                                </div>
                                            </div>
                                            <small class="court-member-schedule-field-help mb-3">
                                                Masa berlaku menentukan periode jadwal rutin. Pemakaian pada tanggal tertentu tetap dicatat terpisah melalui <strong>Catat booking member</strong>.
                                            </small>

                                            <div class="mb-3">
                                                <label class="form-label" for="{{ $editScheduleModalId }}Notes">Catatan</label>
                                                <input class="form-control" id="{{ $editScheduleModalId }}Notes" name="notes" maxlength="500"
                                                    value="{{ $editScheduleOld('notes', $schedule->notes) }}" placeholder="Opsional">
                                            </div>
                                            <label class="form-check d-flex align-items-center gap-2 mb-0">
                                                <input class="form-check-input mt-0" type="checkbox" name="is_active" value="1"
                                                    @checked((bool) $editScheduleOld('is_active', $schedule->is_active))>
                                                <span class="form-check-label">Jadwal aktif dan menahan slot dari booking umum</span>
                                            </label>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-primary" type="submit">Simpan perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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
                        @endforeach
                    </article>
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
            document.querySelectorAll('[data-member-name-picker]').forEach((picker) => {
                const select = picker.querySelector('[data-member-name-select]');
                const input = picker.querySelector('[data-member-name-input]');
                const mode = picker.querySelector('[data-member-name-mode]');

                if (!select || !input || !mode) return;

                const syncMemberNameControl = () => {
                    const usesNewName = select.value === '__new__';
                    mode.value = usesNewName ? 'new' : 'existing';
                    select.required = !usesNewName;
                    if (usesNewName) {
                        select.removeAttribute('name');
                    } else {
                        select.name = 'member_name';
                    }
                    input.required = usesNewName;
                    if (usesNewName) {
                        input.name = 'member_name';
                    } else {
                        input.removeAttribute('name');
                    }
                    input.disabled = !usesNewName;
                    input.hidden = !usesNewName;
                };

                select.addEventListener('change', syncMemberNameControl);
                syncMemberNameControl();
            });

            @if(session('open_member_schedule_modal'))
                const openMemberScheduleModal = () => {
                    const modal = document.getElementById(@js('editMemberScheduleModal' . session('open_member_schedule_modal')));
                    if (modal && window.bootstrap?.Modal) {
                        window.bootstrap.Modal.getOrCreateInstance(modal).show();
                    }
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', openMemberScheduleModal, { once: true });
                } else {
                    openMemberScheduleModal();
                }
            @endif
        })();
    </script>
@endpush
