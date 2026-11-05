@props([
    'status' => 'available',
    'timeLabel',
    'dateLabel' => null,
    'disabled' => false,
])

<button type="button" {{ $attributes->class(['slot-button', $status]) }} @disabled($disabled)>
    <span class="slot-pitch-markings" aria-hidden="true">
        <span class="slot-pitch-boundary"></span>
        <span class="slot-pitch-midline"></span>
        <span class="slot-pitch-center-circle"></span>
        <span class="slot-pitch-penalty-area slot-pitch-penalty-area--left"></span>
        <span class="slot-pitch-penalty-area slot-pitch-penalty-area--right"></span>
    </span>
    @if($dateLabel)
        <span class="slot-button-date">{{ $dateLabel }}</span>
    @endif
    <strong class="slot-button-time">{{ $timeLabel }}</strong>
    <span class="slot-button-copy walk-in-slot-copy">{{ $slot }}</span>
</button>

</write_to_file>