{{-- resources/views/partials/navbar.blade.php --}}
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

                <!-- Highlights link -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('highlights*') ? 'active' : '' }}"
                        href="{{ route('highlights.index') }}">
                        <i class="bi bi-play-circle"></i> Highlights
                    </a>
                </li>

                @auth
                @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('teams*') ? 'active' : '' }}" href="{{ route('teams.index') }}">
                        <i class="bi bi-people-fill"></i> Teams
                    </a>
                </li>

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