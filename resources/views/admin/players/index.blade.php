@extends('layouts.admin')



@section('title', 'Players Management')

@section('styles')
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #6b7280;
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e5e7eb;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            color: var(--primary);
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }

        .btn-create {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .btn-create:hover {
            background: #1d4ed8;
            color: white;
        }

        /* Search Box */
        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            padding-left: 2.5rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            width: 100%;
            font-size: 0.875rem;
        }

        .search-box input:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
            pointer-events: none;
        }

        /* Main Card */
        .main-card {
            background: var(--bg-card);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary);
        }

        /* Table Styling */
        .table {
            margin: 0;
            font-size: 0.875rem;
        }

        .table thead th {
            background: #f8fafc;
            color: var(--secondary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.02);
        }

        /* Player Info with Photo */
        .player-photo {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid var(--border-color);
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .player-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .player-photo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .player-name {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 2px;
            font-size: 0.875rem;
        }

        .player-details {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .jersey-number {
            font-weight: 600;
            color: var(--primary-light);
            margin-right: 0.5rem;
        }

        /* Team Badge */
        .team-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.5rem;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.75rem;
            color: var(--primary);
        }

        .team-initial {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Position Badge */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
        }

        .badge-flank {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-light);
        }

        .badge-anchor {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .badge-pivot {
            background: rgba(168, 85, 247, 0.1);
            color: #9333ea;
        }

        .badge-kiper {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Stats Display */
        .stats-grid {
            display: flex;
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 0.65rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.25rem;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-action:hover {
            border-color: var(--primary-light);
        }

        .btn-view:hover {
            background: var(--primary-light);
            color: white;
        }

        .btn-edit:hover {
            background: #f59e0b;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: white;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 2.5rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-state-title {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            color: var(--secondary);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Pagination Styling */
        .pagination-container {
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--border-color);
            background: #f8fafc;
        }

        .pagination-info {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .pagination-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .pagination-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f8fafc;
        }

        .pagination-pages {
            display: flex;
            gap: 0.25rem;
        }

        .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.2s;
            min-width: 32px;
            text-align: center;
        }

        .page-link:hover {
            background: #f8fafc;
            color: var(--primary);
            border-color: var(--primary-light);
        }

        .page-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Alert */
        .alert {
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            border: none;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .search-box {
                width: 100%;
            }

            .table-responsive {
                font-size: 0.75rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.5rem;
            }

            .player-photo {
                width: 36px;
                height: 36px;
                margin-right: 0.5rem;
            }

            .stats-grid {
                gap: 0.5rem;
            }

            .stat-value {
                font-size: 0.75rem;
            }

            .stat-label {
                font-size: 0.6rem;
            }

            .pagination-container {
                flex-direction: column;
                gap: 0.75rem;
            }

            .pagination-controls {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination-pages {
                order: -1;
                flex-wrap: wrap;
                justify-content: center;
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size: 0.875rem; padding: 0; background: none;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Players</li>
        </ol>
    </nav>

    <div class="page-header">
        <h1>
            <i class="bi bi-people me-2"></i>
            Players
        </h1>
        <div class="d-flex gap-2 align-items-center">
            <div class="search-box position-relative">
                <i class="bi bi-search search-icon position-absolute"></i>
                <input type="text" id="searchInput" placeholder="Search players..." class="form-control"
                    style="padding-left: 32px; height: 38px;" value="{{ request('search') }}">
            </div>

            <a href="{{ route('admin.players.create') }}" class="btn btn-create d-flex align-items-center"
                style="height: 38px;">
                <i class="bi bi-plus me-1"></i> Add Player
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="main-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list me-2"></i> Players List</h5>
        </div>

        <div class="card-body p-0">
            @if($players->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="playersTable">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Team</th>
                                <th>Position</th>
                                <th>Stats</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $player)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="player-photo">
                                                @php
                                                    // Get player photo
                                                    $playerPhoto = $player->photo ?? null;
                                                    $nameParts = explode(' ', $player->name);
                                                    $initials = '';
                                                    if (count($nameParts) >= 2) {
                                                        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                                    } else {
                                                        $initials = strtoupper(substr($player->name, 0, 2));
                                                    }

                                                    // Process photo path
                                                    $photoPath = null;
                                                    if ($playerPhoto) {
                                                        if (filter_var($playerPhoto, FILTER_VALIDATE_URL)) {
                                                            $photoPath = $playerPhoto;
                                                        } else {
                                                            try {
                                                                if (Storage::disk('public')->exists($playerPhoto)) {
                                                                    $photoPath = asset('storage/' . $playerPhoto);
                                                                } else {
                                                                    $photoPath = null;
                                                                }
                                                            } catch (Exception $e) {
                                                                $photoPath = null;
                                                            }
                                                        }
                                                    }
                                                @endphp

                                                @if($photoPath)
                                                    <img src="{{ $photoPath }}" alt="{{ $player->name }}"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="player-photo-fallback" style="display: none;">
                                                        {{ $initials }}
                                                    </div>
                                                @else
                                                    <div class="player-photo-fallback">
                                                        {{ $initials }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="player-name">{{ $player->name }}</div>
                                                <div class="player-details">
                                                    @if($player->jersey_number)
                                                        <span class="jersey-number">#{{ $player->jersey_number }}</span>
                                                    @endif
                                                    @if($player->nationality)
                                                        <span>{{ $player->nationality }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($player->team)
                                            <div class="team-badge">
                                                <div class="team-initial">
                                                    {{ strtoupper(substr($player->team->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $player->team->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($player->position)
                                            @php
                                                $positionClass = 'badge-' . strtolower($player->position);
                                            @endphp
                                            <span class="badge {{ $positionClass }}">{{ $player->position }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="stats-grid">
                                            <div class="stat-item">
                                                <div class="stat-value">{{ $player->goals }}</div>
                                                <div class="stat-label">Goals</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-value">{{ $player->assists }}</div>
                                                <div class="stat-label">Assists</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-value">{{ $player->yellow_cards }}</div>
                                                <div class="stat-label">YC</div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-value">{{ $player->red_cards }}</div>
                                                <div class="stat-label">RC</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.players.show', $player) }}" class="btn-action btn-view"
                                                title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.players.edit', $player) }}" class="btn-action btn-edit"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.players.destroy', $player) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete"
                                                    onclick="return confirm('Delete this player?')" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($players->hasPages())
                    <div class="pagination-container d-flex justify-content-between align-items-center flex-wrap">
                        <div class="pagination-info">
                            Showing {{ $players->firstItem() }} to {{ $players->lastItem() }} of {{ $players->total() }} players
                        </div>

                        <div class="pagination-controls">
                            <!-- Previous Button -->
                            @if($players->onFirstPage())
                                <span class="pagination-btn disabled">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </span>
                            @else
                                <a href="{{ $players->previousPageUrl() }}" class="pagination-btn">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="pagination-pages">
                                @php
                                    $current = $players->currentPage();
                                    $last = $players->lastPage();
                                    $start = max(1, $current - 2);
                                    $end = min($last, $current + 2);

                                    if ($start > 1) {
                                        echo '<a href="' . $players->url(1) . '" class="page-link">1</a>';
                                        if ($start > 2) {
                                            echo '<span class="page-link disabled">...</span>';
                                        }
                                    }

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $current) {
                                            echo '<span class="page-link active">' . $i . '</span>';
                                        } else {
                                            echo '<a href="' . $players->url($i) . '" class="page-link">' . $i . '</a>';
                                        }
                                    }

                                    if ($end < $last) {
                                        if ($end < $last - 1) {
                                            echo '<span class="page-link disabled">...</span>';
                                        }
                                        echo '<a href="' . $players->url($last) . '" class="page-link">' . $last . '</a>';
                                    }
                                @endphp
                            </div>

                            <!-- Next Button -->
                            @if($players->hasMorePages())
                                <a href="{{ $players->nextPageUrl() }}" class="pagination-btn">
                                    Next <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <span class="pagination-btn disabled">
                                    Next <i class="bi bi-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4 class="empty-state-title">No Players Found</h4>
                    <p class="empty-state-text">
                        Start by adding your first player to the system.
                    </p>
                    <a href="{{ route('admin.players.create') }}" class="btn-create">
                        <i class="bi bi-plus"></i>
                        Add First Player
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Search functionality
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('keyup', function (e) {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                const search = this.value;
                if (search) {
                    window.location.href = '{{ route("admin.players.index") }}?search=' + encodeURIComponent(search);
                } else {
                    window.location.href = '{{ route("admin.players.index") }}';
                }
            }, 500);
        });

        // Enter key to submit search
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                this.dispatchEvent(new Event('keyup'));
            }
        });

        // Handle photo loading errors
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.player-photo img').forEach(img => {
                img.addEventListener('error', function () {
                    console.log('Player photo failed to load:', this.src);
                    this.style.display = 'none';
                    const fallback = this.nextElementSibling;
                    if (fallback && fallback.classList.contains('player-photo-fallback')) {
                        fallback.style.display = 'flex';
                    }
                });
            });
        });

        // Auto-dismiss alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection