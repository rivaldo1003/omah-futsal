<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;

class TournamentDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Tournament
        $tournament = Tournament::create([
            'name' => 'Ofs Champions League 2025',
            'slug' => 'ofs-champions-league-2025',
            'description' => 'Turnamen futsal tahunan Ofs Champions League edisi 2025',
            'start_date' => '2025-12-07',
            'end_date' => '2026-01-11',
            'location' => 'Gor Ofs',
            'organizer' => 'OFs Sports Club',
            'status' => 'upcoming',
            'type' => 'group_knockout',
            'teams_per_group' => 5,
            'groups_count' => 2,
            'settings' => json_encode([
                'match_duration' => 50,
                'break_duration' => 5,
                'points_win' => 3,
                'points_draw' => 1,
                'points_loss' => 0,
            ])
        ]);

        // 2. Create Teams
        $teamsGrupA = [
            ['name' => 'BLUEMOSPHERE', 'coach_name' => 'Coach A'],
            ['name' => 'CASPER', 'coach_name' => 'Coach B'],
            ['name' => 'PAYUNG PUSAKA', 'coach_name' => 'Coach C'],
            ['name' => 'RONGGOLAWE', 'coach_name' => 'Coach D'],
            ['name' => 'ORI', 'coach_name' => 'Coach E'],
        ];

        $teamsGrupB = [
            ['name' => 'GOLD', 'coach_name' => 'Coach F'],
            ['name' => 'HAMTON', 'coach_name' => 'Coach G'],
            ['name' => 'SINGO LIAR', 'coach_name' => 'Coach H'],
            ['name' => 'ELANO', 'coach_name' => 'Coach I'],
            ['name' => 'KLIWON', 'coach_name' => 'Coach J'],
        ];

        // Create and attach teams to tournament
        $allTeams = [];
        $teamId = 1;

        // Group A Teams
        foreach ($teamsGrupA as $teamData) {
            $team = Team::create([
                'name' => $teamData['name'],
                'coach_name' => $teamData['coach_name'],
                'tournament_id' => $tournament->id
            ]);

            // Attach to tournament with group
            $tournament->teams()->attach($team->id, [
                'group_name' => 'A',
                'seed' => $teamId++
            ]);

            $allTeams[$team->name] = $team->id;
        }

        // Group B Teams
        foreach ($teamsGrupB as $teamData) {
            $team = Team::create([
                'name' => $teamData['name'],
                'coach_name' => $teamData['coach_name'],
                'tournament_id' => $tournament->id
            ]);

            // Attach to tournament with group
            $tournament->teams()->attach($team->id, [
                'group_name' => 'B',
                'seed' => $teamId++
            ]);

            $allTeams[$team->name] = $team->id;
        }

        // 3. Create Matches Schedule (sesuai jadwal Anda)
        $matchSchedules = [
            // 7 Desember 2025
            ['date' => '2025-12-07', 'time' => '09:00', 'home' => 'BLUEMOSPHERE', 'away' => 'CASPER', 'group' => 'A'],
            ['date' => '2025-12-07', 'time' => '09:55', 'home' => 'PAYUNG PUSAKA', 'away' => 'RONGGOLAWE', 'group' => 'A'],
            ['date' => '2025-12-07', 'time' => '10:50', 'home' => 'ORI', 'away' => 'GOLD', 'group' => null],
            ['date' => '2025-12-07', 'time' => '11:45', 'home' => 'HAMTON', 'away' => 'SINGO LIAR', 'group' => 'B'],

            // 14 Desember 2025
            ['date' => '2025-12-14', 'time' => '09:00', 'home' => 'CASPER', 'away' => 'ELANO', 'group' => null],
            ['date' => '2025-12-14', 'time' => '09:55', 'home' => 'BLUEMOSPHERE', 'away' => 'HAMTON', 'group' => null],
            ['date' => '2025-12-14', 'time' => '10:50', 'home' => 'GOLD', 'away' => 'KLIWON', 'group' => 'B'],
            ['date' => '2025-12-14', 'time' => '11:45', 'home' => 'ORI', 'away' => 'PAYUNG PUSAKA', 'group' => 'A'],

            // ... tambahkan jadwal lainnya sesuai kebutuhan
        ];

        foreach ($matchSchedules as $schedule) {
            $timeStart = Carbon::parse($schedule['time']);
            $timeEnd = $timeStart->copy()->addMinutes(50);

            Game::create([
                'tournament_id' => $tournament->id,
                'match_date' => $schedule['date'],
                'time_start' => $timeStart->format('H:i:s'),
                'time_end' => $timeEnd->format('H:i:s'),
                'team_home_id' => $allTeams[$schedule['home']],
                'team_away_id' => $allTeams[$schedule['away']],
                'venue' => 'Gor Ofs',
                'status' => 'upcoming',
                'round_type' => $schedule['group'] ? 'group' : 'intergroup',
                'group_name' => $schedule['group'],
            ]);
        }

        // 4. Create Admin User (jika belum)
        if (!User::where('email', 'admin@tournament.com')->exists()) {
            User::create([
                'name' => 'Admin Turnamen',
                'email' => 'admin@tournament.com',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true
            ]);
        }

        $this->command->info('✅ Tournament data created successfully!');
        $this->command->info('Tournament: ' . $tournament->name);
        $this->command->info('Teams: ' . Team::count() . ' teams created');
        $this->command->info('Matches: ' . Game::count() . ' matches scheduled');
    }
}