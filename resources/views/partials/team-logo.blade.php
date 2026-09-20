{{--
    Logo tim dengan fallback inisial.
    Usage: @include('partials.team-logo', ['team' => $team, 'class' => 'team-logo-small'])
--}}
@php
    $logoClass = $class ?? 'team-logo-small';
@endphp
<div class="{{ $logoClass }}">
    @if($team && $team->logo)
        @if(Storage::disk('public')->exists($team->logo))
            <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}">
        @elseif(filter_var($team->logo, FILTER_VALIDATE_URL))
            <img src="{{ $team->logo }}" alt="{{ $team->name }}">
        @else
            <div class="team-initial">{{ strtoupper(substr($team->name, 0, 1)) }}</div>
        @endif
    @else
        <div class="team-initial">{{ strtoupper(substr($team->name ?? 'T', 0, 1)) }}</div>
    @endif
</div>