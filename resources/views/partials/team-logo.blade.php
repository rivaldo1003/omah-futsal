{{--
    Logo tim dengan fallback inisial.
    Usage: @include('partials.team-logo', ['team' => $team, 'class' => 'team-logo-small'])
--}}
@php
    $logoClass = $class ?? 'team-logo-small';
@endphp
<div class="{{ $logoClass }}">
    @if($team && $team->logo)
        {{-- Render the logo whenever it is set (URL or storage path). Avoids
             Storage::exists() false-negatives on servers where files live
             under public/storage but not storage/app/public. --}}
        @if(filter_var($team->logo, FILTER_VALIDATE_URL))
            <img src="{{ $team->logo }}" alt="{{ $team->name }}">
        @else
            <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}">
        @endif
    @else
    @endif
</div>