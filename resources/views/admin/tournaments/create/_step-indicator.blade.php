<div class="step-indicator">
            <div class="step {{ $currentStep == 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}" data-step="1" @if($currentStep == 1) aria-current="step" @endif>
                <div class="step-circle">1</div>
                <div class="step-label">Info</div>
            </div>
            <div class="step {{ $currentStep == 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}" data-step="2" @if($currentStep == 2) aria-current="step" @endif>
                <div class="step-circle">2</div>
                <div class="step-label">Tim</div>
            </div>
            
            <div class="step {{ $currentStep == 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}" data-step="3" @if($currentStep == 3) aria-current="step" @endif>
                <div class="step-circle">3</div>
                <div class="step-label" id="step3Label">
                    @php
                        $tournamentType = old('type', $tournamentData['type'] ?? 'group_knockout');
                        $step3Labels = [
                            'league' => 'Liga',
                            'knockout' => 'Bagan',
                            'group_knockout' => 'Grup'
                        ];
                        echo $step3Labels[$tournamentType] ?? 'Pengaturan grup';
                    @endphp
                </div>
            </div>
            
            <div class="step {{ $currentStep == 4 ? 'active' : '' }} {{ $currentStep > 4 ? 'completed' : '' }}" data-step="4" @if($currentStep == 4) aria-current="step" @endif>
                <div class="step-circle">4</div>
                <div class="step-label">Aturan</div>
            </div>
            <div class="step {{ $currentStep == 5 ? 'active' : '' }}" data-step="5" @if($currentStep == 5) aria-current="step" @endif>
                <div class="step-circle">5</div>
                <div class="step-label">Tinjau</div>
            </div>
        </div>
