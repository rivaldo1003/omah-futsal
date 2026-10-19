.admin-team-form-page {
    --team-ink: #111111;
    --team-charcoal: #39393b;
    --team-muted: #707072;
    --team-line: #cacacb;
    --team-line-soft: #e5e5e5;
    --team-cloud: #f5f5f5;
    --team-white: #ffffff;
    --team-success: #007d48;
    --team-warning: #8a4b00;
    --team-danger: #b42318;
    max-width: 1440px;
    margin: 0 auto;
    color: var(--team-ink);
    font-size: 15px;
    line-height: 1.5;
}

.admin-team-form-page .page-header {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px 24px;
    margin-bottom: 24px;
}

.admin-team-form-page .page-header h1 {
    margin: 0 0 8px;
    color: var(--team-ink);
    font-size: clamp(1.75rem, 2.5vw, 2rem);
    font-weight: 500;
    line-height: 1.2;
    letter-spacing: -.02em;
}

.admin-team-form-page .page-subtitle {
    max-width: 66ch;
    margin: 0;
    color: var(--team-muted);
    font-size: 15px;
}

.admin-team-form-page .form-card {
    overflow: hidden;
    border: 1px solid var(--team-line-soft);
    border-radius: 8px;
    background: var(--team-white);
    box-shadow: none;
}

.admin-team-form-page .form-card .card-header {
    padding: 18px 20px;
    border-bottom: 1px solid var(--team-line-soft);
    background: var(--team-white);
}

.admin-team-form-page .form-card .card-header h5 {
    margin: 0;
    color: var(--team-ink);
    font-size: 16px;
    font-weight: 500;
    line-height: 1.4;
}

.admin-team-form-page .form-card .card-body {
    padding: 20px;
}

.admin-team-form-page .form-section-title {
    margin: 28px 0 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--team-line-soft);
    color: var(--team-ink);
    font-size: 15px;
    font-weight: 500;
    line-height: 1.4;
}

.admin-team-form-page form > .form-section-title:first-of-type {
    margin-top: 0;
}

.admin-team-form-page .form-label {
    margin-bottom: 7px;
    color: var(--team-charcoal);
    font-size: 14px;
    font-weight: 500;
}

.admin-team-form-page .required {
    color: var(--team-danger);
}

.admin-team-form-page .x-small {
    font-size: 12px;
    line-height: 1.5;
}

.admin-team-form-page .text-secondary,
.admin-team-form-page .text-muted {
    color: var(--team-muted) !important;
}

.admin-team-form-page .form-control,
.admin-team-form-page .form-select {
    min-height: 44px;
    border: 1px solid var(--team-line);
    border-radius: 8px;
    background-color: var(--team-white);
    color: var(--team-ink);
    font: inherit;
    font-size: 14px;
    box-shadow: none;
}

.admin-team-form-page textarea.form-control {
    min-height: unset;
    resize: vertical;
}

.admin-team-form-page .form-control::placeholder {
    color: var(--team-muted);
    opacity: .85;
}

.admin-team-form-page .form-control:focus,
.admin-team-form-page .form-select:focus {
    border-color: var(--team-ink);
    box-shadow: 0 0 0 3px var(--team-cloud);
}

.admin-team-form-page .form-control.is-invalid:focus,
.admin-team-form-page .form-select.is-invalid:focus {
    border-color: var(--team-danger);
    box-shadow: 0 0 0 3px #fdf1f0;
}

.admin-team-form-page .form-control[type="file"] {
    min-height: 48px;
    padding: 5px;
}

.admin-team-form-page .form-control[type="file"]::file-selector-button {
    min-height: 36px;
    margin: 0 10px 0 0;
    padding: 7px 14px;
    border: 0;
    border-radius: 9999px;
    background: var(--team-cloud);
    color: var(--team-ink);
    font: inherit;
    font-size: 13px;
    cursor: pointer;
}

.admin-team-form-page .form-control-color {
    width: 56px;
    min-height: 44px;
    padding: 4px;
    border-radius: 8px 0 0 8px;
    cursor: pointer;
}

.admin-team-form-page .input-group > .form-control:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.admin-team-form-page .form-check-input {
    border-color: var(--team-line);
}

.admin-team-form-page .form-check-input:checked {
    border-color: var(--team-ink);
    background-color: var(--team-ink);
}

.admin-team-form-page .form-check-input:focus {
    border-color: var(--team-ink);
    box-shadow: 0 0 0 3px var(--team-cloud);
}

.admin-team-form-page .form-check-input:disabled + .form-check-label {
    color: var(--team-muted);
    cursor: not-allowed;
    opacity: .65;
}

.admin-team-form-page .form-check-label {
    width: 100%;
    color: var(--team-charcoal);
    font-size: 13px;
    cursor: pointer;
}

.admin-team-form-page .selection-box,
.admin-team-form-page .selection-surface {
    max-height: 240px;
    overflow: auto;
    padding: 8px;
    border: 1px solid var(--team-line-soft);
    border-radius: 8px;
    background: var(--team-cloud) !important;
    scrollbar-color: var(--team-line) transparent;
    scrollbar-width: thin;
}

.admin-team-form-page .selection-box .form-check,
.admin-team-form-page .selection-surface .form-check {
    display: flex;
    min-height: 38px;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 6px 8px;
    border-bottom: 1px solid var(--team-line-soft);
}

.admin-team-form-page .selection-box .form-check:last-child,
.admin-team-form-page .selection-surface .form-check:last-child {
    border-bottom: 0;
}

.admin-team-form-page .selection-box .form-check:hover,
.admin-team-form-page .selection-surface .form-check:hover {
    background: var(--team-white);
}

.admin-team-form-page .selection-box .form-check-input,
.admin-team-form-page .selection-surface .form-check-input {
    flex: 0 0 auto;
    margin: 0;
}

.admin-team-form-page .selection-box .form-check-label,
.admin-team-form-page .selection-surface .form-check-label {
    display: block;
    min-width: 0;
    margin: 0;
}

.admin-team-form-page .selection-tournament .form-check-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.admin-team-form-page .btn-back,
.admin-team-form-page .btn-submit,
.admin-team-form-page .team-form-actions .btn,
.admin-team-form-page .modal .btn {
    min-height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 22px;
    border: 1px solid transparent;
    border-radius: 9999px;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.4;
    text-decoration: none;
    white-space: nowrap;
    box-shadow: none;
    transition: background-color 150ms ease-out, border-color 150ms ease-out, color 150ms ease-out;
}

.admin-team-form-page .btn-back,
.admin-team-form-page .team-form-actions .btn-outline-secondary,
.admin-team-form-page .modal .btn-outline-secondary {
    background: var(--team-cloud);
    color: var(--team-ink);
}

.admin-team-form-page .btn-back:hover,
.admin-team-form-page .team-form-actions .btn-outline-secondary:hover,
.admin-team-form-page .modal .btn-outline-secondary:hover {
    background: #eeeeee;
    color: var(--team-ink);
}

.admin-team-form-page .btn-submit,
.admin-team-form-page .team-form-actions .btn-primary {
    background: var(--team-ink);
    color: var(--team-white);
}

.admin-team-form-page .btn-submit:hover,
.admin-team-form-page .team-form-actions .btn-primary:hover {
    background: var(--team-charcoal);
    color: var(--team-white);
}

.admin-team-form-page .btn-xs.btn-back {
    min-height: 36px;
    padding: 6px 13px;
    font-size: 12px;
}

.admin-team-form-page #clearLogoBtn {
    width: 48px;
    flex: 0 0 48px;
    padding: 0;
}

.admin-team-form-page #clearLogoBtn i {
    font-size: 18px;
}

.admin-team-form-page .team-form-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 28px !important;
    padding-top: 20px !important;
    border-top: 1px solid var(--team-line-soft) !important;
}

.admin-team-form-page .team-form-actions .btn-outline-danger,
.admin-team-form-page .modal .btn-danger {
    border-color: #e8c8c5;
    background: #fdf1f0;
    color: var(--team-danger);
}

.admin-team-form-page .team-form-actions .btn-outline-danger:hover,
.admin-team-form-page .modal .btn-danger:hover {
    border-color: var(--team-danger);
    background: var(--team-danger);
    color: var(--team-white);
}

.admin-team-form-page .team-form-actions .btn-sm,
.admin-team-form-page .modal .btn-sm {
    min-height: 40px;
    padding: 8px 16px;
    font-size: 13px;
}

.admin-team-form-page :is(a, button, input, select, textarea):focus-visible {
    outline: 2px solid var(--team-ink);
    outline-offset: 3px;
}

.admin-team-form-page #logoPreview,
.admin-team-form-page #logoPreviewCard {
    overflow: hidden;
    border: 1px solid var(--team-line-soft) !important;
    border-radius: 8px !important;
    background: var(--team-cloud) !important;
}

.admin-team-form-page #logoPreview img,
.admin-team-form-page #logoPreviewCard img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.admin-team-form-page #logoPreviewCard {
    min-height: 180px !important;
    padding: 20px;
}

.admin-team-form-page .team-logo-container {
    overflow: hidden;
    border: 1px solid var(--team-line-soft);
    border-radius: 50%;
    background: var(--team-cloud);
}

.admin-team-form-page .team-logo-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.admin-team-form-page .current-info-item {
    padding: 8px 10px;
    border: 1px solid var(--team-line-soft);
    border-radius: 8px;
}

.admin-team-form-page .current-info-item .label {
    color: var(--team-muted);
    font-size: 12px;
}

.admin-team-form-page .current-info-item .value {
    color: var(--team-ink);
    font-size: 14px;
    font-weight: 500;
    overflow-wrap: anywhere;
}

.admin-team-form-page .alert {
    border-radius: 8px;
    box-shadow: none;
}

.admin-team-form-page .alert-warning {
    border-color: #f0dfbd;
    background: #fff6e7;
    color: var(--team-warning);
}

.admin-team-form-page .modal-content {
    overflow: hidden;
    border: 1px solid var(--team-line-soft);
    border-radius: 8px;
    background: var(--team-white);
    box-shadow: none;
}

.admin-team-form-page .modal-header,
.admin-team-form-page .modal-footer {
    border-color: var(--team-line-soft);
}

.admin-team-form-page .modal-title {
    color: var(--team-ink);
    font-size: 18px;
    font-weight: 500;
}

.admin-team-form-page .modal-body {
    color: var(--team-charcoal);
}

@media (max-width: 767.98px) {
    .admin-team-form-page .form-card .card-header {
        padding: 16px;
    }

    .admin-team-form-page .form-card .card-body {
        padding: 16px;
    }

    .admin-team-form-page .team-form-actions {
        align-items: stretch;
    }

    .admin-team-form-page .team-form-actions > *,
    .admin-team-form-page .team-form-actions .d-flex,
    .admin-team-form-page .team-form-actions .btn {
        width: 100%;
    }

    .admin-team-form-page .team-form-actions .d-flex {
        flex-direction: column;
    }

    .admin-team-form-page .page-header .btn-back {
        width: 100%;
    }
}

@media (prefers-reduced-motion: reduce) {
    .admin-team-form-page * {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}
