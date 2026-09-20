@extends('layouts.admin')

@section('content')<div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Matches Management</h1>
                            <p class="text-muted mb-0">Manage and schedule tournament matches</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                            <a href="{{ route('admin.matches.create') }}" class="btn btn-primary ms-2">
                                <i class="bi bi-plus-circle"></i> Add Match
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Matches Table -->
        <div class="row">
            <div class="col-12">
                <div class="matches-table-container">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5><i class="bi bi-calendar-event me-2"></i> All Matches</h5>
                        <div>
                            <span class="badge bg-light text-dark">{{ $matches->total() }} matches</span>
                        </div>
                    </div>

                    @if($matches->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Match</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matches as $match)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($match->match_date)->format('M d, Y') }}
                                            </td>
                                            <td>{{ $match->time_start }}</td>
                                            <td>
                                                <div class="match-info">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <!-- Home Team -->
                                                        <div class="text-end" style="width: 45%;">
                                                            <strong>{{ $match->homeTeam->name ?? 'TBD' }}</strong>
                                                        </div>

                                                        <!-- Score Box -->
                                                        <div class="text-center" style="width: 10%;">
                                                            @if($match->status == 'completed')
                                                                <div class="final-score">
                                                                    {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                                                                </div>
                                                            @elseif($match->status == 'ongoing')
                                                                <span class="live-badge">LIVE</span>
                                                            @else
                                                                <span class="vs-text">VS</span>
                                                            @endif
                                                        </div>

                                                        <!-- Away Team -->
                                                        <div class="text-start" style="width: 45%;">
                                                            <strong>{{ $match->awayTeam->name ?? 'TBD' }}</strong>
                                                        </div>
                                                    </div>

                                                    <!-- Extra Info (only for completed matches) -->
                                                    @if($match->status == 'completed')
                                                        <div class="extra-score-info text-center">
                                                            @if($match->et_score)
                                                                <span class="et-badge">
                                                                    ET {{ $match->et_score }}
                                                                </span>
                                                            @endif

                                                            @if($match->is_penalty && $match->penalty_score)
                                                                <span class="penalty-badge">
                                                                    <i class="bi bi-flag"></i> Pen. {{ $match->penalty_score }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $match->venue ?? 'Main Court' }}</td>
                                            <td>
                                                @php
                                                    $statusClass = '';
                                                    switch ($match->status) {
                                                        case 'completed':
                                                            $statusClass = 'bg-success';
                                                            break;
                                                        case 'ongoing':
                                                            $statusClass = 'bg-danger';
                                                            break;
                                                        case 'upcoming':
                                                            $statusClass = 'bg-warning';
                                                            break;
                                                        case 'postponed':
                                                            $statusClass = 'bg-secondary';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-info';
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusClass }}">
                                                    {{ ucfirst($match->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.matches.show', $match) }}" class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.matches.edit', $match) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('admin.matches.events.index', $match) }}"
                                                        class="btn btn-sm btn-outline-info btn-icon"
                                                        title="Manage Events & Statistics" data-bs-toggle="tooltip">
                                                        <i class="bi bi-activity"></i>
                                                    </a>
                                                    <form action="{{ route('admin.matches.destroy', $match) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
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

                        <!-- Pagination -->
                        @if($matches->hasPages())
                            <div class="d-flex justify-content-center py-4">
                                {{ $matches->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; color: #ccc;"></i>
                            <h4 class="mt-3">No Matches Found</h4>
                            <p class="text-muted">Start by scheduling your first match</p>
                            <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Schedule First Match
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .page-header {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .matches-table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
        }

        .team-name {
            font-weight: 600;
        }

        .match-score {
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #dee2e6;
        }

        .final-score {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            display: inline-block;
            min-width: 60px;
        }

        .live-badge {
            background: #dc3545;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                opacity: 1;
            }
        }

        .vs-text {
            color: #6c757d;
            font-weight: 500;
        }

        .extra-score-info {
            margin-top: 4px;
            font-size: 0.75rem;
        }

        .et-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 2px 6px;
            border-radius: 3px;
            margin-right: 5px;
            display: inline-block;
        }

        .penalty-badge {
            background: #fff3e0;
            color: #ff9800;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
            display: inline-block;
        }

        .penalty-badge i {
            margin-right: 2px;
        }

        .live-indicator {
            color: #dc3545;
            font-weight: bold;
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
@endsection
