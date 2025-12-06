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
                                                <div class="d-flex align-items-center">
                                                    <div class="text-end" style="width: 120px;">
                                                        <div class="team-name">{{ $match->homeTeam->name ?? 'TBD' }}</div>
                                                        <small class="text-muted">Home</small>
                                                    </div>
                                                    <div class="text-center mx-3" style="width: 80px;">
                                                        <div class="match-score {{ $match->status }}">
                                                            @if($match->status == 'completed')
                                                                {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                                                            @elseif($match->status == 'ongoing')
                                                                <span class="live-indicator">LIVE</span>
                                                            @else
                                                                VS
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="text-start" style="width: 120px;">
                                                        <div class="team-name">{{ $match->awayTeam->name ?? 'TBD' }}</div>
                                                        <small class="text-muted">Away</small>
                                                    </div>
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

@push('styles')
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
@endpush