{{-- Tournament Info Banner (Active / Selected Tournament or Friendly) --}}
@if(isset($selectedTournament) && $selectedTournament)
    <div class="schedule-info-banner">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <div>
                <div class="schedule-info-title">{{ $selectedTournament->name }}</div>
                <div class="schedule-info-meta">
                    @if($selectedTournament->start_date && $selectedTournament->end_date)
                        <span>
                            <i class="bi bi-calendar"></i>
                            {{ \Carbon\Carbon::parse($selectedTournament->start_date)->format('F d, Y') }} - 
                            {{ \Carbon\Carbon::parse($selectedTournament->end_date)->format('F d, Y') }}
                        </span>
                    @endif
                    <span>
                        <i class="bi bi-geo-alt"></i>
                        {{ $selectedTournament->location ?? 'Venue TBD' }}
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                @php
                    $statusClass = $selectedTournament->status == 'ongoing' ? 'status-badge-ongoing' : ($selectedTournament->status == 'upcoming' ? 'status-badge-upcoming' : 'status-badge-completed');
                @endphp
                <span class="schedule-status-badge {{ $statusClass }}">
                    {{ ucfirst($selectedTournament->status) }}
                </span>
                @if($selectedTournament->type)
                    <span class="schedule-round-badge">
                        {{ ucfirst(str_replace('_', ' ', $selectedTournament->type)) }}
                    </span>
                @endif
            </div>
        </div>
    </div>
@elseif($selectedFriendly ?? false)
    <div class="schedule-info-banner">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <div>
                <div class="schedule-info-title">Friendly matches (Ujicoba)</div>
                <div class="schedule-info-meta">
                    <span>
                        <i class="bi bi-info-circle"></i>
                        Pertandingan persahabatan di luar turnamen resmi.
                    </span>
                </div>
            </div>

            <div>
                <span class="schedule-status-badge status-badge-completed">Friendly</span>
            </div>
        </div>
    </div>
@endif
