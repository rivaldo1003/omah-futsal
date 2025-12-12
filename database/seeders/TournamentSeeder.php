<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Turnamen',
            'email' => 'admin@tournament.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Teams Grup A
        $grupA = [
            ['name' => 'BLUEMOSPHERE', 'group_name' => 'A'],
            ['name' => 'CASPER', 'group_name' => 'A'],
            ['name' => 'PAYUNG PUSAKA', 'group_name' => 'A'],
            ['name' => 'RONGGOLAWE', 'group_name' => 'A'],
            ['name' => 'ORI', 'group_name' => 'A'],
        ];

        // Teams Grup B
        $grupB = [
            ['name' => 'GOLD', 'group_name' => 'B'],
            ['name' => 'HAMTON', 'group_name' => 'B'],
            ['name' => 'SINGO LIAR', 'group_name' => 'B'],
            ['name' => 'ELANO', 'group_name' => 'B'],
            ['name' => 'KLIWON', 'group_name' => 'B'],
        ];

        // Insert teams
        foreach (array_merge($grupA, $grupB) as $teamData) {
            $team = Team::create($teamData);

            // Create sample players for each team
            for ($i = 1; $i <= 5; $i++) {
                Player::create([
                    'team_id' => $team->id,
                    'name' => "Player {$i} {$team->name}",
                    'jersey_number' => $i,
                    'position' => $this->getRandomPosition(),
                    'goals' => rand(0, 5),
                    'assists' => rand(0, 3),
                    'yellow_cards' => rand(0, 2),
                    'red_cards' => rand(0, 1),
                ]);
            }
        }

        $this->command->info('✅ Tournament data seeded successfully!');
    }

    private function getRandomPosition(): string
    {
        $positions = ['GK', 'DF', 'MF', 'FW'];

        return $positions[array_rand($positions)];
    }
}
