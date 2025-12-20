<!-- <div class="step-content" id="step3" style="display: {{ $currentStep == 3 ? 'block' : 'none' }};">
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-grid-3x3"></i> League Settings</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle"></i>
                <strong>League Tournament Configuration</strong><br>
                In a league tournament, all teams play against each other. Configure how the league will operate.
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="league_rounds" class="form-label">
                            <i class="bi bi-arrow-repeat"></i>
                            Number of Rounds
                            <span class="required">*</span>
                        </label>
                        <select class="form-select" id="league_rounds" name="league_rounds" required>
                            <option value="1" {{ old('league_rounds', $tournamentData['league_rounds'] ?? 1) == 1 ? 'selected' : '' }}>Single Round Robin</option>
                            <option value="2" {{ old('league_rounds', $tournamentData['league_rounds'] ?? 1) == 2 ? 'selected' : '' }}>Double Round Robin</option>
                        </select>
                        <div class="form-text">Each team plays every other team once or twice</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="league_standings_type" class="form-label">
                            <i class="bi bi-sort-numeric-down"></i>
                            Tiebreaker Method
                            <span class="required">*</span>
                        </label>
                        <select class="form-select" id="league_standings_type" name="league_standings_type" required>
                            <option value="total_points" {{ old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'total_points' ? 'selected' : '' }}>Total Points</option>
                            <option value="head_to_head" {{ old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'head_to_head' ? 'selected' : '' }}>Head-to-Head Results</option>
                            <option value="goal_difference" {{ old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'goal_difference' ? 'selected' : '' }}>Goal Difference</option>
                        </select>
                        <div class="form-text">How to rank teams with equal points</div>
                    </div>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="league_allow_draw" name="league_allow_draw" value="1" 
                       {{ old('league_allow_draw', $tournamentData['league_allow_draw'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="league_allow_draw">
                    Allow draws in league matches
                </label>
            </div>

            <div class="info-box">
                <i class="bi bi-calculator"></i>
                <p>
                    <strong>League Calculation:</strong><br>
                    Total matches: {{ count($tournamentData['teams'] ?? []) }} teams<br>
                    Single round: {{ $totalMatches = count($tournamentData['teams'] ?? []) * (count($tournamentData['teams'] ?? []) - 1) / 2 }} matches<br>
                    Double round: {{ $totalMatches * 2 }} matches
                </p>
            </div>
        </div>
    </div>
</div> -->