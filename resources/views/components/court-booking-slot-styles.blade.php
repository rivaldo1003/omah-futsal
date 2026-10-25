<style>
    .court-slot-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
    }

    .booking-page .court-slot-grid {
        padding: 16px 20px 20px;
    }

    .walk-in-admin-page .court-slot-grid {
        padding: 0;
        --v3-card: #f5f5f5;
        --border-color: #e5e5e5;
        --text-primary: #111111;
        --text-secondary: #39393b;
        --text-muted: #707072;
        --success: #007d48;
        --success-subtle: #e8f3ed;
        --v3-neon-green: #007d48;
        --radius-md: 8px;
    }

    .slot-button {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        min-width: 0;
        min-height: 72px;
        padding: 11px 13px;
        border: 1px solid var(--border-color, #e5e5e5);
        border-radius: var(--radius-md, 8px);
        background: var(--v3-card, #f5f5f5);
        color: var(--text-primary, #111111);
        text-align: left;
        transition: border-color 150ms ease, background-color 150ms ease;
    }

    .slot-button .slot-pitch-markings {
        position: absolute;
        z-index: 0;
        inset: 7px;
        display: block;
        margin: 0;
        opacity: .13;
        pointer-events: none;
    }

    .slot-pitch-boundary {
        position: absolute;
        inset: 0;
        border: 1px solid currentColor;
        border-radius: 4px;
    }

    .slot-pitch-midline {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        border-left: 1px solid currentColor;
    }

    .slot-pitch-center-circle {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 25px;
        aspect-ratio: 1;
        border: 1px solid currentColor;
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }

    .slot-pitch-penalty-area {
        position: absolute;
        top: 22%;
        bottom: 22%;
        width: 11%;
        border: 1px solid currentColor;
    }

    .slot-pitch-penalty-area--left {
        left: 0;
        border-left: 0;
    }

    .slot-pitch-penalty-area--right {
        right: 0;
        border-right: 0;
    }

    .slot-button > strong,
    .slot-button > span:not(.slot-pitch-markings) {
        position: relative;
        z-index: 1;
    }

    .slot-button > strong {
        display: block;
        color: inherit;
        font-size: 14px;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    .slot-button > span:not(.slot-pitch-markings) {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 4px;
        color: var(--text-secondary, #39393b);
        font-size: 12px;
        line-height: 1.4;
    }

    .slot-button .slot-status-copy {
        display: inline;
        margin: 0;
        color: inherit;
        font: inherit;
    }

    .slot-button .slot-occupant-copy {
        display: inline-block;
        min-width: 0;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 650;
    }

    .slot-button.available {
        border-color: #9bbca8;
        background: var(--success-subtle, #e8f3ed);
        color: var(--success, #007d48);
        cursor: pointer;
    }

    .slot-button.available:hover:not(.is-past-available) {
        border-color: var(--success, #007d48);
        background: #dceee3;
    }

    .slot-button.available:focus-visible,
    .slot-button--inspect:focus-visible {
        outline: 2px solid var(--v3-neon-green, #007d48);
        outline-offset: 2px;
    }

    .slot-button.available .slot-pitch-markings {
        opacity: .18;
    }

    .slot-button.available[aria-pressed="true"],
    .slot-button.available.is-selected {
        border-color: var(--success, #007d48);
        background: #dceee3;
        box-shadow: inset 0 0 0 1px var(--success, #007d48);
    }

    .slot-button.available[aria-pressed="true"] .slot-pitch-markings,
    .slot-button.available.is-selected .slot-pitch-markings {
        opacity: .22;
    }

    .slot-button.available.is-past-available {
        border-color: #a6a6a8;
        background: #eeeeee;
        color: #555557;
    }

    .slot-button.available.is-past-available > span:not(.slot-pitch-markings) {
        color: var(--text-muted, #707072);
    }

    .slot-button.available.is-past-available:hover,
    .slot-button.available.is-past-available.is-selected {
        border-color: var(--success, #007d48);
        background: #e8f3ed;
        color: var(--success, #007d48);
    }

    .slot-button.available.is-instant:not(.is-selected) {
        border-color: var(--success, #007d48);
        background: #f0f8f4;
    }

    .slot-button.pending {
        border-color: #e7d7ad;
        background: #f7f1e3;
        color: #805600;
        cursor: not-allowed;
    }

    .slot-button.pending > span:not(.slot-pitch-markings) {
        color: #805600;
    }

    .slot-button.booked {
        border-color: #c7d1dc;
        background: #edf1f5;
        color: #344254;
        cursor: not-allowed;
    }

    .slot-button.booked > span:not(.slot-pitch-markings) {
        color: #5c6b7b;
    }

    .slot-button.match {
        border-color: #a9c9ea;
        background: #eaf3ff;
        color: #174a7d;
        cursor: not-allowed;
    }

    .slot-button.match > span:not(.slot-pitch-markings) {
        color: #315e89;
    }

    .slot-button .slot-status-badge--match {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 2px 7px;
        border: 1px solid #b6d4f2;
        border-radius: 4px;
        background: #dcecff;
        color: #164f84;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.4;
    }

    .slot-button.tournament_rental {
        border-color: #c9b9e7;
        background: #f1edfa;
        color: #4c3b79;
        cursor: not-allowed;
    }

    .slot-button.tournament_rental > span:not(.slot-pitch-markings) {
        color: #5e4b8c;
    }

    .slot-button .slot-status-badge--tournament-rental {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 2px 7px;
        border: 1px solid #d6caec;
        border-radius: 4px;
        background: #e9e1f7;
        color: #4c3b79;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.4;
    }

    .slot-button.past,
    .slot-button.is-past {
        border-style: dashed;
        border-color: #a6a6a8;
        background: #eeeeee;
        color: #555557;
        cursor: not-allowed;
    }

    .slot-button.past > span:not(.slot-pitch-markings),
    .slot-button.is-past > span:not(.slot-pitch-markings) {
        color: var(--text-muted, #707072);
    }

    .slot-button.member {
        border-color: #c9bee9;
        background: #f0edfa;
        color: #493b73;
        cursor: not-allowed;
    }

    .slot-button.member > span:not(.slot-pitch-markings) {
        color: #5d4b8d;
    }

    .slot-button.unavailable {
        border-color: #d4d4d4;
        background: #eeeeee;
        color: #555557;
        cursor: not-allowed;
    }

    .slot-button--inspect {
        width: 100%;
        cursor: pointer !important;
        text-align: left;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }

    .slot-button--inspect:hover {
        border-color: currentColor;
        box-shadow: 0 2px 8px rgb(21 31 44 / 10%);
    }

    .slot-button--inspect > span:not(.slot-pitch-markings) {
        justify-content: space-between;
    }

    .slot-button:disabled {
        opacity: 1;
    }

    .slot-inspect-icon {
        flex: 0 0 auto;
        margin-left: auto;
        opacity: .72;
    }

    @media (max-width: 767.98px) {
        .court-slot-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .booking-page .court-slot-grid {
            padding: 13px;
        }

        .walk-in-admin-page .court-slot-grid {
            padding: 0;
        }

        .slot-button {
            min-height: 72px;
            padding: 10px 12px;
        }
    }
</style>
