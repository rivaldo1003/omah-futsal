@extends('layouts.admin')

@section('title', 'Buat Turnamen')

@section('styles')
    @include('admin.tournaments.create._styles')
@endsection

@section('content')
    <div class="page-header">
            <div>
                <h1>Buat Turnamen</h1>
                <p class="page-subtitle">Siapkan turnamen baru dengan tim, aturan, dan pengaturan pertandingan</p>
            </div>
            <a href="{{ route('admin.tournaments.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Step indicator -->

    @include('admin.tournaments.create._step-indicator')

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.tournaments.store.step', ['step' => $currentStep ?? $step ?? 1]) }}"
                    method="POST" id="tournamentForm" class="multi-step-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="current_step" value="{{ $currentStep }}">
                    <!-- <input type="hidden" name="form_action" id="formAction" value="{{ $currentStep < 5 ? 'next' : 'create' }}"> -->

            @include('admin.tournaments.create._step1-basic')

            @include('admin.tournaments.create._step2-teams')

            @include('admin.tournaments.create._step3-group')

            @include('admin.tournaments.create._step4-rules')

            @include('admin.tournaments.create._step5-review')

            <!-- Navigation Buttons -->
                    <div class="step-navigation">
                        <button type="button" class="step-btn prev" id="prevBtn"
                            style="display: {{ $currentStep > 1 ? 'inline-flex' : 'none' }};"
                            onclick="goToPreviousStep()">
                            <i class="bi bi-arrow-left"></i>
                            <span>Sebelumnya</span>
                        </button>

                    <!-- Di bagian akhir form, ganti button create dengan ini -->
                    @if($currentStep == 5)
                        <button type="submit" class="btn btn-primary btn-lg" id="createBtn" name="form_action" value="create">
                            <i class="bi bi-check-circle"></i>
                            <span>Buat Turnamen</span>
                        </button>
                    @else
                        <button type="submit" class="step-btn next" id="nextBtn" name="form_action" value="next">
                            <i class="bi bi-arrow-right"></i>
                            <span>Next:
                                @if($currentStep == 1) Pilih Tim
                                @elseif($currentStep == 2) 
                                    @php
                                        $type = old('type', $tournamentData['type'] ?? 'group_knockout');
                                        $step3Labels = [
                                            'league' => 'Setup League',
                                            'knockout' => 'Setup Bracket',
                                            'group_knockout' => 'Groups'
                                        ];
                                    @endphp
                                    {{ $step3Labels[$type] ?? 'Setup' }}
                                @elseif($currentStep == 3) Match Rules
                                @elseif($currentStep == 4) Review
                                @endif
                            </span>
                        </button>
                    @endif
                    </div>
                </form>
        </div>

        <div class="col-lg-4">
            @include('admin.tournaments.create._sidebar-preview')
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.tournaments.create._scripts')
@endsection
