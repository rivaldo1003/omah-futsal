<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>OFS Futsal Center - Home</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #1a5fb4;
            --secondary-color: #5e5c64;
            --success-color: #26a269;
            --danger-color: #c01c28;
            --warning-color: #f5c211;
            --info-color: #1c71d8;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
            --accent-color: #3b82f6;
            --nav-bg: rgba(255, 255, 255, 0.98);
            --nav-shadow: rgba(15, 23, 42, 0.1);
            --card-shadow: rgba(0, 0, 0, 0.05);
        }

        /* Reset & Base */
        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--light-color);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            color: #334155;
            line-height: 1.6;
            overflow-x: hidden;
            font-size: 15px;
        }

        /* Container Fixes */
        .container {
            padding-left: max(15px, env(safe-area-inset-left));
            padding-right: max(15px, env(safe-area-inset-right));
        }

        /* Navigation - Fully Responsive */
        .navbar {
            background: var(--nav-bg);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px var(--nav-shadow);
            padding: 0.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0;
            margin-right: 0;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            width: 35px;
            height: 35px;
            object-fit: contain;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-main {
            font-size: 1.1rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-sub {
            font-size: 0.65rem;
            color: #64748b;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Mobile First Nav */
        .navbar-toggler {
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 0.4rem 0.6rem;
            font-size: 0.9rem;
        }

        .navbar-collapse {
            margin-top: 0.5rem;
            border-radius: 8px;
            background: var(--nav-bg);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .nav-link {
            color: #475569 !important;
            font-weight: 600;
            padding: 0.7rem 1rem !important;
            border-radius: 6px;
            margin: 2px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(96, 165, 250, 0.08));
            color: var(--accent-color) !important;
        }

        /* Mobile Optimized Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn-sm {
            padding: 0.35rem 0.7rem;
            font-size: 0.85rem;
        }

        .btn-login-nav,
        .admin-badge,
        .btn-logout-nav {
            width: 100%;
            margin: 5px 0;
            justify-content: center;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            padding: 2rem 0;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .tournament-title {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.8rem;
            background: linear-gradient(135deg, #ffffff, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            line-height: 1.3;
        }

        .hero-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
            max-width: 100%;
            margin: 0 auto 1.5rem;
            text-align: center;
            color: #cbd5e1;
            padding: 0 10px;
        }

        /* Cards - Mobile First */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 1.25rem;
            box-shadow: 0 3px 8px var(--card-shadow);
            background: white;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem;
            font-weight: 700;
            color: #334155;
            font-size: 1rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-header i {
            margin-right: 8px;
            color: var(--accent-color);
            font-size: 1.1rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Match Cards - Mobile Optimized */
        .match-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .match-time {
            font-weight: 600;
            color: var(--accent-color);
            font-size: 0.95rem;
            margin-bottom: 0.2rem;
        }

        .match-venue {
            font-size: 0.8rem;
            color: #64748b;
        }

        .team-name {
            font-weight: 600;
            color: #334155;
            font-size: 0.95rem;
            margin-bottom: 3px;
            line-height: 1.2;
        }

        .team-group {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.1;
        }

        /* Score Section - Mobile Friendly */
        .score-badge {
            background: linear-gradient(135deg, var(--accent-color), #1c71d8);
            color: white;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.2rem;
            min-width: 70px;
            text-align: center;
            display: inline-block;
        }

        .score-badge.live {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }

            100% {
                opacity: 1;
            }
        }

        .match-status {
            font-size: 0.8rem;
            color: #64748b;
            text-align: center;
            margin-top: 4px;
            display: block;
        }

        /* Match Layout - Stack on Mobile */
        .teams-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.8rem;
            margin: 1rem 0;
        }

        .team-container {
            width: 100%;
            text-align: center;
        }

        .score-container {
            order: -1;
            margin-bottom: 0.5rem;
        }

        /* Match Events - Mobile Scroll */
        .match-events {
            background: #f8fafc;
            border-radius: 6px;
            padding: 0.7rem;
            border: 1px solid #e2e8f0;
            margin-top: 0.7rem;
            font-size: 0.8rem;
            max-height: 200px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .match-events .col-6 {
            padding: 0 5px;
        }

        /* Tables - Mobile Scroll */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
            overflow-x: auto;
            margin-bottom: 1rem;
        }

        .table {
            width: 100%;
            min-width: 300px;
            font-size: 0.85rem;
        }

        .table th,
        .table td {
            padding: 0.5rem 0.3rem;
            white-space: nowrap;
        }

        /* Group Standings - Mobile Grid */
        .group-standings {
            margin-bottom: 1.5rem;
        }

        .standing-row {
            padding: 0.4rem 0;
        }

        /* Ranking Badge - Mobile */
        .rank-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: start;
            justify-content: start;
            font-weight: 700;
            font-size: 0.9rem;
            margin-right: 8px;
            flex-shrink: 0;
        }

        /*.rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: #333; }*/
        /*.rank-2 { background: linear-gradient(135deg, #C0C0C0, #A9A9A9); color: white; }*/
        /*.rank-3 { background: linear-gradient(135deg, #CD7F32, #A0522D); color: white; }*/
        /*.rank-other { background: linear-gradient(135deg, #64748b, #475569); color: white; }*/

        .group-title {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.8rem;
            padding-bottom: 0.4rem;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Player Cards - Mobile Compact */
        .player-card {
            display: flex;
            align-items: center;
            padding: 0.8rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 0.7rem;
            gap: 0.8rem;
        }

        .player-info {
            flex: 1;
            min-width: 0;
        }

        .player-info h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #334155;
        }

        .player-team {
            font-size: 0.8rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .goals-count {
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .cards-count {
            display: flex;
            gap: 6px;
            margin-top: 3px;
            flex-shrink: 0;
            font-size: 0.75rem;
        }

        /* Upcoming Matches - Mobile Layout */
        .upcoming-match-item {
            padding: 0.8rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 0.8rem;
            background: white;
        }

        .upcoming-match-teams {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .team-with-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 120px;
        }

        .team-logo-small {
            width: 28px;
            height: 28px;
            border-radius: 5px;
            object-fit: cover;
        }

        /* Recent Results - Mobile */
        .recent-result-item {
            padding: 0.8rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 0.8rem;
            background: white;
        }

        .goal-scorers {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e2e8f0;
            font-size: 0.8rem;
        }

        .goal-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.2rem;
            font-size: 0.75rem;
        }

        .goal-minute {
            background-color: rgba(38, 162, 105, 0.1);
            color: #26a269;
            border-radius: 3px;
            padding: 0.1rem 0.25rem;
            margin-right: 0.25rem;
            font-size: 0.7rem;
            border: 1px solid rgba(38, 162, 105, 0.2);
        }

        /* Footer - Mobile */
        .footer {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            padding: 2rem 0 1.2rem;
            margin-top: 2.5rem;
            font-size: 0.9rem;
        }

        .footer-title {
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: #e2e8f0;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            padding: 0.2rem 0;
        }

        .footer-contact {
            color: #cbd5e1;
            font-size: 0.85rem;
        }

        .social-icons {
            display: flex;
            gap: 10px;
            margin-top: 1rem;
        }

        .social-icons a {
            color: #cbd5e1;
            font-size: 1.2rem;
            transition: color 0.2s;
        }

        /* Hero Section Styles - PERBAIKAN */
        .hero-section {
            padding: 80px 0;
            position: relative;
            min-height: 400px;
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            text-align: center;
        }

        .tournament-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
            text-align: center;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto 30px;
            text-align: center;
            color: #cbd5e1;
            opacity: 0.9;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes float-particle {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100vh) translateX(20px);
                opacity: 0;
            }
        }

        .hero-cta-button {
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .hero-cta-button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15) !important;
            text-decoration: none !important;
        }

        .hero-cta-button:active {
            transform: translateY(0) !important;
        }

        /* Hapus atau komen CSS hero section yang konflik di bagian lain */
        /*
.hero-section {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: white;
    padding: 2rem 0;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.tournament-title {
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 0.8rem;
    background: linear-gradient(135deg, #ffffff, #60a5fa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-align: center;
    line-height: 1.3;
}

.hero-subtitle {
    font-size: 0.95rem;
    opacity: 0.9;
    max-width: 100%;
    margin: 0 auto 1.5rem;
    text-align: center;
    color: #cbd5e1;
    padding: 0 10px;
}
*/

        /* Responsive styles */
        @media (max-width: 992px) {
            .hero-section {
                padding: 70px 0;
                min-height: 350px;
            }

            .tournament-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
                max-width: 600px;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
                min-height: 300px;
                text-align: center;
            }

            .tournament-title {
                font-size: 2rem;
                margin-bottom: 15px;
            }

            .hero-subtitle {
                font-size: 1rem;
                margin-bottom: 20px;
            }

            .hero-cta-button {
                padding: 10px 25px !important;
                font-size: 1rem !important;
            }
        }

        /* Pastikan container hero-section memiliki display flex yang benar */
        .hero-section .container {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
        }

        /* Pastikan row dan col di tengah */
        .hero-section .row.justify-content-center {
            justify-content: center !important;
        }

        .hero-section .text-center {
            text-align: center !important;
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 50px 0;
                min-height: 250px;
            }

            .tournament-title {
                font-size: 1.8rem;
                margin-bottom: 12px;
            }

            .hero-subtitle {
                font-size: 0.95rem;
                margin-bottom: 15px;
            }



            .hero-cta-button {
                padding: 8px 20px !important;
                font-size: 0.95rem !important;
            }
        }

        .copyright {
            color: #94a3b8;
            font-size: 0.8rem;
            padding-top: 1.2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        /* Badges - Mobile */
        .badge {
            border-radius: 4px;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.6;
        }

        /* Responsive Breakpoints */
        @media (min-width: 576px) {
            body {
                font-size: 15.5px;
            }

            .tournament-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .card-header {
                padding: 1.1rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .teams-container {
                flex-direction: row;
                gap: 1rem;
            }

            .team-container {
                width: auto;
                flex: 1;
            }

            .team-home {
                text-align: right;
            }

            .team-away {
                text-align: left;
            }

            .score-container {
                order: 0;
                margin-bottom: 0;
            }

            .table th,
            .table td {
                padding: 0.6rem 0.4rem;
            }
        }

        @media (min-width: 768px) {
            body {
                font-size: 16px;
            }

            .navbar-collapse {
                margin-top: 0;
                background: transparent;
                box-shadow: none;
            }

            .nav-link {
                padding: 0.6rem 1rem !important;
                margin: 0 3px;
                width: auto;
            }

            .btn-login-nav,
            .admin-badge,
            .btn-logout-nav {
                width: auto;
                margin: 0;
            }

            .hero-section {
                padding: 3rem 0 2.5rem;
            }

            .tournament-title {
                font-size: 2.2rem;
            }

            .brand-main {
                font-size: 1.3rem;
            }

            .brand-logo {
                width: 40px;
                height: 40px;
            }

            .teams-container {
                gap: 1.5rem;
            }

            .score-badge {
                min-width: 90px;
                padding: 0.7rem 1.2rem;
                font-size: 1.4rem;
            }

            .table {
                font-size: 0.9rem;
            }
        }

        /* ========================================= */
        /* CSS BARU UNTUK TOP SCORERS - AWAL */
        /* ========================================= */

        /* Rank Badge khusus untuk Top Scorers */
        .top-scorer-rank-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* Warna untuk ranking khusus top scorers */
        .top-scorer-rank-1 {
            background: linear-gradient(135deg, #FFD700, #FFA500) !important;
            color: #333 !important;
        }

        .top-scorer-rank-2 {
            background: linear-gradient(135deg, #C0C0C0, #A9A9A9) !important;
            color: white !important;
        }

        .top-scorer-rank-3 {
            background: linear-gradient(135deg, #CD7F32, #A0522D) !important;
            color: white !important;
        }

        .top-scorer-rank-4 {
            background: linear-gradient(135deg, #64748b, #475569) !important;
            color: white !important;
        }

        /* Player card untuk top scorers */
        .top-scorer-player-card {
            display: flex;
            align-items: center;
            padding: 0.8rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            transition: all 0.2s;
        }

        .top-scorer-player-card:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .top-scorer-player-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .top-scorer-player-info h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.9rem;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .top-scorer-player-team {
            font-size: 0.75rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        /* Goals count untuk top scorers */
        .top-scorer-goals-count {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.85rem;
            min-width: 60px;
            text-align: center;
            display: inline-block;
        }

        /* Cards count untuk top scorers */
        .top-scorer-cards-count {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
            font-size: 0.7rem;
            margin-top: 0.25rem;
        }

        /* Responsive untuk top scorers */
        @media (max-width: 576px) {
            .top-scorer-player-card {
                padding: 0.6rem;
            }

            .top-scorer-rank-badge {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
                margin-right: 8px;
            }

            .top-scorer-player-info h6 {
                font-size: 0.85rem;
            }

            .top-scorer-player-team {
                font-size: 0.7rem;
            }

            .top-scorer-goals-count {
                font-size: 0.8rem;
                min-width: 55px;
                padding: 0.25rem 0.5rem;
            }
        }

        /* ========================================= */
        /* CSS BARU UNTUK TOP SCORERS - AKHIR */
        /* ========================================= */

        /* Perbaikan untuk player card */
        .player-card {
            display: flex;
            align-items: center;
            padding: 0.8rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 0.7rem;
            transition: all 0.2s;
        }

        .player-card:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .player-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .player-info h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.9rem;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .player-team {
            font-size: 0.75rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        /* Rank badge style */
        .rank-badge {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        /* .rank-1 {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #333;
        }

        .rank-2 {
            background: linear-gradient(135deg, #C0C0C0, #A9A9A9);
            color: white;
        }

        .rank-3 {
            background: linear-gradient(135deg, #CD7F32, #A0522D);
            color: white;
        }

        .rank-4 {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
        } */

        /* Goals count */
        .goals-count {
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.75rem;
            min-width: 60px;
            text-align: center;
            display: inline-block;
        }

        /* Cards count */
        .cards-count {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
            font-size: 0.7rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .player-card {
                padding: 0.6rem;
                flex-wrap: nowrap;
            }

            .rank-badge {
                width: 24px;
                height: 24px;
                font-size: 0.7rem;
                margin-right: 0.5rem;
            }

            .player-info h6 {
                font-size: 0.85rem;
            }

            .player-team {
                font-size: 0.7rem;
            }

            .goals-count {
                font-size: 0.7rem;
                min-width: 50px;
                padding: 0.2rem 0.4rem;
            }

            .cards-count {
                font-size: 0.65rem;
            }
        }

        /* Responsive styles untuk Group Standings di Home */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 0.4rem 0.25rem;
                font-size: 0.8rem;
            }

            .table th:first-child,
            .table td:first-child {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .team-logo-container {
                width: 28px !important;
                height: 28px !important;
                margin-right: 0.5rem !important;
            }

            .rank-badge {
                width: 28px !important;
                height: 28px !important;
                font-size: 0.8rem !important;
            }
        }

        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.75rem;
            }

            .table th,
            .table td {
                padding: 0.35rem 0.2rem;
                font-size: 0.75rem;
            }

            .col-md-6 {
                padding-left: 8px;
                padding-right: 8px;
            }

            .group-title {
                font-size: 0.9rem;
            }

            /* Pastikan semua kolom tetap terlihat di mobile */
            .table th:nth-child(1),
            .table td:nth-child(1) {
                width: 40px;
            }

            /* # */
            .table th:nth-child(2),
            .table td:nth-child(2) {
                min-width: 120px;
            }

            /* Team */
            .table th:nth-child(3),
            .table td:nth-child(3) {
                width: 30px;
            }

            /* P */
            .table th:nth-child(4),
            .table td:nth-child(4) {
                width: 30px;
            }

            /* W */
            .table th:nth-child(5),
            .table td:nth-child(5) {
                width: 30px;
            }

            /* D */
            .table th:nth-child(6),
            .table td:nth-child(6) {
                width: 30px;
            }

            /* L */
            .table th:nth-child(7),
            .table td:nth-child(7) {
                width: 40px;
            }

            /* GD */
            .table th:nth-child(8),
            .table td:nth-child(8) {
                width: 40px;
            }

            /* PTS */
        }

        @media (min-width: 992px) {
            .tournament-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
                max-width: 600px;
            }

            .brand-main {
                font-size: 1.4rem;
            }

            .card-header {
                font-size: 1.1rem;
            }

            .match-card {
                padding: 1.5rem;
            }

            .team-name {
                font-size: 1rem;
            }

            .score-badge {
                min-width: 100px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
                padding-left: 12px;
                padding-right: 12px;
            }
        }

        /* Touch-friendly elements */
        @media (hover: none) and (pointer: coarse) {

            .btn,
            .nav-link,
            .card {
                min-height: 44px;
            }

            .btn-sm {
                min-height: 36px;
            }

            .table th,
            .table td {
                padding: 0.6rem 0.4rem;
            }

            .match-events {
                max-height: 250px;
            }
        }



        /* Safe Area for Notches */


        /* Print Styles */
        @media print {

            .navbar,
            .footer,
            .btn {
                display: none !important;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <div class="brand-container">
                    <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="brand-logo">
                    <div class="brand-text">
                        <div class="brand-main">OFS FUTSAL</div>
                        <div class="brand-sub">Arena Center</div>
                    </div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('tournaments*') ? 'active' : '' }}"
                                    href="{{ route('tournaments.index') }}">
                                    <i class="bi bi-trophy"></i> Tournaments
                                </a>
                            </li>
                        @endif
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('schedule*') || request()->is('matches*') ? 'active' : '' }}"
                            href="{{ route('schedule') }}">
                            <i class="bi bi-calendar2-week"></i> Schedule
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('standings*') ? 'active' : '' }}"
                            href="{{ route('standings') }}">
                            <i class="bi bi-bar-chart-line"></i> Standings
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('teams*') ? 'active' : '' }}"
                                    href="{{ route('teams.index') }}">
                                    <i class="bi bi-people-fill"></i> Teams
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link admin-badge" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Admin Panel
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link btn-logout-nav p-0 border-0">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-login-nav" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    @if($heroSetting->is_active)
        <div class="hero-section" style="
                                                        @if($heroSetting->background_type === 'gradient')
                                                            background: linear-gradient(135deg, {{ $heroSetting->gradient_start ?? '#0f172a' }}, {{ $heroSetting->gradient_end ?? '#1e293b' }});
                                                        @elseif($heroSetting->background_type === 'color' && $heroSetting->background_color)
                                                            background-color: {{ $heroSetting->background_color }};
                                                        @elseif($heroSetting->background_type === 'image' && $heroSetting->background_image)
                                                            background-image: url('{{ Storage::url($heroSetting->background_image) }}');
                                                            background-size: cover;
                                                            background-position: center;
                                                            @if($heroSetting->overlay_opacity > 0)
                                                                position: relative;
                                                            @endif
                                                        @else
                                                            background: linear-gradient(135deg, #0f172a, #1e293b);
                                                        @endif
                                                        color: {{ $heroSetting->text_color }};
                                                        position: relative;
                                                        overflow: hidden;
                                                    ">
            @if($heroSetting->background_type === 'image' && $heroSetting->background_image && $heroSetting->overlay_opacity > 0)
                <!-- Overlay untuk image background -->
                <div class="hero-overlay" style="
                                                                                                                position: absolute;
                                                                                                                top: 0;
                                                                                                                left: 0;
                                                                                                                width: 100%;
                                                                                                                height: 100%;
                                                                                                                background-color: rgba(0, 0, 0, {{ $heroSetting->overlay_opacity / 100 }});
                                                                                                                z-index: 1;
                                                                                                            "></div>
            @endif

            <div class="container" style="position: relative; z-index: 2;">
                <div class="row align-items-center justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="tournament-title" style="
                                                                        color: {{ $heroSetting->text_color }};
                                                                        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
                                                                    ">
                            <i class="bi bi-trophy-fill"></i> {{ $heroSetting->title }}
                        </h1>
                        <p class="hero-subtitle" style="
                                                                        color: {{ $heroSetting->text_color }};
                                                                        opacity: 0.9;
                                                                        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
                                                                        margin-left: auto;
                                                                        margin-right: auto;
                                                                    ">
                            {{ $heroSetting->subtitle }}
                        </p>

                        @if($heroSetting->cta_button_text)
                            <a href="{{ $heroSetting->cta_button_link ?? '#' }}" class="btn btn-lg hero-cta-button mx-auto"
                                style="
                                                                                                                                background-color: {{ $heroSetting->button_color ?? '#3b82f6' }};
                                                                                                                                color: {{ $heroSetting->button_text_color ?? '#ffffff' }};
                                                                                                                                border: none;
                                                                                                                                padding: 12px 30px;
                                                                                                                                border-radius: 8px;
                                                                                                                                font-weight: 600;
                                                                                                                                text-decoration: none;
                                                                                                                                display: inline-block;
                                                                                                                                margin-top: 20px;
                                                                                                                                transition: all 0.3s ease;
                                                                                                                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                                                                                                            "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.15)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';">
                                <i class="bi bi-arrow-right-circle me-2"></i>
                                {{ $heroSetting->cta_button_text }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Animated elements -->
            <div class="hero-particles" style="
                                                            position: absolute;
                                                            top: 0;
                                                            left: 0;
                                                            width: 100%;
                                                            height: 100%;
                                                            pointer-events: none;
                                                            z-index: 1;
                                                            opacity: 0.3;
                                                        ">
                @for($i = 1; $i <= 15; $i++)
                    <div class="particle" style="
                                                                                                                    position: absolute;
                                                                                                                    width: {{ rand(2, 5) }}px;
                                                                                                                    height: {{ rand(2, 5) }}px;
                                                                                                                    background-color: {{ $heroSetting->text_color }};
                                                                                                                    border-radius: 50%;
                                                                                                                    top: {{ rand(0, 100) }}%;
                                                                                                                    left: {{ rand(0, 100) }}%;
                                                                                                                    animation: float-particle {{ rand(5, 15) }}s linear infinite;
                                                                                                                "></div>
                @endfor
            </div>
        </div>
    @else
        <!-- Hero section hidden message (optional) -->
        <div class="container mt-4">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>
                Hero section is currently disabled.
                <a href="{{ route('admin.hero-settings.edit') }}" class="alert-link">
                    Enable it from admin settings
                </a>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="container main-container">
        <!-- Today's Matches -->
        @if($todayMatches->count() > 0)
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-day"></i> Today's Matches
                    <span class="badge bg-primary ms-2">{{ $todayMatches->count() }}</span>
                </div>
                <div class="card-body">
                    @foreach($todayMatches as $match)
                        <div class="match-card">
                            <div class="row align-items-center">
                                <!-- Time & Venue - Mobile Full Width -->
                                <div class="col-12 col-md-3 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <div class="match-time">{{ date('H:i', strtotime($match->time_start)) }}</div>
                                        <div class="match-venue">{{ $match->venue ?? 'Main Field' }}</div>
                                    </div>
                                </div>

                                <!-- Teams & Score - Responsive Layout -->
                                <div class="col-12 col-md-7">
                                    <div class="teams-container">
                                        <!-- Home Team -->
                                        <div class="team-container team-home">
                                            <div class="team-name">{{ $match->homeTeam->name ?? 'TBA' }}</div>
                                            <div class="team-group">
                                                {{ $match->group_name ? 'Group ' . $match->group_name : ucfirst(str_replace('_', ' ', $match->round_type ?? '')) }}
                                            </div>
                                        </div>

                                        <!-- Score -->
                                        <div class="score-container">
                                            @if($match->status == 'completed')
                                                <div class="score-badge">{{ $match->home_score ?? 0 }} -
                                                    {{ $match->away_score ?? 0 }}
                                                </div>
                                                <span class="match-status">FT</span>
                                            @elseif($match->status == 'ongoing')
                                                <div class="score-badge live">{{ $match->home_score ?? 0 }} -
                                                    {{ $match->away_score ?? 0 }}
                                                </div>
                                                <span class="match-status text-danger">LIVE</span>
                                            @else
                                                <div class="score-badge bg-secondary">VS</div>
                                                <span class="match-status">Upcoming</span>
                                            @endif
                                        </div>

                                        <!-- Away Team -->
                                        <div class="team-container team-away">
                                            <div class="team-name">{{ $match->awayTeam->name ?? 'TBA' }}</div>
                                            <div class="team-group">{{ ucfirst($match->status ?? 'upcoming') }}</div>
                                        </div>
                                    </div>

                                    <!-- Match Events -->
                                    @if(isset($match->events) && $match->events->count() > 0 && ($match->status == 'completed' || $match->status == 'ongoing'))
                                        <div class="match-events">
                                            <div class="row small">
                                                <div class="col-6">
                                                    @foreach($match->events->where('team_id', $match->team_home_id) as $event)
                                                        <div class="mb-1">
                                                            @if($event->event_type == 'goal')
                                                                <span class="badge bg-success me-1">
                                                                    <i class="bi bi-soccer"></i> {{ $event->minute }}'
                                                                </span>
                                                                <small>
                                                                    {{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}
                                                                    @if($event->is_penalty)
                                                                        <span class="text-muted">(P)</span>
                                                                    @endif
                                                                    @if($event->is_own_goal)
                                                                        <span class="text-danger">(OG)</span>
                                                                    @endif
                                                                </small>
                                                            @elseif($event->event_type == 'yellow_card')
                                                                <span class="badge bg-warning me-1">
                                                                    <i class="bi bi-card-text"></i> {{ $event->minute }}'
                                                                </span>
                                                                <small>{{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}</small>
                                                            @elseif($event->event_type == 'red_card')
                                                                <span class="badge bg-danger me-1">
                                                                    <i class="bi bi-card-text"></i> {{ $event->minute }}'
                                                                </span>
                                                                <small>{{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}</small>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-6">
                                                    @foreach($match->events->where('team_id', $match->team_away_id) as $event)
                                                        <div class="mb-1">
                                                            @if($event->event_type == 'goal')
                                                                <span class="badge bg-success me-1">
                                                                    <i class="bi bi-soccer"></i> {{ $event->minute }}'
                                                                </span>
                                                                <small>
                                                                    {{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}
                                                                    @if($event->is_penalty)
                                                                        <span class="text-muted">(P)</span>
                                                                    @endif
                                                                    @if($event->is_own_goal)
                                                                        <span class="text-danger">(OG)</span>
                                                                    @endif
                                                                </small>
                                                            @elseif($event->event_type == 'yellow_card')
                                                                <span class="badge bg-warning me-1">
                                                                    <i class="bi bi-card-text"></i> {{ $event->minute }}'
                                                                </span>
                                                                <small>{{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}</small>
                                                            @elseif($event->event_type == 'red_card')
                                                                <span class="badge bg-danger me-1">
                                                                    <i class="bi bi-card-text"></i> {{ $event->minute }}'
                                                                </span>
                                                                <small>{{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}</small>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Button -->
                                <div class="col-12 col-md-2 text-center text-md-end mt-3 mt-md-0">
                                    <a href="{{ route('matches.show', $match->id) }}"
                                        class="btn btn-primary btn-sm w-100 w-md-auto">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Upcoming Matches -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-calendar-week"></i> Upcoming Matchesss
                    </div>
                    <div class="card-body">
                        @if($upcomingMatches->count() > 0)
                            @foreach($upcomingMatches as $match)
                                <div class="upcoming-match-item">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-dark">{{ date('d M', strtotime($match->match_date)) }}</span>
                                            <span class="text-muted">{{ date('H:i', strtotime($match->time_start)) }}</span>
                                        </div>
                                        <span class="badge bg-warning text-dark">Upcoming</span>
                                    </div>

                                    <div class="upcoming-match-teams">
                                        <!-- Home Team -->
                                        <div class="team-with-logo">
                                            <div class="team-logo-container-small">
                                                @php
                                                    $homeTeam = $match->homeTeam;
                                                    $homeLogo = $homeTeam->logo ?? null;
                                                    $homeName = $homeTeam->name ?? 'TBA';
                                                @endphp
                                                @if($homeLogo && Storage::disk('public')->exists($homeLogo))
                                                    <img src="{{ asset('storage/' . $homeLogo) }}" alt="{{ $homeName }}"
                                                        class="team-logo-small">
                                                @else
                                                    <div class="team-logo-small d-flex align-items-center justify-content-center bg-light"
                                                        style="width: 28px; height: 28px; border-radius: 5px;">
                                                        <span
                                                            class="fw-bold text-dark">{{ strtoupper(substr($homeName, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-truncate">
                                                <strong>{{ $homeName }}</strong>
                                            </div>
                                        </div>

                                        <!-- VS Badge -->
                                        <div class="text-center px-2">
                                            <span class="badge bg-secondary">VS</span>
                                        </div>

                                        <!-- Away Team -->
                                        <div class="team-with-logo justify-content-end">
                                            <div class="text-truncate text-end me-2">
                                                <strong>{{ $match->awayTeam->name ?? 'TBA' }}</strong>
                                            </div>
                                            <div class="team-logo-container-small">
                                                @php
                                                    $awayTeam = $match->awayTeam;
                                                    $awayLogo = $awayTeam->logo ?? null;
                                                    $awayName = $awayTeam->name ?? 'TBA';
                                                @endphp
                                                @if($awayLogo && Storage::disk('public')->exists($awayLogo))
                                                    <img src="{{ asset('storage/' . $awayLogo) }}" alt="{{ $awayName }}"
                                                        class="team-logo-small">
                                                @else
                                                    <div class="team-logo-small d-flex align-items-center justify-content-center bg-light"
                                                        style="width: 28px; height: 28px; border-radius: 5px;">
                                                        <span
                                                            class="fw-bold text-dark">{{ strtoupper(substr($awayName, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Match Details -->
                                    <div class="small text-muted mt-2">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <i class="bi bi-geo-alt"></i>
                                            <span>{{ $match->venue ?? 'Main Field' }}</span>
                                            @if($match->group_name)
                                                <i class="bi bi-people ms-2"></i>
                                                <span>Group {{ $match->group_name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="text-center mt-4">
                                <a href="{{ route('schedule') }}" class="btn btn-primary">
                                    <i class="bi bi-calendar4-week"></i> View Full Schedule
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <p class="mt-2">No upcoming matches scheduled</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Group Standings -->
                <!-- Group Standings -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bar-chart-line"></i> Group Standing
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(isset($standings) && count($standings) > 0)
                                @foreach($standings as $group => $groupStandings)
                                    <div class="col-12 col-md-6 mb-4">
                                        <h6 class="group-title">GROUP {{ $group }}</h6>
                                        <div class="table-responsive">
                                            <!-- GANTI TABEL INI -->
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 40px;">#</th>
                                                        <th>Team</th>
                                                        <th class="text-center">P</th>
                                                        <th class="text-center">W</th>
                                                        <th class="text-center">D</th>
                                                        <th class="text-center">L</th>
                                                        <th class="text-center">GD</th>
                                                        <th class="text-center">PTS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($groupStandings as $index => $standing)
                                                        @php
                                                            $team = $standing->team ?? null;
                                                            $teamName = $team->name ?? $standing->team_name ?? $standing->name ?? 'Unknown Team';
                                                            $teamLogo = $team->logo ?? null;
                                                            $hasPlayed = isset($standing->matches_played) && $standing->matches_played > 0;

                                                            // Abbreviation for logo
                                                            $teamAbbr = '';
                                                            if (!empty($teamName)) {
                                                                $words = explode(' ', $teamName);
                                                                if (count($words) >= 2) {
                                                                    $teamAbbr = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                                                } else {
                                                                    $teamAbbr = strtoupper(substr($teamName, 0, 2));
                                                                }
                                                            }

                                                            // Goal difference
                                                            $gdValue = $standing->goal_difference ?? 0;
                                                            $gdDisplay = $gdValue > 0 ? '+' . $gdValue : $gdValue;
                                                        @endphp
                                                        <tr
                                                            class="standing-row {{ $index < 2 && $hasPlayed ? 'table-success' : '' }}">
                                                            <td class="text-center align-middle">
                                                                <div class="rank-badge rank-{{ min($index + 1, 4) }}">
                                                                    {{ $index + 1 }}
                                                                </div>
                                                            </td>
                                                            <td class="align-middle">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="team-logo-container"
                                                                        style="
                                                                                                                                                                                                width: 32px;
                                                                                                                                                                                                height: 32px;
                                                                                                                                                                                                background: white;
                                                                                                                                                                                                border-radius: 5px;
                                                                                                                                                                                                display: flex;
                                                                                                                                                                                                align-items: center;
                                                                                                                                                                                                justify-content: center;
                                                                                                                                                                                                overflow: hidden;
                                                                                                                                                                                                margin-right: 0.6rem;
                                                                                                                                                                                                flex-shrink: 0;
                                                                                                                                                                                                border: 1px solid #e2e8f0;
                                                                                                                                                                                            ">
                                                                        @php
                                                                            $logoExists = false;

                                                                            if ($teamLogo) {
                                                                                if (filter_var($teamLogo, FILTER_VALIDATE_URL)) {
                                                                                    $logoExists = true;
                                                                                } elseif (Storage::disk('public')->exists($teamLogo)) {
                                                                                    $logoExists = true;
                                                                                }
                                                                            }
                                                                        @endphp

                                                                        @if($logoExists)
                                                                            <img src="{{ filter_var($teamLogo, FILTER_VALIDATE_URL) ? $teamLogo : asset('storage/' . $teamLogo) }}"
                                                                                alt="{{ $teamName }}"
                                                                                style="width: 100%; height: 100%; object-fit: cover;">
                                                                        @else
                                                                            <span
                                                                                style="font-weight: bold; color: #333; font-size: 0.8rem;">
                                                                                {{ $teamAbbr }}
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="text-truncate">
                                                                        <strong class="d-block text-truncate"
                                                                            style="font-size: 0.85rem;">
                                                                            {{ Str::limit($teamName, 15) }}
                                                                        </strong>
                                                                        @if($index < 2 && $hasPlayed)
                                                                            <small class="badge bg-success"
                                                                                style="font-size: 0.6rem; padding: 1px 4px;">Q</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle fw-bold" style="font-size: 0.9rem;">
                                                                {{ $standing->matches_played ?? 0 }}
                                                            </td>
                                                            <td class="text-center align-middle fw-bold text-success"
                                                                style="font-size: 0.9rem;">
                                                                {{ $standing->wins ?? 0 }}
                                                            </td>
                                                            <td class="text-center align-middle" style="font-size: 0.9rem;">
                                                                {{ $standing->draws ?? 0 }}
                                                            </td>
                                                            <td class="text-center align-middle text-danger"
                                                                style="font-size: 0.9rem;">
                                                                {{ $standing->losses ?? 0 }}
                                                            </td>
                                                            <td class="text-center align-middle fw-bold" style="font-size: 0.9rem;">
                                                                <span
                                                                    style="
                                                                                                                                                                                                                                                display: inline-block;
                                                                                                                                                                                                                                                padding: 2px 6px;
                                                                                                                                                                                                                                                border-radius: 4px;
                                                                                                                                                                                                                                                background-color: {{ $gdValue > 0 ? 'rgba(16, 185, 129, 0.1)' : ($gdValue < 0 ? 'rgba(239, 68, 68, 0.1)' : '#f1f5f9') }};
                                                                                                                                                                                                                                                color: {{ $gdValue > 0 ? '#10b981' : ($gdValue < 0 ? '#ef4444' : '#64748b') }};
                                                                                                                                                                                                                                                min-width: 40px;
                                                                                                                                                                                                                                            ">
                                                                    {{ $gdDisplay }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center align-middle fw-bold" style="font-size: 0.9rem;">
                                                                <span
                                                                    style="
                                                                                                                                                                                                                                                display: inline-block;
                                                                                                                                                                                                                                                padding: 2px 8px;
                                                                                                                                                                                                                                                border-radius: 4px;
                                                                                                                                                                                                                                                background-color: rgba(59, 130, 246, 0.1);
                                                                                                                                                                                                                                                color: #1d4ed8;
                                                                                                                                                                                                                                                min-width: 40px;
                                                                                                                                                                                                                                            ">
                                                                    {{ $standing->points ?? 0 }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        No group standings available yet.
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('standings') }}" class="btn btn-dark">
                                <i class="bi bi-table"></i> View Full Standings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Top Scorers -->
                <!-- Top Scorers -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-trophy"></i>
                            <span>Top Scorers</span>
                            @if($activeTournament)
                                <span class="badge bg-primary">{{ $activeTournament->name }}</span>
                            @endif
                        </div>
                        @if($activeTournament)
                            <small
                                class="text-muted d-none d-md-block">{{ $activeTournament->season ?? 'Season 2025' }}</small>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($topScorers->count() > 0)
                            @foreach($topScorers as $index => $player)
                                <div class="top-scorer-player-card">
                                    <!-- Rank Badge - Tampilkan ranking (1, 2, 3, dll) -->
                                    <div class="top-scorer-rank-badge top-scorer-rank-{{ min($index + 1, 4) }}">
                                        {{ $index + 1 }}
                                    </div>

                                    <div class="top-scorer-player-info">
                                        <h6 class="mb-1">{{ $player->name ?? 'Unknown Player' }}</h6>
                                        <div class="top-scorer-player-team small text-muted">
                                            <i class="bi bi-people me-1"></i>
                                            {{ $player->team_name ?? ($player->team->name ?? 'No Team') }}
                                            @if(isset($player->jersey_number) && $player->jersey_number)
                                                <span class="badge bg-secondary ms-1">#{{ $player->jersey_number }}</span>
                                            @endif
                                            @if(isset($player->position) && $player->position)
                                                <span class="badge bg-light text-dark ms-1">{{ $player->position }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-end ms-2">
                                        <span class="top-scorer-goals-count">{{ $player->goals ?? 0 }} Gol</span>
                                        <div class="top-scorer-cards-count">
                                            @if(($player->yellow_cards ?? 0) > 0)
                                                <span class="text-warning me-2">
                                                    <i class="bi bi-square-fill me-1"></i>{{ $player->yellow_cards }}
                                                </span>
                                            @endif
                                            @if(($player->red_cards ?? 0) > 0)
                                                <span class="text-danger">
                                                    <i class="bi bi-square-fill me-1"></i>{{ $player->red_cards }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="text-center mt-4">
                                <a href="{{ route('top-scorers') }}?tournament={{ $activeTournament->id ?? '' }}"
                                    class="btn btn-success w-100">
                                    <i class="bi bi-arrow-right-circle me-2"></i> View All Scorers
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-person-x"></i>
                                <p class="mt-2">No tournament statistics available</p>
                                @if($activeTournament)
                                    <small class="text-muted">No goals scored in {{ $activeTournament->name }} yet</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Results -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-clock-history"></i> Recent Results
                    </div>
                    <div class="card-body">
                        @if($recentResults->count() > 0)
                            @foreach($recentResults as $match)
                                <div class="recent-result-item">
                                    <div class="small text-muted mb-2">
                                        {{ date('d M', strtotime($match->match_date)) }} •
                                        {{ ucfirst(str_replace('_', ' ', $match->round_type ?? '')) }}
                                        @if($match->group_name)
                                            • Group {{ $match->group_name }}
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="text-end" style="width: 45%;">
                                            <small class="d-block">{{ $match->homeTeam->name ?? 'TBA' }}</small>
                                        </div>
                                        <div class="text-center fw-bold px-2" style="width: 10%; min-width: 50px;">
                                            {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                                        </div>
                                        <div class="text-start" style="width: 45%;">
                                            <small class="d-block">{{ $match->awayTeam->name ?? 'TBA' }}</small>
                                        </div>
                                    </div>

                                    @if(isset($match->events) && $match->events->where('event_type', 'goal')->count() > 0)
                                        <div class="goal-scorers">
                                            <div class="row">
                                                <div class="col-6">
                                                    @foreach($match->events->where('team_id', $match->team_home_id)->where('event_type', 'goal') as $goal)
                                                        <div class="goal-item">
                                                            <span class="goal-minute">{{ $goal->minute }}'</span>
                                                            <small class="text-truncate">
                                                                {{ $goal->player->short_name ?? $goal->player->name ?? 'Unknown' }}
                                                                @if($goal->is_penalty)
                                                                    <span class="text-muted">(P)</span>
                                                                @endif
                                                                @if($goal->is_own_goal)
                                                                    <span class="text-danger">(OG)</span>
                                                                @endif
                                                            </small>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-6">
                                                    @foreach($match->events->where('team_id', $match->team_away_id)->where('event_type', 'goal') as $goal)
                                                        <div class="goal-item">
                                                            <span class="goal-minute">{{ $goal->minute }}'</span>
                                                            <small class="text-truncate">
                                                                {{ $goal->player->short_name ?? $goal->player->name ?? 'Unknown' }}
                                                                @if($goal->is_penalty)
                                                                    <span class="text-muted">(P)</span>
                                                                @endif
                                                                @if($goal->is_own_goal)
                                                                    <span class="text-danger">(OG)</span>
                                                                @endif
                                                            </small>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="bi bi-emoji-frown"></i>
                                <p class="mt-2">No recent matches</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">
                        <i class="bi bi-trophy-fill"></i> OFS Futsal Center
                    </h5>
                    <div class="social-icons">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('schedule') }}">Schedule</a>
                        <a href="{{ route('standings') }}">Standings</a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">Contact Info</h5>
                    <div class="footer-contact">
                        <p class="mb-2"><i class="bi bi-geo-alt"></i> OFS Futsal Center Jombang</p>
                        <p class="mb-2"><i class="bi bi-telephone"></i> +62 812 4752 1076</p>
                        <p class="mb-2"><i class="bi bi-envelope"></i> ofsfutsalcenter@gmail.com</p>
                        <p class="mb-0"><i class="bi bi-clock"></i> Mon-Sun: 07.00-23.30</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p class="mb-0">&copy; {{ date('Y') }} OFS Futsal Center. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar auto-close on mobile
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('.nav-link');
            const navbarCollapse = document.querySelector('.navbar-collapse');

            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        }
                    }
                });
            });

            // Live badge animation
            function refreshLiveMatches() {
                const liveBadges = document.querySelectorAll('.score-badge.live');
                liveBadges.forEach(badge => {
                    badge.style.animation = 'none';
                    setTimeout(() => {
                        badge.style.animation = 'pulse 2s infinite';
                    }, 10);
                });
            }

            refreshLiveMatches();
            setInterval(refreshLiveMatches, 5000);

            // Safe area handling
            function updateSafeArea() {
                const safeAreaTop = getComputedStyle(document.documentElement).getPropertyValue('--safe-area-inset-top');
                const safeAreaBottom = getComputedStyle(document.documentElement).getPropertyValue('--safe-area-inset-bottom');

                if (safeAreaTop) {
                    document.documentElement.style.setProperty('--navbar-padding-top', safeAreaTop);
                }

                if (safeAreaBottom) {
                    document.documentElement.style.setProperty('--footer-padding-bottom', safeAreaBottom);
                }
            }

            // Initial call and on resize
            updateSafeArea();
            window.addEventListener('resize', updateSafeArea);
        });

        // Touch-friendly scrolling for tables
        document.querySelectorAll('.table-responsive').forEach(table => {
            let isDown = false;
            let startX;
            let scrollLeft;

            table.addEventListener('mousedown', (e) => {
                isDown = true;
                startX = e.pageX - table.offsetLeft;
                scrollLeft = table.scrollLeft;
            });

            table.addEventListener('mouseleave', () => {
                isDown = false;
            });

            table.addEventListener('mouseup', () => {
                isDown = false;
            });

            table.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - table.offsetLeft;
                const walk = (x - startX) * 2;
                table.scrollLeft = scrollLeft - walk;
            });
        });
    </script>
</body>

</html>