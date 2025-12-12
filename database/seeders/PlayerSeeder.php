<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('players')->truncate();

        $players = [];

        // Data nama pemain futsal Indonesia (contoh)
        $indonesianFirstNames = [
            'Ahmad',
            'Budi',
            'Cahyo',
            'Doni',
            'Eko',
            'Fajar',
            'Guntur',
            'Hadi',
            'Irfan',
            'Joko',
            'Kurniawan',
            'Lukman',
            'Mulyadi',
            'Nugroho',
            'Oki',
            'Prasetyo',
            'Rudi',
            'Slamet',
            'Tri',
            'Udin',
            'Wahyu',
            'Yanto',
            'Zainal',
        ];

        $indonesianLastNames = [
            'Santoso',
            'Wijaya',
            'Pratama',
            'Saputra',
            'Setiawan',
            'Kusuma',
            'Hidayat',
            'Siregar',
            'Sihombing',
            'Simbolon',
            'Siregar',
            'Nasution',
            'Lumban',
            'Situmorang',
            'Manalu',
            'Butar',
            'Siahaan',
            'Nababan',
            'Silitonga',
            'Sirait',
            'Hutagalung',
            'Marpaung',
            'Pane',
            'Purba',
        ];

        // Generate pemain untuk team_id 13 sampai 34
        for ($teamId = 13; $teamId <= 34; $teamId++) {
            // Setiap tim futsal
            $teamPlayers = [];

            // Kiper (minimal 1, maksimal 2)
            $numKeepers = rand(1, 2);
            for ($k = 0; $k < $numKeepers; $k++) {
                $name = $indonesianFirstNames[array_rand($indonesianFirstNames)].' '.
                    $indonesianLastNames[array_rand($indonesianLastNames)];

                $teamPlayers[] = [
                    'team_id' => $teamId,
                    'name' => $name,
                    'jersey_number' => $k === 0 ? 1 : 12, // Kiper utama no 1, cadangan no 12
                    'position' => 'Kiper',
                    'photo' => 'players/default.jpg',
                    'goals' => rand(0, 2),
                    'assists' => rand(1, 5),
                    'yellow_cards' => rand(0, 2),
                    'red_cards' => rand(0, 1),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            // Pivot (1-2 pemain)
            $numPivots = rand(1, 2);
            for ($p = 0; $p < $numPivots; $p++) {
                $name = $indonesianFirstNames[array_rand($indonesianFirstNames)].' '.
                    $indonesianLastNames[array_rand($indonesianLastNames)];

                $teamPlayers[] = [
                    'team_id' => $teamId,
                    'name' => $name,
                    'jersey_number' => 9 + $p, // 9, 10
                    'position' => 'Pivot',
                    'photo' => 'players/default.jpg',
                    'goals' => rand(20, 45),
                    'assists' => rand(8, 20),
                    'yellow_cards' => rand(2, 6),
                    'red_cards' => rand(0, 1),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            // Flank (2-4 pemain)
            $numFlanks = rand(2, 4);
            for ($f = 0; $f < $numFlanks; $f++) {
                $name = $indonesianFirstNames[array_rand($indonesianFirstNames)].' '.
                    $indonesianLastNames[array_rand($indonesianLastNames)];

                $teamPlayers[] = [
                    'team_id' => $teamId,
                    'name' => $name,
                    'jersey_number' => 5 + $f, // 5, 6, 7, 8
                    'position' => 'Flank',
                    'photo' => 'players/default.jpg',
                    'goals' => rand(10, 25),
                    'assists' => rand(12, 30),
                    'yellow_cards' => rand(1, 5),
                    'red_cards' => rand(0, 1),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            // Anchor (1-3 pemain)
            $numAnchors = rand(1, 3);
            for ($a = 0; $a < $numAnchors; $a++) {
                $name = $indonesianFirstNames[array_rand($indonesianFirstNames)].' '.
                    $indonesianLastNames[array_rand($indonesianLastNames)];

                $teamPlayers[] = [
                    'team_id' => $teamId,
                    'name' => $name,
                    'jersey_number' => 2 + $a, // 2, 3, 4
                    'position' => 'Anchor',
                    'photo' => 'players/default.jpg',
                    'goals' => rand(3, 12),
                    'assists' => rand(5, 15),
                    'yellow_cards' => rand(3, 8),
                    'red_cards' => rand(0, 2),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            // Gabungkan ke array utama
            $players = array_merge($players, $teamPlayers);
        }

        // Insert data ke database
        DB::table('players')->insert($players);

        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $totalPlayers = count($players);
        $this->command->info("Futsal players seeded successfully! Total: {$totalPlayers} players");
        $this->command->info('Teams covered: 13 to 34');
    }
}
