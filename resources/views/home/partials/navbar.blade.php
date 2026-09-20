<nav class="navbar navbar-expand-lg app-navbar">
    <div class="app-container d-flex align-items-center justify-content-between">
        <a class="nav-brand-wrap" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="nav-brand-logo">
            <div>
                <span class="nav-brand-title">OFS FUTSAL</span>
                <span class="nav-brand-sub">Center</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#homeNav"
            aria-controls="homeNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="homeNav">
            <ul class="nav-menu-list ms-auto flex-column flex-lg-row align-items-start align-items-lg-center py-2 py-lg-0">
                <li>
                    <a class="nav-menu-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="bi bi-house-door"></i>
                        <span>Home</span>
                    </a>
                </li>

                <li>
                    <a class="nav-menu-link {{ request()->is('news*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                        <i class="bi bi-newspaper"></i>
                        <span>News</span>
                    </a>
                </li>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <li>
                            <a class="nav-menu-link {{ request()->is('tournaments*') ? 'active' : '' }}"
                                href="{{ route('tournaments.index') }}">
                                <i class="bi bi-trophy"></i>
                                <span>Tournaments</span>
                            </a>
                        </li>
                    @endif
                @endauth

                <li>
                    <a class="nav-menu-link {{ request()->is('schedule*') || request()->is('matches*') ? 'active' : '' }}"
                        href="{{ route('schedule') }}">
                        <i class="bi bi-calendar2-week"></i>
                        <span>Schedule</span>
                    </a>
                </li>

                <li>
                    <a class="nav-menu-link {{ request()->is('standings*') ? 'active' : '' }}"
                        href="{{ route('standings') }}">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Standings</span>
                    </a>
                </li>

                <li>
                    <a class="nav-menu-link {{ request()->is('highlights*') ? 'active' : '' }}"
                        href="{{ route('highlights.index') }}">
                        <i class="bi bi-play-circle"></i>
                        <span>Highlights</span>
                    </a>
                </li>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <li>
                            <a class="nav-menu-link {{ request()->is('teams*') ? 'active' : '' }}"
                                href="{{ route('teams.index') }}">
                                <i class="bi bi-people"></i>
                                <span>Teams</span>
                            </a>
                        </li>

                        <li class="ms-lg-2 my-1 my-lg-0">
                            <a class="nav-btn-admin" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Admin panel</span>
                            </a>
                        </li>
                    @endif

                    <li class="ms-lg-2 my-1 my-lg-0">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-btn-logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                @else
                    <li class="ms-lg-2 my-1 my-lg-0">
                        <a class="nav-btn-login" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Login</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
