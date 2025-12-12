@extends('layouts.admin')

@section('title', $team->name . ' - Team Details')

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

        /* Breadcrumb */
        .breadcrumb {
            font-size: 0.875rem;
            padding: 0;
            background: none;
            margin-bottom: 1rem;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
            padding: 1.5rem;
            border-radius: 8px;
            color: white;
            margin-bottom: 1.5rem;
        }

        .team-logo-large {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 2rem;
            margin-right: 1rem;
            flex-shrink: 0;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .team-logo-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .team-name {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }

        .team-meta {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
        }

        .badge-status-active {
            background: rgba(34, 197, 94, 0.2);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .badge-status-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-status-inactive {
            background: rgba(239, 68, 68, 0.2);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-group {
            background: rgba(30, 58, 138, 0.2);
            color: var(--primary);
            border: 1px solid rgba(30, 58, 138, 0.3);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-action:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Main Card */
        .main-card {
            background: var(--bg-card);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 1.5rem;
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
        }

        .stat-title {
            font-size: 0.75rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-detail {
            font-size: 0.875rem;
            color: var(--secondary);
        }

        /* Info Table */
        .info-table {
            width: 100%;
            font-size: 0.875rem;
        }

        .info-table th {
            text-align: left;
            padding: 0.5rem;
            color: var(--secondary);
            font-weight: 500;
            width: 140px;
        }

        .info-table td {
            padding: 0.5rem;
            color: var(--primary);
        }

        /* Logo in info table */
        .team-logo-small {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: 1px solid var(--border-color);
            margin-right: 0.5rem;
        }

        .team-logo-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Players Table */
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

        /* Player Info */
        .player-avatar {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            margin-right: 0.75rem;
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

        /* Action Buttons */
        .btn-small {
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
            margin: 0 2px;
        }

        .btn-small:hover {
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

        /* Empty State */
        .empty-state {
            padding: 2rem 1rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 2rem;
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

        /* Modal */
        .modal-content {
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .modal-body {
            padding: 1.5rem;
        }

        /* Quick Actions */
        .quick-actions {
            padding: 1rem;
            border-top: 1px solid var(--border-color);
            background: #f8fafc;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.5rem;
        }

        .action-btn {
            padding: 0.5rem;
            font-size: 0.75rem;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.2s;
            text-align: center;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Logo Container */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .player-photo {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e5e5e5;
            margin-right: 12px;
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
            font-weight: 700;
            color: #fff;
            background: #6c757d;
            border-radius: 50%;
        }


        /* Mobile Responsive */
        @media (max-width: 768px) {
            .page-header {
                text-align: center;
                padding: 1rem;
            }

            .team-logo-large {
                margin: 0 auto 1rem;
            }

            .team-meta {
                justify-content: center;
            }

            .action-buttons {
                justify-content: center;
                margin-top: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .info-table th {
                width: 120px;
            }

            .table-responsive {
                font-size: 0.75rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.5rem;
            }

            .action-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.teams.index') }}">Teams</a></li>
            <li class="breadcrumb-item active">{{ $team->name }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-column flex-md-row align-items-center">
            <div class="team-logo-large mb-3 mb-md-0">
                @if($team->logo)
                    @if(Storage::disk('public')->exists($team->logo))
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}">
                    @elseif(filter_var($team->logo, FILTER_VALIDATE_URL))
                        <img src="{{ $team->logo }}" alt="{{ $team->name }}">
                    @else
                        {{ strtoupper(substr($team->name, 0, 1)) }}
                    @endif
                @else
                    {{ strtoupper(substr($team->name, 0, 1)) }}
                @endif
            </div>
            <div class="flex-grow-1 text-center text-md-start">
                <h1 class="team-name text-white">{{ $team->name }}</h1>
                @if($team->short_name)
                    <p class="text-white-75 mb-0">({{ $team->short_name }})</p>
                @endif
                <div class="team-meta">
                    @if($team->status == 'active')
                        <span class="badge badge-status-active">Active</span>
                    @elseif($team->status == 'pending')
                        <span class="badge badge-status-pending">Pending</span>
                    @else
                        <span class="badge badge-status-inactive">Inactive</span>
                    @endif

                    @if($team->group_name)
                        <span class="badge badge-group">Group {{ $team->group_name }}</span>
                    @endif
                </div>
            </div>
            <div class="action-buttons mt-3 mt-md-0">
                <a href="{{ route('admin.teams.edit', $team) }}" class="btn-action">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('admin.teams.index') }}" class="btn-action">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
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

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Players</div>
            <div class="stat-value">{{ $team->players->count() }}</div>
            <div class="stat-detail">Registered players</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Tournaments</div>
            <div class="stat-value">{{ $team->tournaments->count() }}</div>
            <div class="stat-detail">Active competitions</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Matches</div>
            <div class="stat-value">{{ $stats['total_matches'] ?? 0 }}</div>
            <div class="stat-detail">{{ $stats['wins'] ?? 0 }}W {{ $stats['draws'] ?? 0 }}D {{ $stats['losses'] ?? 0 }}L
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Founded</div>
            <div class="stat-value">{{ $team->created_at->format('Y') }}</div>
            <div class="stat-detail">{{ $team->created_at->diffForHumans() }}</div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Team Information -->
        <div class="col-lg-8">
            <div class="main-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Team Information</h5>
                </div>
                <div class="card-body">
                    @if($team->logo)
                        <div class="logo-container">
                            <div class="team-logo-small">
                                @if(Storage::disk('public')->exists($team->logo))
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}">
                                @elseif(filter_var($team->logo, FILTER_VALIDATE_URL))
                                    <img src="{{ $team->logo }}" alt="{{ $team->name }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100 text-white">
                                        {{ strtoupper(substr($team->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="text-muted small">Team Logo</div>
                                <div class="fw-medium">{{ basename($team->logo) }}</div>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <table class="info-table">
                        <tr>
                            <th>Full Name:</th>
                            <td>{{ $team->name }}</td>
                        </tr>
                        @if($team->short_name)
                            <tr>
                                <th>Short Name:</th>
                                <td>{{ $team->short_name }}</td>
                            </tr>
                        @endif
                        @if($team->city)
                            <tr>
                                <th>City:</th>
                                <td>{{ $team->city }}</td>
                            </tr>
                        @endif
                        @if($team->coach_name)
                            <tr>
                                <th>Coach:</th>
                                <td>{{ $team->coach_name }}</td>
                            </tr>
                        @endif
                        @if($team->contact_email)
                            <tr>
                                <th>Email:</th>
                                <td><a href="mailto:{{ $team->contact_email }}"
                                        class="text-decoration-none">{{ $team->contact_email }}</a></td>
                            </tr>
                        @endif
                        @if($team->contact_phone)
                            <tr>
                                <th>Phone:</th>
                                <td><a href="tel:{{ $team->contact_phone }}"
                                        class="text-decoration-none">{{ $team->contact_phone }}</a></td>
                            </tr>
                        @endif
                    </table>

                    @if($team->description)
                        <div class="mt-3 pt-3 border-top">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p class="mb-0">{{ $team->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Players Section -->
            <div class="main-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i> Players ({{ $team->players->count() }})</h5>
                    <a href="{{ route('admin.players.create', ['team_id' => $team->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus"></i> Add Player
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($team->players->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Player</th>
                                        <th>Position</th>
                                        <th>Jersey #</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($team->players as $player)
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
                                                @if($player->position)
                                                    <span class="badge badge-group">{{ $player->position }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($player->jersey_number)
                                                    <span class="badge badge-status-active">#{{ $player->jersey_number }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.players.show', $player) }}" class="btn-small btn-view"
                                                    title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.players.edit', $player) }}" class="btn-small btn-edit"
                                                    title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h4 class="empty-state-title">No Players Yet</h4>
                            <p class="empty-state-text">
                                Add players to this team to get started.
                            </p>
                            <a href="{{ route('admin.players.create', ['team_id' => $team->id]) }}"
                                class="btn btn-primary btn-sm">
                                <i class="bi bi-plus"></i> Add First Player
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="main-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i> Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="stat-title">Win Rate</div>
                        @php
                            $totalMatches = $stats['total_matches'] ?? 0;
                            $wins = $stats['wins'] ?? 0;
                            $winRate = $totalMatches > 0 ? round(($wins / $totalMatches) * 100, 1) : 0;
                        @endphp
                        <div class="stat-value">{{ $winRate }}%</div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $winRate }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="stat-title">Goals For/Against</div>
                        <div class="stat-value">{{ $stats['goals_for'] ?? 0 }} / {{ $stats['goals_against'] ?? 0 }}</div>
                        <div class="stat-detail">+{{ ($stats['goals_for'] ?? 0) - ($stats['goals_against'] ?? 0) }} GD</div>
                    </div>

                    @if($team->tournaments->count() > 0)
                        <div>
                            <div class="stat-title">Active Tournaments</div>
                            <div class="stat-value">{{ $team->tournaments->count() }}</div>
                            <div class="stat-detail">
                                @foreach($team->tournaments->take(3) as $tournament)
                                    <div class="mb-1">{{ $tournament->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="main-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="action-grid">
                        <a href="{{ route('admin.teams.edit', $team) }}" class="action-btn">
                            <i class="bi bi-pencil d-block mb-1"></i>
                            Edit Team
                        </a>
                        <a href="{{ route('admin.players.create', ['team_id' => $team->id]) }}" class="action-btn">
                            <i class="bi bi-plus d-block mb-1"></i>
                            Add Player
                        </a>
                        <a href="{{ route('admin.teams.index') }}" class="action-btn">
                            <i class="bi bi-list d-block mb-1"></i>
                            All Teams
                        </a>
                        <button type="button" class="action-btn text-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="bi bi-trash d-block mb-1"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Logo Preview -->
            @if($team->logo)
                <div class="main-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-image me-2"></i> Logo Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="team-logo-large mx-auto mb-3">
                                @if(Storage::disk('public')->exists($team->logo))
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="img-fluid">
                                @elseif(filter_var($team->logo, FILTER_VALIDATE_URL))
                                    <img src="{{ $team->logo }}" alt="{{ $team->name }}" class="img-fluid">
                                @else
                                    {{ strtoupper(substr($team->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="text-muted small">
                                @if(Storage::disk('public')->exists($team->logo))
                                    Local file
                                @elseif(filter_var($team->logo, FILTER_VALIDATE_URL))
                                    External URL
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions Footer -->
    <div class="quick-actions mt-4">
        <div class="action-grid">
            <a href="{{ route('admin.teams.edit', $team) }}" class="action-btn">
                <i class="bi bi-pencil me-1"></i> Edit Team
            </a>
            <a href="{{ route('admin.players.create', ['team_id' => $team->id]) }}" class="action-btn">
                <i class="bi bi-plus me-1"></i> Add Player
            </a>
            <a href="{{ route('admin.teams.index') }}" class="action-btn">
                <i class="bi bi-arrow-left me-1"></i> Back to Teams
            </a>
        </div>
    </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-exclamation-triangle text-danger fs-1 mb-3"></i>
                    <p class="mb-0">Delete "<strong>{{ $team->name }}</strong>"?</p>
                    <p class="text-muted small mt-2">
                        This action cannot be undone. All related data will be removed.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Delete Team
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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