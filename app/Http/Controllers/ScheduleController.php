<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Tournament;
use App\Models\Match;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    // Generate schedule for a tournament
    public function generateSchedule(Tournament $tournament)
    {
        try {
            DB::beginTransaction();

            // Clear existing matches
            $tournament->matches()->delete();

            $teams = $tournament->teams()->withPivot(['group_name', 'seed'])->get();

            if ($tournament->type === 'group_knockout') {
                $this->generateGroupStageMatches($tournament, $teams);
                $this->generateKnockoutMatches($tournament, $teams);
            } elseif ($tournament->type === 'league') {
                $this->generateLeagueMatches($tournament, $teams);
            } elseif ($tournament->type === 'knockout') {
                $this->generateKnockoutMatches($tournament, $teams);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Schedule generated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to generate schedule: ' . $e->getMessage());
        }
    }

    // Generate group stage matches (round robin)
    private function generateGroupStageMatches(Tournament $tournament, $teams)
    {
        $groups = $teams->groupBy('pivot.group_name');
        $matchDate = $tournament->start_date;
        $matchesPerDay = $tournament->settings['matches_per_day'] ?? 4;
        $matchInterval = $tournament->settings['match_interval'] ?? 30;

        // Time slots from settings
        $timeSlots = $tournament->settings['match_time_slots'] ?? '14:00,16:00,18:00,20:00';
        $timeSlots = array_map('trim', explode(',', $timeSlots));

        $matchCounter = 0;
        $currentTimeSlot = 0;

        foreach ($groups as $groupName => $groupTeams) {
            $teamsInGroup = $groupTeams->toArray();
            $teamCount = count($teamsInGroup);

            // Generate round robin schedule
            for ($i = 0; $i < $teamCount - 1; $i++) {
                for ($j = $i + 1; $j < $teamCount; $j++) {
                    // Determine match date and time
                    if ($matchCounter % $matchesPerDay === 0 && $matchCounter > 0) {
                        $matchDate = date('Y-m-d', strtotime($matchDate . ' +1 day'));
                        $currentTimeSlot = 0;
                    }

                    $timeStart = $timeSlots[$currentTimeSlot % count($timeSlots)];
                    $timeEnd = date('H:i', strtotime($timeStart . ' +' . ($tournament->settings['match_duration'] ?? 40) . ' minutes'));

                    // Create match
                    MatchModel::create([
                        'tournament_id' => $tournament->id,
                        'match_date' => $matchDate,
                        'time_start' => $timeStart,
                        'time_start' => $timeEnd,
                        'team_home_id' => $teamsInGroup[$i]['id'],
                        'team_away_id' => $teamsInGroup[$j]['id'],
                        'venue' => $tournament->location,
                        'status' => 'upcoming',
                        'round_type' => 'group',
                        'group_name' => $groupName,
                        'notes' => "Group $groupName - Matchday " . (floor($matchCounter / $matchesPerDay) + 1)
                    ]);

                    $matchCounter++;
                    $currentTimeSlot++;
                }
            }
        }
    }

    // Generate knockout matches
    private function generateKnockoutMatches(Tournament $tournament, $teams)
    {
        if ($tournament->type === 'group_knockout') {
            // Get teams that qualify from groups
            $qualifyPerGroup = $tournament->qualify_per_group ?? 2;
            $groups = $teams->groupBy('pivot.group_name');
            $qualifiedTeams = [];

            foreach ($groups as $groupName => $groupTeams) {
                // Sort by seed (or points later)
                $sortedTeams = $groupTeams->sortBy('pivot.seed')->take($qualifyPerGroup);
                $qualifiedTeams = array_merge($qualifiedTeams, $sortedTeams->toArray());
            }

            $teamCount = count($qualifiedTeams);
            $rounds = $this->getKnockoutRounds($teamCount);

        } else {
            // For pure knockout tournament
            $qualifiedTeams = $teams->toArray();
            $teamCount = count($qualifiedTeams);
            $rounds = $this->getKnockoutRounds($teamCount);
        }

        // Generate knockout bracket
        $this->generateKnockoutBracket($tournament, $qualifiedTeams, $rounds);
    }

    // Generate league matches (full round robin)
    private function generateLeagueMatches(Tournament $tournament, $teams)
    {
        $teamArray = $teams->toArray();
        $teamCount = count($teamArray);
        $matchDate = $tournament->start_date;
        $matchesPerDay = $tournament->settings['matches_per_day'] ?? 4;

        $matchCounter = 0;

        for ($i = 0; $i < $teamCount - 1; $i++) {
            for ($j = $i + 1; $j < $teamCount; $j++) {
                // Determine match date
                if ($matchCounter % $matchesPerDay === 0 && $matchCounter > 0) {
                    $matchDate = date('Y-m-d', strtotime($matchDate . ' +1 day'));
                }

                // Create match
                MatchModel::create([
                    'tournament_id' => $tournament->id,
                    'match_date' => $matchDate,
                    'time_start' => '14:00', // Default time
                    'time_end' => '15:40', // Default time + duration
                    'team_home_id' => $teamArray[$i]['id'],
                    'team_away_id' => $teamArray[$j]['id'],
                    'venue' => $tournament->location,
                    'status' => 'upcoming',
                    'round_type' => 'group',
                    'group_name' => null, // No groups in league
                    'notes' => "Matchday " . (floor($matchCounter / $matchesPerDay) + 1)
                ]);

                $matchCounter++;
            }
        }
    }

    // Helper: Get knockout rounds based on team count
    private function getKnockoutRounds($teamCount)
    {
        $rounds = [];
        $currentRound = $teamCount;

        while ($currentRound > 1) {
            $rounds[] = $currentRound;
            $currentRound = ceil($currentRound / 2);
        }

        return $rounds;
    }

    // Helper: Generate knockout bracket matches
    private function generateKnockoutBracket($tournament, $teams, $rounds)
    {
        $matchDate = $tournament->end_date; // Start from near the end date
        $roundTypes = ['semifinal', 'final', 'third_place'];

        foreach ($rounds as $index => $matchesInRound) {
            $roundType = $roundTypes[$index] ?? 'knockout';

            for ($i = 0; $i < $matchesInRound; $i += 2) {
                if (isset($teams[$i]) && isset($teams[$i + 1])) {
                    MatchModel::create([
                        'tournament_id' => $tournament->id,
                        'match_date' => $matchDate,
                        'time_start' => '16:00',
                        'time_end' => '17:40',
                        'team_home_id' => null, // Will be determined by previous matches
                        'team_away_id' => null, // Will be determined by previous matches
                        'venue' => $tournament->location,
                        'status' => 'upcoming',
                        'round_type' => $roundType,
                        'group_name' => null,
                        'notes' => "$roundType match"
                    ]);
                }
            }

            // Move to next day for next round
            $matchDate = date('Y-m-d', strtotime($matchDate . ' -1 day'));
        }
    }
}