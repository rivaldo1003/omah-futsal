@extends('layouts.admin')


@section('title', 'Player Details')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-1"><i class="bi bi-person-badge text-primary me-2"></i>{{ $player->name }}</h2>
                <p class="text-muted mb-0">Player details and statistics</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.players.edit', $player) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('admin.players.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <!-- Stats Cards - hanya tampilkan statistik yang ada datanya -->
        @php
            $statCards = collect([
                ['label' => 'Goals', 'value' => $player->goals ?? 0, 'icon' => 'bullseye', 'color' => 'primary'],
                ['label' => 'Assists', 'value' => $player->assists ?? 0, 'icon' => 'share', 'color' => 'success'],
                ['label' => 'Penalty Goals', 'value' => $player->penalty_goals ?? 0, 'icon' => 'flag', 'color' => 'info'],
                ['label' => 'Yellow Cards', 'value' => $player->yellow_cards ?? 0, 'icon' => 'exclamation-triangle', 'color' => 'warning'],
                ['label' => 'Red Cards', 'value' => $player->red_cards ?? 0, 'icon' => 'x-circle', 'color' => 'danger'],
            ])->filter(fn ($c) => ($c['value'] ?? 0) > 0);
        @endphp
        <div class="row g-3 mb-4">
            @if($statCards->isNotEmpty())
                @foreach($statCards as $card)
                    <div class="{{ $statCards->count() >= 4 ? 'col-md-3' : 'col-md-4' }} col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-{{ $card['color'] }} bg-opacity-10 p-2 me-3">
                                        <i class="bi bi-{{ $card['icon'] }} text-{{ $card['color'] }} fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">{{ $card['label'] }}</div>
                                        <div class="h4 mb-0">{{ $card['value'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-light border mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-muted"></i>
                        <span class="text-muted small mb-0">Belum ada statistik tercatat untuk pemain ini.</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Player Info Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Avatar Column -->
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="player-photo-container mb-3 mx-auto">
                                        @php
                                            $playerPhoto = $player->photo ?? null;
                                            $nameParts = explode(' ', $player->name);
                                            $initials = count($nameParts) >= 2
                                                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                                : strtoupper(substr($player->name, 0, 2));

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
                                            <div class="player-photo-large">
                                                <img src="{{ $photoPath }}" alt="{{ $player->name }}" class="player-photo-img"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="player-photo-fallback-large" style="display: none;">
                                                    {{ $initials }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="player-photo-fallback-large">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <h5 class="fw-bold mb-2">{{ $player->name }}</h5>
                                        @if($player->jersey_number)
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                #{{ $player->jersey_number }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($player->position)
                                        <div class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2 mb-2">
                                            {{ $player->position }}
                                        </div>
                                    @endif

                                    @if($player->team)
                                        <div class="mt-3">
                                            <div class="text-muted small mb-1">Team</div>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="team-badge">
                                                    @if($player->team->logo)
                                                        @php
                                                            $teamLogo = null;
                                                            if (filter_var($player->team->logo, FILTER_VALIDATE_URL)) {
                                                                $teamLogo = $player->team->logo;
                                                            } else {
                                                                try {
                                                                    if (Storage::disk('public')->exists($player->team->logo)) {
                                                                        $teamLogo = asset('storage/' . $player->team->logo);
                                                                    }
                                                                } catch (Exception $e) {
                                                                    $teamLogo = null;
                                                                }
                                                            }
                                                        @endphp
                                                        @if($teamLogo)
                                                            <img src="{{ $teamLogo }}" alt="{{ $player->team->name }}"
                                                                class="team-logo-small me-1"
                                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        @endif
                                                    @endif
                                                    <div class="team-initial-small"
                                                        style="@if($player->team->logo) display: none; @endif">
                                                        {{ strtoupper(substr($player->team->name, 0, 1)) }}
                                                    </div>
                                                    <span class="fw-medium">{{ $player->team->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Details Column - hanya tampilkan field yang ada datanya -->
                            <div class="col-md-8">
                                @php
                                    $hasBirthDate = !empty($player->birth_date);
                                    $hasBirthPlace = !empty($player->birth_place);
                                    $hasBio = !empty($player->biography);
                                @endphp
                                <div class="row g-3">
                                    @if($hasBirthDate)
                                        <div class="col-md-6">
                                            <label class="text-muted small d-block">Date of Birth</label>
                                            <div class="fw-medium">
                                                {{ \Carbon\Carbon::parse($player->birth_date)->format('d M Y') }}
                                                <small class="text-muted">({{ \Carbon\Carbon::parse($player->birth_date)->age }} yrs)</small>
                                            </div>
                                        </div>
                                    @endif

                                    @if($hasBirthPlace)
                                        <div class="col-md-6">
                                            <label class="text-muted small d-block">Birth Place</label>
                                            <div class="fw-medium">{{ $player->birth_place }}</div>
                                        </div>
                                    @endif

                                    @if($hasBio)
                                        <div class="col-12">
                                            <label class="text-muted small d-block">Bio</label>
                                            <div class="fw-medium">{{ Str::limit($player->biography, 150) }}</div>
                                        </div>
                                    @endif

                                    @if(!$hasBirthDate && !$hasBirthPlace && !$hasBio)
                                        <div class="col-12">
                                            <div class="text-muted small d-flex align-items-center gap-2 py-2">
                                                <i class="bi bi-info-circle"></i>
                                                Belum ada informasi detail (tanggal lahir, tempat lahir, bio) untuk pemain ini.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Event per Pertandingan - hanya tampil jika ada data -->
                @if(isset($matchEvents) && $matchEvents->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-4">
                                <i class="bi bi-journal-check me-2"></i>Riwayat Pertandingan
                                <span class="badge bg-primary bg-opacity-10 text-primary ms-1">{{ $matchEvents->count() }} event</span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-muted small fw-semibold" style="font-size: 0.75rem;">TANGGAL</th>
                                            <th class="text-muted small fw-semibold" style="font-size: 0.75rem;">PERTANDINGAN</th>
                                            <th class="text-muted small fw-semibold" style="font-size: 0.75rem;">TURNAMEN</th>
                                            <th class="text-muted small fw-semibold text-center" style="font-size: 0.75rem;">MENIT</th>
                                            <th class="text-muted small fw-semibold" style="font-size: 0.75rem;">EVENT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($matchEvents as $event)
                                            @php
                                                $match = $event->match;
                                                $eventMeta = match ($event->event_type) {
                                                    'goal' => ['label' => $event->is_penalty ? 'Goal (Penalti)' : 'Goal', 'class' => 'bg-success bg-opacity-10 text-success', 'icon' => 'bullseye'],
                                                    'yellow_card' => ['label' => 'Kartu Kuning', 'class' => 'bg-warning bg-opacity-10 text-warning', 'icon' => 'card-text'],
                                                    'red_card' => ['label' => 'Kartu Merah', 'class' => 'bg-danger bg-opacity-10 text-danger', 'icon' => 'card-text'],
                                                    default => ['label' => ucfirst($event->event_type), 'class' => 'bg-secondary bg-opacity-10 text-secondary', 'icon' => 'circle'],
                                                };
                                            @endphp
                                            <tr>
                                                <td class="small">{{ $match?->match_date?->format('d M Y') ?? '-' }}</td>
                                                <td class="small">
                                                    <strong>{{ $match?->homeTeam?->name ?? '?' }}</strong>
                                                    <span class="text-muted mx-1">{{ $match?->home_score ?? 0 }} - {{ $match?->away_score ?? 0 }}</span>
                                                    <strong>{{ $match?->awayTeam?->name ?? '?' }}</strong>
                                                </td>
                                                <td class="small text-muted">{{ $match?->tournament?->name ?? '-' }}</td>
                                                <td class="text-center small">{{ $event->minute }}'</td>
                                                <td>
                                                    <span class="badge {{ $eventMeta['class'] }} d-inline-flex align-items-center gap-1">
                                                        <i class="bi bi-{{ $eventMeta['icon'] }}"></i> {{ $eventMeta['label'] }}
                                                    </span>
                                                    @if($event->is_own_goal)
                                                        <span class="badge bg-dark bg-opacity-10 text-dark ms-1">Own Goal</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#updateStatsModal">
                                <i class="bi bi-plus-circle me-1"></i> Update Stats
                            </button>

                            <!-- @if($player->status == 'active')
                                    <form action="{{ route('admin.players.update', $player) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="inactive">
                                        <input type="hidden" name="update_type" value="status_only">
                                        <button type="submit" class="btn btn-outline-warning w-100"
                                            onclick="return confirm('Mark player as inactive?')">
                                            <i class="bi bi-pause-circle me-1"></i> Set Inactive
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.players.update', $player) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="active">
                                        <input type="hidden" name="update_type" value="status_only">
                                        <button type="submit" class="btn btn-outline-success w-100"
                                            onclick="return confirm('Mark player as active?')">
                                            <i class="bi bi-play-circle me-1"></i> Set Active
                                        </button>
                                    </form>
                                @endif -->

                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-1"></i> Delete Player
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CATATAN: Section Contact dihapus karena kolom email/phone/address
                     tidak ada di tabel players (data tidak pernah tersimpan) -->

                <!-- Timeline -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-clock-history me-2"></i>Activity</h6>
                        <div class="vstack gap-3">
                            <div class="d-flex">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                    <i class="bi bi-person-plus text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small fw-medium">Player Added</div>
                                    <div class="text-muted small">{{ $player->created_at->format('d M Y') }}</div>
                                </div>
                            </div>

                            @if($player->team)
                                <div class="d-flex">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                        <i class="bi bi-people text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-medium">Joined Team</div>
                                        <div class="text-muted small">{{ $player->team->name }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($player->goals > 0)
                                <div class="d-flex">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3">
                                        <i class="bi bi-trophy text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-medium">Goals Scored</div>
                                        <div class="text-muted small">{{ $player->goals }} total</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Stats Modal -->
    <div class="modal fade" id="updateStatsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold"><i class="bi bi-bar-chart me-2"></i>Update Stats</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.players.update', $player) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Goals</label>
                                <input type="number" name="goals" class="form-control form-control-sm"
                                    value="{{ $player->goals ?? 0 }}" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Assists</label>
                                <input type="number" name="assists" class="form-control form-control-sm"
                                    value="{{ $player->assists ?? 0 }}" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Yellow Cards</label>
                                <input type="number" name="yellow_cards" class="form-control form-control-sm"
                                    value="{{ $player->yellow_cards ?? 0 }}" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Red Cards</label>
                                <input type="number" name="red_cards" class="form-control form-control-sm"
                                    value="{{ $player->red_cards ?? 0 }}" min="0">
                            </div>
                            <input type="hidden" name="update_type" value="stats_only">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Delete
                        Player</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Delete <strong>{{ $player->name }}</strong>? This action cannot be undone.</p>
                    <div class="alert alert-warning small mb-3">
                        <i class="bi bi-info-circle me-1"></i> All player data will be permanently removed.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.players.destroy', $player) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .player-photo-container {
            position: relative;
            width: 150px;
            height: 150px;
        }

        .player-photo-large {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            overflow: hidden;
            border: 3px solid #e9ecef;
            background: #ffffff;
        }

        .player-photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .player-photo-fallback-large {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 600;
            border: 3px solid #e9ecef;
        }

        .team-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .team-logo-small {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            object-fit: cover;
        }

        .team-initial-small {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .stat-item {
            text-align: center;
            transition: all 0.2s;
        }

        .stat-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .text-purple {
            color: #8b5cf6;
        }

        .list-group-item {
            background: transparent;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .card {
            border-radius: 8px;
        }

        .modal-content {
            border-radius: 8px;
            border: none;
        }

        @media (max-width: 768px) {
            .player-photo-container {
                width: 120px;
                height: 120px;
            }

            .player-photo-fallback-large {
                font-size: 2rem;
            }

            .col-6 {
                padding: 0.25rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle photo loading errors
            document.querySelectorAll('.player-photo-img').forEach(img => {
                img.addEventListener('error', function () {
                    console.log('Player photo failed to load:', this.src);
                    this.style.display = 'none';
                    const fallback = this.nextElementSibling;
                    if (fallback && fallback.classList.contains('player-photo-fallback-large')) {
                        fallback.style.display = 'flex';
                    }
                });
            });

            // Handle team logo loading errors
            document.querySelectorAll('.team-logo-small').forEach(img => {
                img.addEventListener('error', function () {
                    console.log('Team logo failed to load:', this.src);
                    this.style.display = 'none';
                    const initial = this.nextElementSibling;
                    if (initial && initial.classList.contains('team-initial-small')) {
                        initial.style.display = 'flex';
                    }
                });
            });
        });
    </script>
@endsection