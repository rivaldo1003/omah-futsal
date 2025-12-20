<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>OFS Futsal Center - News</title>
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


    /* {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    } */

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

    /* Compact Hero Section */
    .news-hero {
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: white;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        border-radius: 0 0 8px 8px;
        text-align: center;
    }

    .news-hero h1 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .news-hero p {
        font-size: 0.9rem;
        opacity: 0.9;
        max-width: 500px;
        margin: 0 auto 1rem;
    }

    /* Compact Category Filter */
    .news-category-badge {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        padding: 0.2rem 0.7rem;
        border-radius: 16px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .news-category-badge:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-1px);
    }

    .news-category-badge.active {
        background: white;
        color: #3b82f6;
        border-color: white;
    }

    /* Compact Cards */
    .card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px var(--card-shadow);
        background: white;
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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

    .card-body {
        padding: 1rem;
    }

    /* Compact News Article Card */
    .news-article-card {
        height: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    .news-article-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    .news-image-container {
        position: relative;
        overflow: hidden;
        height: 160px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    }

    .news-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .news-category-tag {
        position: absolute;
        top: 8px;
        left: 8px;
        background: rgba(59, 130, 246, 0.9);
        color: white;
        padding: 0.15rem 0.6rem;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 2;
    }

    .news-featured-tag {
        position: absolute;
        top: 8px;
        right: 8px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 0.15rem 0.6rem;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 2;
    }

    .news-content {
        padding: 0.8rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .news-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .news-excerpt {
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.4;
        margin-bottom: 0.8rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .news-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 0.5rem;
        border-top: 1px solid #f1f5f9;
    }

    .news-source,
    .news-date {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .news-read-more {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    /* Compact Featured News */
    .featured-news-card {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    .featured-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .featured-content {
        padding: 1rem;
        background: white;
    }

    .featured-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        line-height: 1.3;
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

    /* Pagination */
    .pagination {
        justify-content: center;
        margin-top: 1.5rem;
        font-size: 0.85rem;
    }

    .page-item .page-link {
        border-radius: 4px;
        margin: 0 2px;
        padding: 0.3rem 0.6rem;
        font-size: 0.85rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #64748b;
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.6;
    }

    /* Responsive Adjustments */
    @media (min-width: 576px) {
        body {
            font-size: 14.5px;
        }

        .news-hero h1 {
            font-size: 1.8rem;
        }

        .news-image-container {
            height: 180px;
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

        .news-hero {
            padding: 2rem 0;
        }

        .news-hero h1 {
            font-size: 2rem;
        }

        .news-image-container {
            height: 160px;
        }
    }

    @media (min-width: 992px) {
        .news-hero h1 {
            font-size: 2.2rem;
        }

        .news-hero p {
            font-size: 1rem;
        }

        .news-image-container {
            height: 180px;
        }

        .featured-image {
            height: 220px;
        }
    }

    @media (min-width: 1200px) {
        .news-image-container {
            height: 200px;
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


    .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .p-compact-2 {
        padding: 0.8rem;
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

    <!-- News Hero Section -->
    <section class="news-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h1 class="mb-compact-1">
                        <i class="bi bi-newspaper"></i> Latest News
                    </h1>
                    <p class="mb-compact-2">Stay updated with the latest news, match reports, and announcements from OFS
                        Futsal Center</p>

                    <!-- Category Filter -->
                    <div class="d-flex flex-wrap justify-content-center gap-1 mb-1">
                        <a href="{{ route('news.index') }}"
                            class="news-category-badge {{ !request()->has('category') ? 'active' : '' }}">
                            All News
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('news.index', ['category' => $category]) }}"
                            class="news-category-badge {{ request('category') == $category ? 'active' : '' }}">
                            {{ $category }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container main-container">
        <div class="row">
            <!-- Left Column - Main News -->
            <div class="col-lg-8 mb-compact-2">
                <!-- Featured News -->
                @if($featuredNews->count() > 0)
                <div class="card featured-news-card">
                    <div class="row g-0">
                        @foreach($featuredNews->take(1) as $featured)
                        <div class="col-md-6">
                            <div class="position-relative h-100">
                                @if($featured->image_url)
                                <img src="{{ $featured->image_url }}" alt="{{ $featured->title }}"
                                    class="featured-image">
                                @else
                                <div
                                    class="featured-image bg-gradient d-flex align-items-center justify-content-center">
                                    <i class="bi bi-newspaper" style="font-size: 3rem; color: #94a3b8;"></i>
                                </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-primary py-1">Featured</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="featured-content">
                                <span class="badge bg-secondary mb-1">{{ $featured->category }}</span>
                                <h3 class="featured-title">{{ Str::limit($featured->title, 60) }}</h3>
                                <p class="compact-text text-muted mb-2">{{ Str::limit($featured->excerpt, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-2 compact-meta">
                                    <span>
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $featured->published_at->format('d M Y') }}
                                    </span>
                                    <span>
                                        <i class="bi bi-eye me-1"></i>
                                        {{ $featured->views_count }}
                                    </span>
                                </div>
                                <a href="{{ route('news.show', $featured->id) }}" class="btn btn-primary btn-sm">
                                    Read Full Story <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- All News Grid -->
                <div class="row mb-compact-2">
                    <div class="col-12">
                        <h3 class="compact-heading">
                            @if(request()->has('category'))
                            <i class="bi bi-tag"></i> {{ request('category') }} News
                            @else
                            <i class="bi bi-newspaper"></i> All News
                            @endif
                            <span class="badge bg-secondary ms-1">{{ $news->total() }}</span>
                        </h3>
                    </div>

                    @if($news->count() > 0)
                    @foreach($news as $article)
                    <div class="col-md-6 mb-3">
                        <div class="news-article-card">
                            <!-- Image -->
                            <div class="news-image-container">
                                @if($article->image_url)
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="news-image">
                                @else
                                <div class="news-image bg-gradient d-flex align-items-center justify-content-center">
                                    <i class="bi bi-newspaper" style="font-size: 2rem; color: #94a3b8;"></i>
                                </div>
                                @endif
                                <div class="news-category-tag">{{ $article->category }}</div>
                                @if($article->is_featured)
                                <div class="news-featured-tag">Featured</div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="news-content">
                                <h4 class="news-title">{{ Str::limit($article->title, 70) }}</h4>
                                <p class="news-excerpt">{{ Str::limit($article->excerpt, 90) }}</p>

                                <div class="news-meta">
                                    <span class="news-source">
                                        <i class="bi bi-link-45deg"></i> {{ $article->source ?? 'OFS News' }}
                                    </span>
                                    <span class="news-date">
                                        <i class="bi bi-clock"></i> {{ $article->published_at->diffForHumans() }}
                                    </span>
                                </div>

                                <a href="{{ route('news.show', $article->id) }}" class="news-read-more mt-2">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-newspaper"></i>
                            <h4 class="mt-2">No News Available</h4>
                            <p class="compact-text text-muted">There are no news articles to display at the moment.</p>
                            @if(request()->has('category'))
                            <a href="{{ route('news.index') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="bi bi-arrow-left me-1"></i> View All News
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Pagination -->
                    @if($news->hasPages())
                    <div class="col-12">
                        <nav aria-label="News pagination">
                            {{ $news->withQueryString()->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
                <!-- Latest News Sidebar -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center p-compact-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history"></i>
                            <span>Latest Updates</span>
                        </div>
                        <span class="badge bg-primary">{{ $latestNews->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($latestNews->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($latestNews as $article)
                            <a href="{{ route('news.show', $article->id) }}"
                                class="list-group-item list-group-item-action border-0 px-3 py-2 sidebar-news-item">
                                <div class="d-flex align-items-start gap-2">
                                    @if($article->image_url)
                                    <div class="flex-shrink-0" style="width: 50px; height: 50px;">
                                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="rounded"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    @endif
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <h6 class="sidebar-news-title mb-1">{{ Str::limit($article->title, 50) }}</h6>
                                        <div class="sidebar-news-meta">
                                            <span class="sidebar-news-category">{{ $article->category }}</span>
                                            <span
                                                class="sidebar-news-date">{{ $article->published_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-3">
                            <i class="bi bi-newspaper text-muted" style="font-size: 1.5rem;"></i>
                            <p class="compact-text text-muted mb-0 mt-1">No news available</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Categories -->
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
                            @foreach($popularNews as $article)
                            <a href="{{ route('news.show', $article->id) }}"
                                class="list-group-item list-group-item-action border-0 px-3 py-2 sidebar-news-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div style="max-width: 70%;">
                                        <h6 class="sidebar-news-title mb-1">{{ Str::limit($article->title, 40) }}</h6>
                                        <div class="sidebar-news-meta">
                                            <span class="sidebar-news-category">{{ $article->category }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                                            <i class="bi bi-eye"></i> {{ $article->views_count }}
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

        // Newsletter form
        const newsletterForm = document.getElementById('newsletterForm');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const emailInput = this.querySelector('input[type="email"]');
                const email = emailInput.value.trim();

                if (email) {
                    // Simple success feedback
                    const originalText = this.querySelector('button').innerHTML;
                    this.querySelector('button').innerHTML = '<i class="bi bi-check"></i>';
                    this.querySelector('button').classList.remove('btn-primary');
                    this.querySelector('button').classList.add('btn-success');

                    emailInput.value = '';

                    setTimeout(() => {
                        this.querySelector('button').innerHTML = originalText;
                        this.querySelector('button').classList.remove('btn-success');
                        this.querySelector('button').classList.add('btn-primary');
                    }, 2000);
                }
            });
        }

        // Scroll to top button
        const scrollTopBtn = document.createElement('button');
        scrollTopBtn.innerHTML = '<i class="bi bi-chevron-up"></i>';
        scrollTopBtn.className =
            'btn btn-primary btn-sm position-fixed bottom-2 end-2 rounded-circle shadow-sm';
        scrollTopBtn.style.width = '40px';
        scrollTopBtn.style.height = '40px';
        scrollTopBtn.style.zIndex = '1000';
        scrollTopBtn.style.display = 'none';

        document.body.appendChild(scrollTopBtn);

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        window.addEventListener('scroll', () => {
            scrollTopBtn.style.display = window.pageYOffset > 300 ? 'flex' : 'none';
        });

        // Lazy load images
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
    });
    </script>
</body>

</html>