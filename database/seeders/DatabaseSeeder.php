<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama (use query()->delete() karena truncate() fail dengan foreign key constraints)
        MatchEvent::query()->delete();
        Game::query()->delete();
        Player::query()->delete();
        Team::query()->delete();
        Tournament::query()->delete();
        Standing::query()->delete();
        User::query()->delete();

        // Buat user admin
        User::create([
            'name' => 'Admin Omah Futsal',
            'email' => 'admin@omahfutsal.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat turnamen
        $tournament = Tournament::create([
            'name' => 'Liga Futsal Surabaya 2024',
            'slug' => 'liga-futsal-surabaya-2024',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'status' => 'ongoing',
            'type' => 'league',
            'description' => 'Turnamen futsal tahunan di Surabaya',
        ]);

        // Buat tim-tim
        $teams = [
            [
                'name' => 'Surabaya Warriors',
                'coach_name' => 'Ahmad Santoso',
            ],
            [
                'name' => 'East Java Falcons',
                'coach_name' => 'Budi Hartono',
            ],
            [
                'name' => 'Sidoarjo Strikers',
                'coach_name' => 'Cahyo Putra',
            ],
            [
                'name' => 'Gresik Titans',
                'coach_name' => 'Dedi Setiawan',
            ],
            [
                'name' => 'Mojokerto Legends',
                'coach_name' => 'Eko Prasetyo',
            ],
            [
                'name' => 'Lamongan Eagles',
                'coach_name' => 'Fajar Nugroho',
            ],
        ];

        $createdTeams = [];
        foreach ($teams as $teamData) {
            $teamData['tournament_id'] = $tournament->id;
            $createdTeams[] = Team::create($teamData);
        }

        // Buat pemain untuk setiap tim
        $playerNames = [
            'Rizki Ramadhan',
            'Andi Wijaya',
            'Bambang Surya',
            'Cahyo Adi',
            'Doni Prasetyo',
            'Eko Santoso',
            'Fajar Hidayat',
            'Guntur Wibowo',
            'Hendra Kusuma',
            'Indra Setiawan',
            'Joko Susilo',
            'Kurniawan',
            'Lukman Hakim',
            'Mulyadi',
            'Nugroho',
            'Oki Setiawan',
        ];

        $positions = ['Kiper', 'Anchor', 'Flank', 'Pivot', 'Universal'];

        $allPlayers = [];

        foreach ($createdTeams as $team) {
            $teamPlayers = [];
            for ($i = 1; $i <= 12; $i++) {
                $player = Player::create([
                    'team_id' => $team->id,
                    'name' => $playerNames[array_rand($playerNames)] . ' ' . $team->name,
                    'jersey_number' => $i,
                    'position' => $positions[array_rand($positions)],
                    'goals' => rand(0, 20),
                    'assists' => rand(0, 15),
                    'yellow_cards' => rand(0, 5),
                    'red_cards' => rand(0, 2),
                ]);
                $teamPlayers[] = $player;
                $allPlayers[] = $player;
            }
        }

        // Buat pertandingan
        $matches = [
            [
                'team_home_id' => $createdTeams[0]->id,
                'team_away_id' => $createdTeams[1]->id,
                'tournament_id' => $tournament->id,
                'match_date' => '2024-10-15',
                'time_start' => '18:00:00',
                'time_end' => '19:30:00',
                'home_score' => 3,
                'away_score' => 2,
                'venue' => 'Lapangan Utama Omah Futsal',
                'status' => 'completed',
                'round_type' => 'league',
            ],
            [
                'team_home_id' => $createdTeams[2]->id,
                'team_away_id' => $createdTeams[3]->id,
                'tournament_id' => $tournament->id,
                'match_date' => '2024-10-15',
                'time_start' => '20:00:00',
                'time_end' => '21:30:00',
                'home_score' => 1,
                'away_score' => 1,
                'venue' => 'Lapangan Utama Omah Futsal',
                'status' => 'completed',
                'round_type' => 'league',
            ],
            [
                'team_home_id' => $createdTeams[4]->id,
                'team_away_id' => $createdTeams[5]->id,
                'tournament_id' => $tournament->id,
                'match_date' => '2024-10-22',
                'time_start' => '18:00:00',
                'time_end' => '19:30:00',
                'home_score' => 0,
                'away_score' => 0,
                'venue' => 'Lapangan Sintetik Surabaya',
                'status' => 'upcoming',
                'round_type' => 'league',
            ],
        ];

        $createdMatches = [];
        foreach ($matches as $matchData) {
            $createdMatches[] = Game::create($matchData);
        }

        // Buat beberapa gol dan kartu untuk match pertama
        if (count($createdMatches) > 0 && count($allPlayers) > 0) {
            $firstMatch = $createdMatches[0];
            $homePlayers = array_slice($allPlayers, 0, 5);
            $awayPlayers = array_slice($allPlayers, 6, 5);

            // Gol untuk match pertama
            MatchEvent::create([
                'match_id' => $firstMatch->id,
                'player_id' => $homePlayers[0]->id,
                'team_id' => $firstMatch->team_home_id,
                'event_type' => 'goal',
                'minute' => 12,
                'description' => 'Gol normal',
            ]);

            MatchEvent::create([
                'match_id' => $firstMatch->id,
                'player_id' => $homePlayers[2]->id,
                'team_id' => $firstMatch->team_home_id,
                'event_type' => 'goal',
                'minute' => 35,
                'is_penalty' => true,
                'description' => 'Gol penalti',
            ]);

            MatchEvent::create([
                'match_id' => $firstMatch->id,
                'player_id' => $awayPlayers[0]->id,
                'team_id' => $firstMatch->team_away_id,
                'event_type' => 'goal',
                'minute' => 28,
                'description' => 'Gol free kick',
            ]);

            MatchEvent::create([
                'match_id' => $firstMatch->id,
                'player_id' => $awayPlayers[1]->id,
                'team_id' => $firstMatch->team_away_id,
                'event_type' => 'goal',
                'minute' => 42,
                'related_player_id' => $awayPlayers[2]->id,
                'description' => 'Gol dengan assist',
            ]);

            MatchEvent::create([
                'match_id' => $firstMatch->id,
                'player_id' => $homePlayers[3]->id,
                'team_id' => $firstMatch->team_home_id,
                'event_type' => 'goal',
                'minute' => 67,
                'description' => 'Gol normal',
            ]);

            // Kartu untuk match pertama
            MatchEvent::create([
                'match_id' => $firstMatch->id,
                'player_id' => $homePlayers[4]->id,
                'team_id' => $firstMatch->team_home_id,
                'event_type' => 'yellow_card',
                'minute' => 23,
                'description' => 'Tackle keras',
            ]);

            MatchEvent::create([
                'match_id' => $firstMatch->id,
                'player_id' => $awayPlayers[3]->id,
                'team_id' => $firstMatch->team_away_id,
                'event_type' => 'red_card',
                'minute' => 78,
                'description' => 'Pelanggaran berbahaya',
            ]);
        }

        // Update statistik tim dan buat standings
        foreach ($createdTeams as $team) {
            $matches = Game::where(function ($query) use ($team) {
                $query->where('team_home_id', $team->id)
                    ->orWhere('team_away_id', $team->id);
            })->where('status', 'completed')->get();

            $won = 0;
            $drawn = 0;
            $lost = 0;
            $goals_for = 0;
            $goals_against = 0;
            $played = $matches->count();

            foreach ($matches as $match) {
                if ($match->team_home_id == $team->id) {
                    $goals_for += $match->home_score ?? 0;
                    $goals_against += $match->away_score ?? 0;

                    if ($match->home_score > $match->away_score) {
                        $won++;
                    } elseif ($match->home_score == $match->away_score) {
                        $drawn++;
                    } else {
                        $lost++;
                    }
                } else {
                    $goals_for += $match->away_score ?? 0;
                    $goals_against += $match->home_score ?? 0;

                    if ($match->away_score > $match->home_score) {
                        $won++;
                    } elseif ($match->away_score == $match->home_score) {
                        $drawn++;
                    } else {
                        $lost++;
                    }
                }
            }

            $points = ($won * 3) + $drawn;
            $goal_difference = $goals_for - $goals_against;

            // Buat standings
            Standing::create([
                'team_id' => $team->id,
                'tournament_id' => $tournament->id,
                'group_name' => 'A',
                'matches_played' => $played,
                'wins' => $won,
                'draws' => $drawn,
                'losses' => $lost,
                'goals_for' => $goals_for,
                'goals_against' => $goals_against,
                'points' => $points,
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin User: admin@omahfutsal.com / password');
        $this->command->info('Total Teams: ' . Team::count());
        $this->command->info('Total Players: ' . Player::count());
        $this->command->info('Total Matches: ' . Game::count());
        $this->command->info('Total Events: ' . MatchEvent::count());
    }
}