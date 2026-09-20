<div class="step-indicator">
            <div class="step {{ $currentStep == 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Info Dasar</div>
            </div>
            <div class="step {{ $currentStep == 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Tim</div>
            </div>
            
            <div class="step {{ $currentStep == 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label" id="step3Label">
                    @php
                        $tournamentType = old('type', $tournamentData['type'] ?? 'group_knockout');
                        $step3Labels = [
                            'league' => 'Setup League',
                            'knockout' => 'Setup Bracket',
                            'group_knockout' => 'Groups'
                        ];
                        echo $step3Labels[$tournamentType] ?? 'Groups';
                    @endphp
                </div>
            </div>
            
            <div class="step {{ $currentStep == 4 ? 'active' : '' }} {{ $currentStep > 4 ? 'completed' : '' }}" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Aturan Pertandingan</div>
            </div>
            <div class="step {{ $currentStep == 5 ? 'active' : '' }}" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Review</div>
            </div>
        </div>
