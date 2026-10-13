@php
    $crestClass = $class ?? 'team-crest';
    $crestLogo = $logo ?? null;
    $crestUrl = $crestLogo
        ? (filter_var($crestLogo, FILTER_VALIDATE_URL) ? $crestLogo : asset('storage/' . $crestLogo))
        : null;
@endphp

<span class="team-crest {{ $crestClass }}" aria-hidden="true">
    @if($crestUrl)
        <img src="{{ $crestUrl }}" alt="" loading="lazy">
    @else
        <i class="bi bi-shield-fill" aria-hidden="true"></i>
    @endif
</span>
