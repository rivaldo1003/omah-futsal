@php
    $openingMatches = $bracketEditor['matches'];
    $sources = $bracketEditor['sources'];
    $quota = (int) ($tournament->qualify_per_group ?? 2);
    $locked = $qualifierLocked || $tournament->status === 'completed';
    $round = match (count($openingMatches)) {
        1 => 'Final',
        2 => 'Semifinal',
        default => (count($openingMatches) * 2).' besar',
    };
    $slotIndex = 0;
@endphp

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
    <div>
        <h2 id="bracket-order" class="h5 mb-1" tabindex="-1">Susun bagan knockout</h2>
        <p class="text-muted mb-0">Pilih asal tim lolos untuk setiap posisi di babak pembuka. Pemenang maju otomatis ke babak berikutnya.</p>
    </div>
    @if($bracketEditor['configured'])
        <a class="btn btn-outline-secondary btn-sm"
            href="{{ route('standings', ['tournament_id' => $tournament->id]) }}#group-knockout-bracket-title">Lihat bagan knockout</a>
    @endif
</div>

@if(session('bracket_order_success'))
    <p class="alert alert-success" role="status">{{ session('bracket_order_success') }}</p>
@endif
@if($errors->bracketOrder->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0 ps-3">
            @foreach(array_unique($errors->bracketOrder->all()) as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if($locked)
    <p class="alert alert-warning">Susunan terkunci setelah laga knockout dimulai, memiliki hasil, atau turnamen selesai.</p>
@elseif(!$qualifierStageReady && !$bracketEditor['configured'] && !session('bracket_order_success'))
    <p class="alert alert-info">Anda bisa menyimpan susunan sekarang. Nama tim akan terisi setelah semua pertandingan grup selesai.</p>
@endif

<form action="{{ route('admin.tournaments.bracket-order.update', $tournament) }}" method="POST">
    @csrf
    @method('PUT')

    <fieldset @disabled($locked)>
        <legend class="h6">{{ $round }}</legend>
        <p class="small text-muted mb-3">{{ $bracketEditor['manual'] ? 'Susunan panitia' : 'Susunan otomatis' }}</p>

        <div class="row g-3">
            @foreach($openingMatches as $matchIndex => $sides)
                @php $fixture = $bracketEditor['fixtures']->get($matchIndex); @endphp
                <div class="col-12 col-xl-6">
                    <fieldset class="border rounded-3 p-3 h-100">
                        <legend class="float-none w-auto px-1 small fw-semibold mb-0">Posisi {{ $matchIndex + 1 }}</legend>
                        @if($fixture?->match_date)
                            <p class="small text-muted mb-2">
                                {{ $fixture->match_date->format('d/m/Y') }}
                                @if($fixture->time_start && $fixture->time_end)
                                    · {{ substr($fixture->time_start, 0, 5) }}–{{ substr($fixture->time_end, 0, 5) }}
                                @endif
                                @if($fixture->venue)
                                    · {{ $fixture->venue }}
                                @endif
                            </p>
                        @endif
                        <div class="row g-2">
                            @foreach($sides as $side => $qualifier)
                                @php
                                    $field = 'bracket-slot-'.$matchIndex.'-'.$side;
                                    $label = $side === 'home' ? 'Tim pertama' : 'Tim kedua';
                                @endphp
                                <div class="col-12 col-sm-6">
                                    <label class="form-label small mb-1" for="{{ $field }}">{{ $label }}</label>
                                    @if($qualifier === null)
                                        <input id="{{ $field }}" class="form-control" value="BYE (langsung maju)" readonly>
                                    @else
                                        @php
                                            $selected = old('slots.'.$slotIndex, $qualifier['source']);
                                            $slotIndex++;
                                        @endphp
                                        <select id="{{ $field }}" class="form-select" name="slots[]" required>
                                            @foreach($sources as $sourceKey => $source)
                                                <option value="{{ $sourceKey }}" @selected($selected === $sourceKey)>
                                                    @if($quota === 1)
                                                        Tim lolos Grup {{ $source['group_name'] }}
                                                    @else
                                                        Tim lolos ke-{{ $source['group_position'] }} Grup {{ $source['group_name'] }}
                                                    @endif
                                                    @if(!empty($source['team_name'])) · {{ $source['team_name'] }} @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if(count($openingMatches) === 4)
                            <p class="small text-muted mb-0 mt-2">Pemenang maju ke semifinal {{ intdiv($matchIndex, 2) + 1 }}.</p>
                        @endif
                    </fieldset>
                </div>
            @endforeach
        </div>

        @unless($locked)
            <p class="small text-muted mt-3 mb-2">Tanggal, jam, dan lapangan tetap mengikuti posisi pertandingan yang sudah dijadwalkan.</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button type="submit" class="btn btn-primary" name="mode" value="manual">Simpan susunan bagan</button>
                @if($quota === 1 && count($sources) % 2 === 0)
                    <button type="button" class="btn btn-outline-secondary" data-bracket-sequential>Pasangkan grup berurutan</button>
                @endif
                @if($bracketEditor['manual'])
                    <button type="submit" class="btn btn-outline-secondary" name="mode" value="automatic" formnovalidate>Gunakan susunan otomatis</button>
                @endif
            </div>
        @endunless
    </fieldset>
</form>
