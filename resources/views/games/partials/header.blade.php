{{-- Page Header: Breadcrumbs + Title + Tournament Selector Card --}}
<nav aria-label="breadcrumb">
    <ol class="schedule-breadcrumb">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li class="active" aria-current="page">Match schedule</li>
    </ol>
</nav>

<div class="schedule-page-header">
    <h1>
        <i class="bi bi-calendar2-week"></i>
        Match schedule
    </h1>
    <p>View and filter matches by tournament, date, and status</p>
</div>

<div class="schedule-selector-card">
    <div class="row align-items-center g-3">
        <div class="col-md-7 col-lg-8">
            <div class="schedule-selector-label">Tournament filter</div>
            <form action="{{ route('schedule') }}" method="GET" id="tournamentSelectForm">
                {{-- Preserve other query params if needed --}}
                @if(request('status') && request('status') !== 'all')
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('date'))
                    <input type="hidden" name="date" value="{{ request('date') }}">
                @endif
                <select name="tournament" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ !request('tournament') || request('tournament') == 'all' ? 'selected' : '' }}>
                        All tournaments
                    </option>
                    <option value="friendly" {{ request('tournament') == 'friendly' ? 'selected' : '' }}>
                        Friendly matches
                    </option>
                    @if(isset($allTournaments) && $allTournaments->count() > 0)
                        @foreach($allTournaments as $tournament)
                            @php
                                $statusLabel = $tournament->status == 'ongoing' ? 'Active' : ($tournament->status == 'upcoming' ? 'Upcoming' : 'Completed');
                            @endphp
                            <option value="{{ $tournament->id }}"
                                {{ request('tournament') == $tournament->id || (!request('tournament') && isset($activeTournament) && $activeTournament && $activeTournament->id == $tournament->id) ? 'selected' : '' }}>
                                {{ $tournament->name }} ({{ $statusLabel }})
                            </option>
                        @endforeach
                    @endif
                </select>
            </form>
        </div>

        <div class="col-md-5 col-lg-4 text-md-end">
            @if($selectedFriendly ?? false)
                <div class="d-inline-block text-md-end">
                    <span class="schedule-badge-chip schedule-badge-friendly">
                        <i class="bi bi-people-fill"></i>
                        Friendly matches
                    </span>
                    <div class="text-secondary small mt-1">Non-tournament matches</div>
                </div>
            @elseif(isset($selectedTournament) && $selectedTournament)
                <div class="d-inline-block text-md-end">
                    <span class="schedule-badge-chip schedule-badge-accent">
                        <i class="bi bi-trophy-fill"></i>
                        {{ $selectedTournament->name }}
                    </span>
                    @if($selectedTournament->start_date && $selectedTournament->end_date)
                        <div class="text-secondary small mt-1">
                            {{ \Carbon\Carbon::parse($selectedTournament->start_date)->format('M d') }} - 
                            {{ \Carbon\Carbon::parse($selectedTournament->end_date)->format('M d, Y') }}
                        </div>
                    @endif
                </div>
            @else
                <div class="d-inline-block text-md-end">
                    <span class="schedule-badge-chip schedule-badge-accent">
                        <i class="bi bi-collection"></i>
                        All matches
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
