<style>
    /* Knockout bracket */
    .standings-bracket-champion-banner {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: var(--space-3);
        margin-top: var(--space-4);
        margin-bottom: var(--space-4);
        padding: var(--space-3) var(--space-4);
        border: 1px solid var(--success);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--text-primary);
        animation: bracket-champion-arrive 760ms cubic-bezier(.2, .75, .25, 1) both;
    }

    .standings-bracket-champion-banner::before {
        position: absolute;
        z-index: 0;
        top: 0;
        bottom: 0;
        left: -28%;
        width: 18%;
        background: rgba(255, 255, 255, 0.08);
        content: '';
        opacity: 0;
        pointer-events: none;
        transform: skewX(-22deg);
        animation: bracket-champion-sweep 1.05s 420ms ease-out both;
    }

    .standings-bracket-champion-banner::after {
        position: absolute;
        z-index: 0;
        top: -10px;
        right: 18%;
        width: 5px;
        height: 9px;
        border-radius: 1px;
        background: var(--warning);
        box-shadow:
            -31px 18px 0 -1px var(--success),
            -17px 39px 0 -1px var(--warning),
            13px 8px 0 -1px var(--success),
            29px 27px 0 -1px var(--warning),
            44px 46px 0 -1px var(--success),
            61px 13px 0 -1px var(--warning),
            78px 34px 0 -1px var(--success);
        opacity: 0;
        pointer-events: none;
        content: '';
        animation: bracket-champion-confetti 1.7s 180ms ease-out both;
    }

    .standings-bracket-champion-emblem {
        position: relative;
        z-index: 1;
        display: grid;
        align-items: center;
        justify-items: center;
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        border: 1px solid var(--warning);
        border-radius: 50%;
        background: var(--warning-subtle);
        color: var(--warning);
        font-size: 18px;
    }

    .standings-bracket-champion-emblem>i {
        animation: bracket-champion-trophy 700ms 160ms cubic-bezier(.2, .75, .25, 1) both;
    }

    .standings-bracket-champion-emblem::before {
        position: absolute;
        inset: -5px;
        border: 1px solid var(--warning);
        border-radius: inherit;
        content: '';
        opacity: 0;
        pointer-events: none;
        animation: bracket-champion-ripple 900ms 400ms ease-out both;
    }

    .standings-bracket-champion-emblem::after {
        position: absolute;
        top: -5px;
        right: -3px;
        color: var(--warning);
        content: '✦';
        font-size: 11px;
        animation: bracket-champion-spark 1.15s 470ms ease-out both;
    }

    .standings-bracket-champion-logo {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        border-radius: 50%;
        border: 1px solid var(--border-color);
        background: var(--surface-hover);
        padding: 5px;
        animation: bracket-champion-logo 620ms 260ms cubic-bezier(.2, .75, .25, 1) both;
    }

    .standings-bracket-champion-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .standings-bracket-champion-copy {
        position: relative;
        z-index: 1;
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .standings-bracket-champion-label {
        color: var(--success);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .7px;
        line-height: 1.3;
        animation: bracket-champion-copy 460ms 390ms ease-out both;
    }

    .standings-bracket-champion-copy>strong {
        overflow: hidden;
        color: var(--text-primary);
        font-size: 19px;
        font-weight: 900;
        letter-spacing: -.25px;
        line-height: 1.2;
        text-overflow: ellipsis;
        white-space: nowrap;
        animation: bracket-champion-copy 520ms 500ms ease-out both;
    }

    @keyframes bracket-champion-arrive {
        0% {
            opacity: 0;
            clip-path: inset(0 100% 0 0);
            transform: translateY(10px) scale(.985);
        }

        68% {
            opacity: 1;
            clip-path: inset(0 0 0 0);
        }

        100% {
            transform: translateY(0) scale(1);
        }
    }

    @keyframes bracket-champion-sweep {
        0% {
            opacity: 0;
            transform: translateX(0) skewX(-22deg);
        }

        18% {
            opacity: .8;
        }

        100% {
            opacity: 0;
            transform: translateX(760%) skewX(-22deg);
        }
    }

    @keyframes bracket-champion-confetti {
        0% {
            opacity: 0;
            transform: translateY(-5px) rotate(0);
        }

        14% {
            opacity: .9;
        }

        100% {
            opacity: 0;
            transform: translateY(74px) rotate(110deg);
        }
    }

    @keyframes bracket-champion-trophy {
        0% {
            opacity: 0;
            transform: translateY(5px) rotate(-14deg) scale(.76);
        }

        72% {
            opacity: 1;
            transform: translateY(-1px) rotate(3deg) scale(1.04);
        }

        100% {
            transform: translateY(0) rotate(0) scale(1);
        }
    }

    @keyframes bracket-champion-ripple {
        0% {
            opacity: .7;
            transform: scale(.72);
        }

        100% {
            opacity: 0;
            transform: scale(1.4);
        }
    }

    @keyframes bracket-champion-logo {
        from {
            opacity: 0;
            transform: scale(.82) rotate(-8deg);
        }

        to {
            opacity: 1;
            transform: scale(1) rotate(0);
        }
    }

    @keyframes bracket-champion-copy {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bracket-champion-card {

        0%,
        100% {
            box-shadow: inset 3px 0 0 var(--success);
        }

        42% {
            box-shadow: inset 3px 0 0 var(--success), 0 0 0 4px var(--success-subtle);
        }
    }

    @keyframes bracket-champion-row {
        0% {
            background: transparent;
        }

        52% {
            background: var(--success-subtle);
        }

        100% {
            background: var(--success-subtle);
        }
    }

    @keyframes bracket-champion-spark {
        0% {
            opacity: 0;
            transform: scale(.4) rotate(-25deg);
        }

        45% {
            opacity: 1;
            transform: scale(1.1) rotate(12deg);
        }

        100% {
            opacity: .35;
            transform: scale(.8) rotate(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .standings-bracket-champion-banner,
        .standings-bracket-champion-banner::before,
        .standings-bracket-champion-banner::after,
        .standings-bracket-champion-emblem::before,
        .standings-bracket-champion-emblem>i,
        .standings-bracket-champion-emblem::after,
        .standings-bracket-champion-logo,
        .standings-bracket-champion-label,
        .standings-bracket-champion-copy>strong,
        .standings-bracket-match.is-champion-decided,
        .standings-bracket-team.is-champion {
            animation: none;
        }
    }

    .standings-bracket-scroller {
        margin-top: var(--space-3);
        overflow-x: auto;
        padding-bottom: var(--space-3);
        scroll-snap-type: x proximity;
        scrollbar-color: var(--border-color) var(--surface);
    }

    .standings-bracket-board {
        position: relative;
        isolation: isolate;
        display: flex;
        align-items: stretch;
        gap: var(--space-6, 24px);
        width: max-content;
        min-width: 100%;
    }

    .standings-bracket-connections {
        position: absolute;
        inset: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        overflow: visible;
        pointer-events: none;
    }

    .standings-bracket-connector-path {
        fill: none;
        stroke: var(--border-focus, var(--border-color));
        stroke-width: 1.75;
        stroke-linecap: round;
        stroke-linejoin: round;
        opacity: .55;
    }

    .standings-bracket-connector-junction {
        fill: var(--surface);
        stroke: var(--border-focus, var(--border-color));
        stroke-width: 1.5;
        opacity: .7;
    }

    .standings-bracket-round {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        flex: 0 0 260px;
        width: 260px;
        scroll-snap-align: start;
    }

    .standings-bracket-round-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-2);
        min-height: 42px;
        margin-bottom: var(--space-3);
        padding: var(--space-2) var(--space-3);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--surface);
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 700;
    }

    .standings-bracket-round-header small {
        color: var(--text-secondary);
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
    }

    .standings-bracket-round.is-final .standings-bracket-round-header {
        border-color: var(--accent);
    }

    .standings-bracket-matches {
        display: flex;
        flex: 1;
        flex-direction: column;
        justify-content: space-around;
        gap: var(--space-3);
    }

    .standings-bracket-match {
        position: relative;
        padding: var(--space-3);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--surface);
    }

    .standings-bracket-match.is-champion-decided {
        border-color: var(--success);
        box-shadow: inset 3px 0 0 var(--success);
        animation: bracket-champion-card 1s 1.05s ease-out both;
    }

    .standings-bracket-match-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-2);
        margin-bottom: var(--space-2);
        color: var(--text-secondary);
        font-size: 11px;
    }

    .standings-bracket-status {
        padding: 2px 7px;
        border-radius: 999px;
        background: var(--surface-hover);
        color: var(--text-secondary);
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
    }

    .standings-bracket-status.is-completed {
        background: var(--success-subtle);
        color: var(--success);
    }

    .standings-bracket-status.is-bye {
        background: var(--success-subtle);
        color: var(--success);
    }

    .standings-bracket-status.is-ongoing {
        background: var(--warning-subtle);
        color: var(--warning);
    }

    .standings-bracket-team {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-2);
        min-height: 30px;
        color: var(--text-primary);
        font-size: 13px;
    }

    .standings-bracket-identity {
        display: flex;
        align-items: center;
        flex: 1;
        gap: var(--space-2);
        min-width: 0;
    }

    .standings-bracket-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
        border-radius: 50%;
        color: var(--text-secondary);
        font-size: 13px;
    }

    .standings-bracket-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .standings-bracket-identity>span:last-child {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .standings-bracket-team+.standings-bracket-team {
        border-top: 1px solid var(--border-color);
    }

    .standings-bracket-team.is-winner {
        font-weight: 700;
    }

    .standings-bracket-team.is-champion {
        padding: 2px 5px;
        border-radius: 4px;
        background: var(--success-subtle);
        color: var(--success);
        animation: bracket-champion-row 900ms 1.28s ease-out both;
    }

    .standings-bracket-score {
        min-width: 20px;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
        font-weight: 700;
        text-align: right;
    }

    .standings-bracket-placement {
        flex: 0 0 auto;
        padding: 2px 5px;
        border-radius: 4px;
        background: var(--success-subtle);
        color: var(--success);
        font-size: 10px;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
    }

    .standings-bracket-placement.is-champion {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 7px;
        border-radius: 999px;
        letter-spacing: .2px;
    }

    .standings-bracket-extras {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--space-3);
        margin-top: var(--space-2);
        padding-top: var(--space-2);
        border-top: 1px solid var(--border-color);
    }

    .standings-bracket-extra {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: var(--text-secondary);
        font-size: 11px;
    }

    .standings-bracket-extra strong {
        color: var(--text-primary);
        font-size: 11px;
        font-variant-numeric: tabular-nums;
    }

    .standings-bracket-extra.is-penalty {
        color: var(--accent);
        font-weight: 600;
    }

    .standings-bracket-extra.is-penalty strong {
        color: var(--text-primary);
    }

    .standings-bracket-empty {
        min-height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-3);
        border: 1px dashed var(--border-color);
        border-radius: var(--radius-sm);
        color: var(--text-secondary);
        font-size: 12px;
        text-align: center;
    }

    @media (max-width: 576px) {
        .standings-bracket-champion-banner {
            gap: var(--space-2);
            padding: var(--space-2) var(--space-3);
        }

        .standings-bracket-champion-emblem {
            width: 38px;
            height: 38px;
            flex-basis: 38px;
            font-size: 15px;
        }

        .standings-bracket-champion-logo {
            width: 42px;
            height: 42px;
            flex-basis: 42px;
        }

        .standings-bracket-champion-copy>strong {
            font-size: 15px;
        }

        .standings-bracket-round {
            flex-basis: 230px;
            width: 230px;
        }
    }
</style>
