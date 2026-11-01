<details class="app-card home-court-usage" open>
    <summary class="app-card-header home-court-usage-header" aria-controls="homeCourtUsageList">
        <div>
            <h2 class="app-card-title">Tim dan penyewa dengan pemakaian terbanyak</h2>
            <p>Menghitung booking terkonfirmasi termasuk member yang sudah dicatat, serta sewa turnamen termasuk jeda. Jadwal pertandingan tidak dihitung.</p>
        </div>
        <span class="home-court-usage-header-actions">
            <span class="home-court-usage-period">Sejak awal</span>
            <span class="home-court-usage-toggle">
                <span class="home-court-usage-action-show">Tampilkan</span>
                <span class="home-court-usage-action-hide">Sembunyikan</span>
                <i class="bi bi-chevron-down" aria-hidden="true"></i>
            </span>
        </span>
    </summary>

    <ol class="home-court-usage-list" id="homeCourtUsageList">
        @foreach($topCourtUsers as $index => $user)
            @php
                $hours = intdiv((int) $user['total_minutes'], 60);
                $minutes = (int) $user['total_minutes'] % 60;
            @endphp
            <li class="home-court-usage-row">
                <span class="home-court-usage-rank" aria-label="Peringkat {{ $index + 1 }}">
                    {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                </span>

                <div class="home-court-usage-main">
                    <h3>{{ $user['team_name'] }}</h3>
                    <p>
                        @if($user['booking_count'] > 0)
                            {{ number_format((int) $user['booking_count']) }} booking terkonfirmasi
                        @endif
                        @if($user['booking_count'] > 0 && $user['rental_count'] > 0)
                            ,
                        @endif
                        @if($user['rental_count'] > 0)
                            {{ number_format((int) $user['rental_count']) }} sewa turnamen
                        @endif
                    </p>
                </div>

                <div class="home-court-usage-duration">
                    <span>Total durasi</span>
                    <strong>
                        @if($hours > 0)
                            {{ $hours }} jam
                        @endif
                        @if($minutes > 0)
                            {{ $minutes }} menit
                        @endif
                    </strong>
                </div>
            </li>
        @endforeach
    </ol>
</details>
