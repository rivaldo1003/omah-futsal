<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ Str::limit($article->title, 60) }} - OFS News</title>
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
        line-height: 1.5;
        overflow-x: hidden;
        font-size: 14px;
    }

    /* Compact Container */
    .container {
        padding-left: max(12px, env(safe-area-inset-left));
        padding-right: max(12px, env(safe-area-inset-right));
        max-width: 1200px;
    }

    /* Compact Navigation */
    .navbar {
        background: var(--nav-bg);
        backdrop-filter: blur(10px);
        box-shadow: 0 1px 3px var(--nav-shadow);
        padding: 0.4rem 0;
        position: sticky;
        top: 0;
        z-index: 1040;
    }

    .navbar-brand {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .brand-container {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .brand-logo {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .brand-main {
        font-size: 1rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-color), #60a5fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .brand-sub {
        font-size: 0.6rem;
        color: #64748b;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .navbar-toggler {
        border: 1px solid rgba(59, 130, 246, 0.15);
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
    }

    .nav-link {
        color: #475569 !important;
        font-weight: 600;
        padding: 0.5rem 0.8rem !important;
        border-radius: 4px;
        margin: 1px 0;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
    }

    .nav-link i {
        font-size: 0.9rem;
        width: 18px;
        text-align: center;
    }

    .nav-link.active {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(96, 165, 250, 0.05));
        color: var(--accent-color) !important;
    }

    /* Compact Buttons */
    .btn {
        border-radius: 4px;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    /* Compact Article Hero */
    .article-hero {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: white;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .article-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.8rem;
        line-height: 1.3;
    }

    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.85rem;
    }

    .article-category {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        padding: 0.2rem 0.8rem;
        border-radius: 16px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .article-source,
    .article-date,
    .article-views,
    .article-author {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        opacity: 0.9;
    }

    /* Breadcrumb Compact */
    .breadcrumb {
        padding: 0.5rem 0;
        margin-bottom: 0.8rem;
        font-size: 0.8rem;
    }

    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.9) !important;
        text-decoration: none;
    }

    /* Main Content */
    .article-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 0.5rem;
    }

    /* Compact Featured Image */
    .article-featured-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .article-featured-image-placeholder {
        width: 100%;
        height: 250px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Compact Article Content */
    .article-content {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #334155;
    }

    .article-content h2 {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 1.5rem 0 0.8rem;
        color: #1e293b;
    }

    .article-content h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 1.2rem 0 0.6rem;
        color: #1e293b;
    }

    .article-content p {
        margin-bottom: 1rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        margin: 1.5rem 0;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .article-content blockquote {
        border-left: 3px solid #3b82f6;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #475569;
        font-size: 1rem;
        background: #f8fafc;
        padding: 0.8rem;
        border-radius: 0 6px 6px 0;
    }

    .article-content ul,
    .article-content ol {
        margin-bottom: 1rem;
        padding-left: 1.2rem;
    }

    .article-content li {
        margin-bottom: 0.3rem;
    }

    /* Compact Article Actions */
    .article-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        margin: 1.5rem 0;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .share-buttons {
        display: flex;
        gap: 0.3rem;
    }

    .share-btn {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .share-btn:hover {
        transform: translateY(-1px);
    }

    .share-facebook {
        background: #1877f2;
        color: white;
    }

    .share-twitter {
        background: #1da1f2;
        color: white;
    }

    .share-whatsapp {
        background: #25d366;
        color: white;
    }

    .share-telegram {
        background: #0088cc;
        color: white;
    }

    /* Compact Related News */
    .related-news-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1e293b;
    }

    .related-news-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        transition: all 0.2s ease;
        height: 100%;
    }

    .related-news-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    .related-news-image {
        height: 150px;
        object-fit: cover;
        width: 100%;
    }

    .related-news-content {
        padding: 0.8rem;
    }

    .related-news-title-small {
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .related-news-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* Compact Back Button */
    .back-to-news {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .back-to-news:hover {
        color: #1d4ed8;
        gap: 0.5rem;
    }

    /* Compact Cards */
    .card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px var(--card-shadow);
        background: white;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid #e2e8f0;
        padding: 0.8rem 1rem;
        font-weight: 700;
        color: #334155;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .card-header i {
        color: var(--accent-color);
    }

    .card-body {
        padding: 1rem;
    }

    /* Compact Sidebar */
    .sidebar-news-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
    }

    .sidebar-news-item:hover {
        background-color: #f8fafc;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        border-radius: 4px;
    }

    .sidebar-news-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.2rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-news-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.7rem;
    }

    .sidebar-news-category {
        background: #e2e8f0;
        color: #475569;
        padding: 0.1rem 0.4rem;
        border-radius: 2px;
        font-weight: 500;
    }

    /* Compact Footer */
    .footer {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        padding: 1.5rem 0 1rem;
        margin-top: 2rem;
        font-size: 0.8rem;
    }

    .footer-title {
        font-weight: 700;
        margin-bottom: 0.8rem;
        font-size: 0.95rem;
        color: #e2e8f0;
    }

    .footer-links a {
        color: #cbd5e1;
        text-decoration: none;
        display: block;
        margin-bottom: 0.3rem;
        font-size: 0.8rem;
    }

    .footer-contact {
        color: #cbd5e1;
        font-size: 0.75rem;
    }

    .social-icons {
        display: flex;
        gap: 8px;
        margin-top: 0.5rem;
    }

    .social-icons a {
        color: #cbd5e1;
        font-size: 1rem;
    }

    .copyright {
        color: #94a3b8;
        font-size: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    /* Grid System Adjustments */
    .main-container {
        padding-top: 0.5rem;
    }

    .col-lg-8,
    .col-lg-4 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .row {
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }

    /* Source Alert */
    .alert-info {
        padding: 0.8rem;
        font-size: 0.85rem;
        margin-top: 1.5rem;
        border-radius: 6px;
    }

    /* Badges */
    .badge {
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Responsive Adjustments */
    @media (min-width: 576px) {
        body {
            font-size: 14.5px;
        }

        .article-title {
            font-size: 1.8rem;
        }

        .article-featured-image,
        .article-featured-image-placeholder {
            height: 300px;
        }

        .article-content {
            font-size: 1rem;
        }
    }

    @media (min-width: 768px) {
        body {
            font-size: 15px;
        }

        .nav-link {
            padding: 0.5rem 0.8rem !important;
            font-size: 0.9rem;
        }

        .article-hero {
            padding: 2rem 0;
        }

        .article-title {
            font-size: 2rem;
        }

        .article-featured-image,
        .article-featured-image-placeholder {
            height: 350px;
        }

        .article-content h2 {
            font-size: 1.5rem;
        }

        .article-content h3 {
            font-size: 1.3rem;
        }
    }

    @media (min-width: 992px) {
        .article-title {
            font-size: 2.2rem;
        }

        .article-content {
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .related-news-image {
            height: 180px;
        }
    }

    @media (min-width: 1200px) {

        .article-featured-image,
        .article-featured-image-placeholder {
            height: 400px;
        }
    }

    /* Utility Classes */
    .compact-text {
        font-size: 0.85rem;
        line-height: 1.3;
    }

    .compact-meta {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .compact-heading {
        font-size: 1.1rem;
        margin-bottom: 0.8rem;
    }

    /* Better Spacing */
    .mb-compact-1 {
        margin-bottom: 0.5rem;
    }

    .mb-compact-2 {
        margin-bottom: 1rem;
    }

    .mb-compact-3 {
        margin-bottom: 1.5rem;
    }

    .p-compact-1 {
        padding: 0.5rem;
    }

    .p-compact-2 {
        padding: 0.8rem;
    }

    /* Fixed Action Buttons */
    .action-buttons {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
        display: flex;
        gap: 0.5rem;
        z-index: 1000;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .action-buttons {
            bottom: 0.5rem;
            right: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
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
                        <div class="brand-sub">CENTER</div>
                    </div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('news*') ? 'active' : '' }}"
                            href="{{ route('news.index') }}">
                            <i class="bi bi-newspaper"></i> News
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

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('highlights*') ? 'active' : '' }}"
                            href="{{ route('highlights.index') }}">
                            <i class="bi bi-play-circle"></i> Highlights
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
                        <a class="nav-link btn btn-outline-primary btn-sm" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Admin
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link btn-sm text-danger p-0">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary btn-sm" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Article Hero Section -->
    <section class="article-hero">
        <div class="container">
            <div class="article-hero-content">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-compact-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('news.index') }}">News</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Article</li>
                    </ol>
                </nav>

                <!-- Title -->
                <h1 class="article-title">{{ $article->title }}</h1>

                <!-- Meta Information -->
                <div class="article-meta">
                    @if($article->category)
                    <span class="article-category">{{ $article->category }}</span>
                    @endif

                    @if($article->source)
                    <span class="article-source">
                        <i class="bi bi-link-45deg"></i> {{ $article->source }}
                    </span>
                    @endif

                    <span class="article-date">
                        <i class="bi bi-calendar"></i> {{ $article->published_at->format('d M Y') }}
                    </span>

                    <span class="article-views">
                        <i class="bi bi-eye"></i> {{ $article->views_count }}
                    </span>

                    @if($article->author)
                    <span class="article-author">
                        <i class="bi bi-person"></i> {{ $article->author }}
                    </span>
                    @endif
                </div>

                @if($article->is_featured)
                <span class="badge bg-warning text-dark py-1">
                    <i class="bi bi-star"></i> Featured
                </span>
                @endif
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Article Content -->
            <div class="col-lg-8">
                <!-- Back Button -->
                <a href="{{ route('news.index') }}" class="back-to-news">
                    <i class="bi bi-arrow-left"></i> Back to News
                </a>

                <!-- Featured Image -->
                @if($article->image_url)
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="article-featured-image">
                @else
                <div class="article-featured-image-placeholder">
                    <i class="bi bi-newspaper" style="font-size: 3rem; color: #94a3b8;"></i>
                </div>
                @endif

                <!-- Article Content -->
                <div class="article-container">
                    <div class="article-content">
                        {!! $article->content !!}
                    </div>

                    <!-- Source URL -->
                    @if($article->source_url)
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-link-45deg"></i>
                        <strong>Source:</strong>
                        <a href="{{ $article->source_url }}" target="_blank" class="alert-link">
                            {{ Str::limit($article->source_url, 50) }}
                        </a>
                    </div>
                    @endif

                    <!-- Article Actions (Share, etc.) -->
                    <div class="article-actions">
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                target="_blank" class="share-btn share-facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode(Str::limit($article->title, 100)) }}"
                                target="_blank" class="share-btn share-twitter">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode(Str::limit($article->title, 100) . ' ' . url()->current()) }}"
                                target="_blank" class="share-btn share-whatsapp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="https://t.me/share/url?url={{ url()->current() }}&text={{ urlencode(Str::limit($article->title, 100)) }}"
                                target="_blank" class="share-btn share-telegram">
                                <i class="bi bi-telegram"></i>
                            </a>
                        </div>

                        <div class="compact-text text-muted">
                            <i class="bi bi-clock"></i>
                            Published {{ $article->published_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <!-- Related News -->
                @if($relatedNews->count() > 0)
                <div class="related-news mt-4">
                    <h3 class="related-news-title">
                        <i class="bi bi-newspaper me-1"></i>Related News
                    </h3>

                    <div class="row">
                        @foreach($relatedNews as $related)
                        <div class="col-md-6 mb-3">
                            <div class="related-news-card">
                                @if($related->image_url)
                                <img src="{{ $related->image_url }}" alt="{{ $related->title }}"
                                    class="related-news-image">
                                @else
                                <div
                                    class="related-news-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-newspaper" style="font-size: 1.5rem; color: #94a3b8;"></i>
                                </div>
                                @endif
                                <div class="related-news-content">
                                    <span class="badge bg-secondary mb-1">{{ $related->category }}</span>
                                    <h4 class="related-news-title-small">{{ Str::limit($related->title, 70) }}</h4>
                                    <div class="related-news-meta">
                                        <span>{{ $related->published_at->diffForHumans() }}</span>
                                        <span><i class="bi bi-eye"></i> {{ $related->views_count }}</span>
                                    </div>
                                    <a href="{{ route('news.show', $related->id) }}"
                                        class="btn btn-sm btn-outline-primary w-100 mt-2">
                                        Read More <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Latest News Sidebar -->
                @php
                $latestNews = \App\Models\NewsArticle::where('is_active', true)
                ->where('id', '!=', $article->id)
                ->latest('published_at')
                ->take(5)
                ->get();
                @endphp

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center p-compact-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history"></i>
                            <span>Latest News</span>
                        </div>
                        <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($latestNews->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($latestNews as $newsItem)
                            <a href="{{ route('news.show', $newsItem->id) }}"
                                class="list-group-item list-group-item-action border-0 px-3 py-2 sidebar-news-item">
                                <div class="d-flex align-items-start gap-2">
                                    @if($newsItem->image_url)
                                    <div class="flex-shrink-0" style="width: 50px; height: 50px;">
                                        <img src="{{ $newsItem->image_url }}" alt="{{ $newsItem->title }}"
                                            class="rounded" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    @endif
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <h6 class="sidebar-news-title mb-1">{{ Str::limit($newsItem->title, 50) }}</h6>
                                        <div class="sidebar-news-meta">
                                            <span class="sidebar-news-category">{{ $newsItem->category }}</span>
                                            <span
                                                class="sidebar-news-date">{{ $newsItem->published_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-3">
                            <i class="bi bi-newspaper text-muted" style="font-size: 1.5rem;"></i>
                            <p class="compact-text text-muted mb-0 mt-1">No other news available</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Categories -->
                @php
                $categories = \App\Models\NewsArticle::where('is_active', true)
                ->select('category')
                ->distinct()
                ->pluck('category');
                @endphp

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center p-compact-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-tags"></i>
                            <span>Categories</span>
                        </div>
                        <span class="badge bg-secondary">{{ $categories->count() }}</span>
                    </div>
                    <div class="card-body p-compact-2">
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($categories as $category)
                            <a href="{{ route('news.index', ['category' => $category]) }}"
                                class="badge bg-light text-dark text-decoration-none px-2 py-1 {{ request('category') == $category ? 'bg-primary text-white' : '' }}"
                                style="font-size: 0.75rem;">
                                {{ $category }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Popular News -->
                @php
                $popularNews = \App\Models\NewsArticle::where('is_active', true)
                ->where('id', '!=', $article->id)
                ->orderBy('views_count', 'desc')
                ->take(4)
                ->get();
                @endphp

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center p-compact-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-fire"></i>
                            <span>Most Popular</span>
                        </div>
                        <i class="bi bi-bar-chart text-warning"></i>
                    </div>
                    <div class="card-body p-0">
                        @if($popularNews->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($popularNews as $newsItem)
                            <a href="{{ route('news.show', $newsItem->id) }}"
                                class="list-group-item list-group-item-action border-0 px-3 py-2 sidebar-news-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div style="max-width: 70%;">
                                        <h6 class="sidebar-news-title mb-1">{{ Str::limit($newsItem->title, 40) }}</h6>
                                        <div class="sidebar-news-meta">
                                            <span class="sidebar-news-category">{{ $newsItem->category }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                                            <i class="bi bi-eye"></i> {{ $newsItem->views_count }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-3">
                            <i class="bi bi-bar-chart text-muted" style="font-size: 1.5rem;"></i>
                            <p class="compact-text text-muted mb-0 mt-1">No popular articles yet</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Newsletter -->
                <!-- <div class="card">
                    <div class="card-header p-compact-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope"></i>
                            <span>Newsletter</span>
                        </div>
                    </div>
                    <div class="card-body p-compact-2">
                        <p class="compact-text text-muted mb-2">Get the latest news in your inbox</p>
                        <form action="#" method="POST" id="newsletterForm">
                            <div class="input-group input-group-sm mb-2">
                                <input type="email" class="form-control form-control-sm" placeholder="Email" required>
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>
                        </form>
                        <p class="compact-text text-muted mb-0">We respect your privacy</p>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="#" class="action-btn bg-primary text-white" id="scrollTopBtn">
            <i class="bi bi-chevron-up"></i>
        </a>
        <a href="javascript:window.print()" class="action-btn bg-secondary text-white">
            <i class="bi bi-printer"></i>
        </a>
        <a href="#" class="action-btn bg-success text-white" id="copyLinkBtn">
            <i class="bi bi-link-45deg"></i>
        </a>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-3">
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
                <div class="col-lg-4 mb-3">
                    <h5 class="footer-title">Quick Links</h5>
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('news.index') }}">News</a>
                        <a href="{{ route('schedule') }}">Schedule</a>
                        <a href="{{ route('standings') }}">Standings</a>
                        <a href="{{ route('highlights.index') }}">Highlights</a>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <h5 class="footer-title">Contact Info</h5>
                    <div class="footer-contact">
                        <p class="mb-1"><i class="bi bi-geo-alt"></i> OFS Futsal Center Jombang</p>
                        <p class="mb-1"><i class="bi bi-telephone"></i> +62 812 4752 1076</p>
                        <p class="mb-1"><i class="bi bi-envelope"></i> ofsfutsalcenter@gmail.com</p>
                        <p class="mb-0"><i class="bi bi-clock"></i> Mon-Sun: 07.00-23.30</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p class="mb-0">&copy; {{ date('Y') }} OFS Futsal Center. All rights reserved.</p>
                        <p class="mb-0 small mt-1">
                            <i class="bi bi-newspaper me-1"></i> Article updated:
                            {{ $article->updated_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile navbar close
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

        // Share buttons
        const shareButtons = document.querySelectorAll('.share-btn');
        shareButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.href;
                const width = 550;
                const height = 400;
                const left = (screen.width - width) / 2;
                const top = (screen.height - height) / 2;

                window.open(url, 'share',
                    `width=${width},height=${height},left=${left},top=${top},menubar=no,toolbar=no,resizable=yes,scrollbars=yes`
                );
            });
        });

        // Newsletter form
        const newsletterForm = document.getElementById('newsletterForm');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const emailInput = this.querySelector('input[type="email"]');
                const email = emailInput.value.trim();

                if (email) {
                    const btn = this.querySelector('button');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check"></i>';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');

                    emailInput.value = '';

                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-primary');
                    }, 2000);
                }
            });
        }

        // Scroll to top
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        if (scrollTopBtn) {
            scrollTopBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Copy link
        const copyLinkBtn = document.getElementById('copyLinkBtn');
        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    const originalHTML = copyLinkBtn.innerHTML;
                    copyLinkBtn.innerHTML = '<i class="bi bi-check"></i>';
                    copyLinkBtn.classList.remove('bg-success');
                    copyLinkBtn.classList.add('bg-info');

                    setTimeout(() => {
                        copyLinkBtn.innerHTML = originalHTML;
                        copyLinkBtn.classList.remove('bg-info');
                        copyLinkBtn.classList.add('bg-success');
                    }, 2000);
                });
            });
        }

        // Image lazy loading
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            if (!img.complete) {
                img.style.opacity = '0';
                img.addEventListener('load', function() {
                    this.style.transition = 'opacity 0.3s ease';
                    this.style.opacity = '1';
                });
            }
        });

        // Update views count
        setTimeout(() => {
            fetch(`/news/{{ $article->id }}/increment-views`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
        }, 2000);
    });

    // Simple safe area handling
    function updateSafeArea() {
        const safeAreaBottom = getComputedStyle(document.documentElement).getPropertyValue('--safe-area-inset-bottom');
        if (safeAreaBottom) {
            const actionButtons = document.querySelector('.action-buttons');
            if (actionButtons) {
                actionButtons.style.bottom = `calc(1rem + ${safeAreaBottom})`;
            }
        }
    }

    updateSafeArea();
    window.addEventListener('resize', updateSafeArea);
    </script>
</body>

</html>