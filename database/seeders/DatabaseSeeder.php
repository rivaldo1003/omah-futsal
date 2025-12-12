<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Goal;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        Goal::truncate();
        Card::truncate();
        MatchModel::truncate();
        Player::truncate();
        Team::truncate();
        Venue::truncate();
        Tournament::truncate();
        Standing::truncate();

        // Buat user admin
        User::create([
            'name' => 'Admin Omah Futsal',
            'email' => 'admin@omahfutsal.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Buat venue (tempat)
        $venues = [
            [
                'name' => 'Lapangan Utama Omah Futsal',
                'address' => 'Jl. Raya Futsal No. 123',
                'city' => 'Surabaya',
                'phone' => '031-5678901',
                'capacity' => 500,
                'facilities' => 'AC, Ruang Ganti, Toilet, Kantin',
                'is_active' => true,
            ],
            [
                'name' => 'Lapangan Sintetik Surabaya',
                'address' => 'Jl. Olahraga No. 45',
                'city' => 'Surabaya',
                'phone' => '031-5678902',
                'capacity' => 300,
                'facilities' => 'Lighting, Ruang Ganti',
                'is_active' => true,
            ],
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }

        // Buat turnamen
        $tournament = Tournament::create([
            'name' => 'Liga Futsal Surabaya 2024',
            'season' => '2024',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'status' => 'Ongoing',
            'total_teams' => 6,
            'description' => 'Turnamen futsal tahunan di Surabaya',
        ]);

        // Buat tim-tim
        $teams = [
            [
                'name' => 'Surabaya Warriors',
                'coach' => 'Ahmad Santoso',
                'primary_color' => '#1e3a8a',
                'secondary_color' => '#3b82f6',
                'description' => 'Tim kuat dari Surabaya Barat',
            ],
            [
                'name' => 'East Java Falcons',
                'coach' => 'Budi Hartono',
                'primary_color' => '#dc2626',
                'secondary_color' => '#ef4444',
                'description' => 'Tim berani dari Jawa Timur',
            ],
            [
                'name' => 'Sidoarjo Strikers',
                'coach' => 'Cahyo Putra',
                'primary_color' => '#059669',
                'secondary_color' => '#10b981',
                'description' => 'Tim cepat dari Sidoarjo',
            ],
            [
                'name' => 'Gresik Titans',
                'coach' => 'Dedi Setiawan',
                'primary_color' => '#7c3aed',
                'secondary_color' => '#8b5cf6',
                'description' => 'Tim kuat dari Gresik',
            ],
            [
                'name' => 'Mojokerto Legends',
                'coach' => 'Eko Prasetyo',
                'primary_color' => '#ea580c',
                'secondary_color' => '#f97316',
                'description' => 'Tim legendaris dari Mojokerto',
            ],
            [
                'name' => 'Lamongan Eagles',
                'coach' => 'Fajar Nugroho',
                'primary_color' => '#0ea5e9',
                'secondary_color' => '#38bdf8',
                'description' => 'Tim tangguh dari Lamongan',
            ],
        ];

        $createdTeams = [];
        foreach ($teams as $teamData) {
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
                    'name' => $playerNames[array_rand($playerNames)].' '.$team->name,
                    'jersey_number' => $i,
                    'position' => $positions[array_rand($positions)],
                    'birth_date' => fake()->dateTimeBetween('-30 years', '-18 years')->format('Y-m-d'),
                    'height' => rand(160, 185),
                    'weight' => rand(60, 85),
                    'total_goals' => rand(0, 20),
                    'total_assists' => rand(0, 15),
                    'yellow_cards' => rand(0, 5),
                    'red_cards' => rand(0, 2),
                    'matches_played' => rand(0, 15),
                    'is_captain' => $i === 1,
                    'is_active' => true,
                ]);
                $teamPlayers[] = $player;
                $allPlayers[] = $player;
            }

            // Update total players di tim
            $team->update(['total_players' => 12]);
        }

        // Buat pertandingan
        $matches = [
            [
                'home_team_id' => $createdTeams[0]->id,
                'away_team_id' => $createdTeams[1]->id,
                'venue_id' => 1,
                'tournament_id' => $tournament->id,
                'match_date' => '2024-10-15',
                'match_time' => '18:00',
                'home_score' => 3,
                'away_score' => 2,
                'status' => 'Completed',
                'round' => 'Week 1',
            ],
            [
                'home_team_id' => $createdTeams[2]->id,
                'away_team_id' => $createdTeams[3]->id,
                'venue_id' => 1,
                'tournament_id' => $tournament->id,
                'match_date' => '2024-10-15',
                'match_time' => '20:00',
                'home_score' => 1,
                'away_score' => 1,
                'status' => 'Completed',
                'round' => 'Week 1',
            ],
            [
                'home_team_id' => $createdTeams[4]->id,
                'away_team_id' => $createdTeams[5]->id,
                'venue_id' => 2,
                'tournament_id' => $tournament->id,
                'match_date' => '2024-10-22',
                'match_time' => '18:00',
                'home_score' => null,
                'away_score' => null,
                'status' => 'Scheduled',
                'round' => 'Week 2',
            ],
        ];

        $createdMatches = [];
        foreach ($matches as $matchData) {
            $createdMatches[] = MatchModel::create($matchData);
        }

        // Buat beberapa gol dan kartu untuk match pertama
        if (count($createdMatches) > 0 && count($allPlayers) > 0) {
            $firstMatch = $createdMatches[0];
            $homePlayers = array_slice($allPlayers, 0, 5);
            $awayPlayers = array_slice($allPlayers, 6, 5);

            // Gol untuk match pertama
            Goal::create([
                'match_id' => $firstMatch->id,
                'player_id' => $homePlayers[0]->id,
                'team_id' => $firstMatch->home_team_id,
                'minute' => 12,
                'type' => 'Normal',
                'assist_by' => $homePlayers[1]->id,
            ]);

            Goal::create([
                'match_id' => $firstMatch->id,
                'player_id' => $homePlayers[2]->id,
                'team_id' => $firstMatch->home_team_id,
                'minute' => 35,
                'type' => 'Penalty',
            ]);

            Goal::create([
                'match_id' => $firstMatch->id,
                'player_id' => $awayPlayers[0]->id,
                'team_id' => $firstMatch->away_team_id,
                'minute' => 28,
                'type' => 'Free Kick',
            ]);

            Goal::create([
                'match_id' => $firstMatch->id,
                'player_id' => $awayPlayers[1]->id,
                'team_id' => $firstMatch->away_team_id,
                'minute' => 42,
                'type' => 'Normal',
                'assist_by' => $awayPlayers[2]->id,
            ]);

            Goal::create([
                'match_id' => $firstMatch->id,
                'player_id' => $homePlayers[3]->id,
                'team_id' => $firstMatch->home_team_id,
                'minute' => 67,
                'type' => 'Normal',
            ]);

            // Kartu untuk match pertama
            Card::create([
                'match_id' => $firstMatch->id,
                'player_id' => $homePlayers[4]->id,
                'card_type' => 'Yellow',
                'minute' => 23,
                'reason' => 'Tackle keras',
            ]);

            Card::create([
                'match_id' => $firstMatch->id,
                'player_id' => $awayPlayers[3]->id,
                'card_type' => 'Red',
                'minute' => 78,
                'reason' => 'Pelanggaran berbahaya',
            ]);
        }

        // Update statistik tim dan buat standings
        foreach ($createdTeams as $team) {
            $matches = MatchModel::where(function ($query) use ($team) {
                $query->where('home_team_id', $team->id)
                    ->orWhere('away_team_id', $team->id);
            })->where('status', 'Completed')->get();

            $won = 0;
            $drawn = 0;
            $lost = 0;
            $goals_for = 0;
            $goals_against = 0;
            $played = $matches->count();

            foreach ($matches as $match) {
                if ($match->home_team_id == $team->id) {
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

            $team->update([
                'matches_played' => $played,
                'won' => $won,
                'drawn' => $drawn,
                'lost' => $lost,
                'goals_for' => $goals_for,
                'goals_against' => $goals_against,
                'points' => $points,
            ]);

            // Buat standings
            Standing::create([
                'team_id' => $team->id,
                'tournament_id' => $tournament->id,
                'position' => 0, // Akan diupdate nanti
                'played' => $played,
                'won' => $won,
                'drawn' => $drawn,
                'lost' => $lost,
                'goals_for' => $goals_for,
                'goals_against' => $goals_against,
                'goal_difference' => $goal_difference,
                'points' => $points,
            ]);
        }

        // Update position di standings berdasarkan points
        $standings = Standing::orderBy('points', 'desc')
            ->orderBy('goal_difference', 'desc')
            ->get();

        $position = 1;
        foreach ($standings as $standing) {
            $standing->update(['position' => $position]);
            $position++;
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin User: admin@omahfutsal.com / password');
        $this->command->info('Total Teams: '.Team::count());
        $this->command->info('Total Players: '.Player::count());
        $this->command->info('Total Matches: '.MatchModel::count());
        $this->command->info('Total Goals: '.Goal::count());
        $this->command->info('Total Cards: '.Card::count());
    }
}
