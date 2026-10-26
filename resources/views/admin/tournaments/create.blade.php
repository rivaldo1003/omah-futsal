@extends('layouts.admin')

@section('title', 'Buat Turnamen')

@section('styles')
    @include('admin.tournaments.create._styles')
@endsection

@section('content')
    <div class="admin-tournament-create" id="adminTournamentCreate">
        <header class="page-header">
            <div class="page-heading">
                <h1>Buat turnamen</h1>
                <p class="page-subtitle">Atur informasi, peserta, format, dan aturan pertandingan dalam lima langkah.</p>
            </div>
            <a href="{{ route('admin.tournaments.index') }}" class="btn-back">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                <span>Kembali ke turnamen</span>
            </a>
        </header>

        <nav class="wizard-progress" aria-label="Langkah pembuatan turnamen">
            @include('admin.tournaments.create._step-indicator')
        </nav>

        <div class="row g-4 tournament-workspace">
            <div class="col-lg-8 tournament-form-column">
                <form action="{{ route('admin.tournaments.store.step', ['step' => $currentStep ?? $step ?? 1]) }}"
                    method="POST" id="tournamentForm" class="multi-step-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="current_step" value="{{ $currentStep }}">

                    @include('admin.tournaments.create._step1-basic')

                    @include('admin.tournaments.create._step2-teams')

                    @include('admin.tournaments.create._step3-group')

                    @include('admin.tournaments.create._step4-rules')

                    @include('admin.tournaments.create._step5-review')

                    @php
                        $prevType = old('type', $tournamentData['type'] ?? 'group_knockout');
                        $prevSkipStep3 = in_array($prevType, ['knockout', 'league']);
                        if ($currentStep == 4 && $prevSkipStep3) {
                            $prevStep = 2;
                        } else {
                            $prevStep = max(1, $currentStep - 1);
                        }
                    @endphp
                    <div class="step-navigation" aria-label="Navigasi langkah">
                        <a href="{{ route('admin.tournaments.create.step', ['step' => $prevStep]) }}"
                            class="step-btn prev" id="prevBtn"
                            style="display: {{ $currentStep > 1 ? 'inline-flex' : 'none' }}; text-decoration: none;">
                            <i class="bi bi-arrow-left" aria-hidden="true"></i>
                            <span>Sebelumnya</span>
                        </a>

                        @if($currentStep == 5)
                            <button type="submit" class="btn btn-primary btn-lg" id="createBtn" name="form_action" value="create">
                                <i class="bi bi-check-lg" aria-hidden="true"></i>
                                <span>Buat turnamen</span>
                            </button>
                        @else
                            <button type="submit" class="step-btn next" id="nextBtn" name="form_action" value="next">
                                <span>
                                    Lanjut:
                                    @if($currentStep == 1) Pilih tim
                                    @elseif($currentStep == 2)
                                        @php
                                            $type = old('type', $tournamentData['type'] ?? 'group_knockout');
                                            $step3Labels = [
                                                'league' => 'Pengaturan liga',
                                                'knockout' => 'Pengaturan bagan',
                                                'group_knockout' => 'Pengaturan grup',
                                            ];
                                        @endphp
                                        {{ $step3Labels[$type] ?? 'Pengaturan format' }}
                                    @elseif($currentStep == 3) Aturan pertandingan
                                    @elseif($currentStep == 4) Tinjau turnamen
                                    @endif
                                </span>
                                <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <aside class="col-lg-4 tournament-summary-column" aria-label="Ringkasan turnamen">
                @include('admin.tournaments.create._sidebar-preview')
            </aside>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.tournaments.create._scripts')
@endsection
