<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            // Senior Teams (Tim Senior)
            [
                'name' => 'Manchester United',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/7/7a/Manchester_United_FC_crest.svg/1200px-Manchester_United_FC_crest.svg.png',
                'coach_name' => 'Erik ten Hag',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Liverpool FC',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/0/0c/Liverpool_FC.svg/1200px-Liverpool_FC.svg.png',
                'coach_name' => 'Jürgen Klopp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chelsea FC',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/c/cc/Chelsea_FC.svg/1200px-Chelsea_FC.svg.png',
                'coach_name' => 'Mauricio Pochettino',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arsenal FC',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/5/53/Arsenal_FC.svg/1200px-Arsenal_FC.svg.png',
                'coach_name' => 'Mikel Arteta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manchester City',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/e/eb/Manchester_City_FC_badge.svg/1200px-Manchester_City_FC_badge.svg.png',
                'coach_name' => 'Pep Guardiola',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tottenham Hotspur',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/b/b4/Tottenham_Hotspur.svg/1200px-Tottenham_Hotspur.svg.png',
                'coach_name' => 'Ange Postecoglou',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Junior Teams (Tim Junior)
            [
                'name' => 'Barcelona Junior',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/4/47/FC_Barcelona_%28crest%29.svg/1200px-FC_Barcelona_%28crest%29.svg.png',
                'coach_name' => 'Rafael Márquez',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Real Madrid Junior',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/5/56/Real_Madrid_CF.svg/1200px-Real_Madrid_CF.svg.png',
                'coach_name' => 'Alberto Toril',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bayern Munich Junior',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/FC_Bayern_M%C3%BCnchen_logo_%282017%29.svg/1200px-FC_Bayern_M%C3%BCnchen_logo_%282017%29.svg.png',
                'coach_name' => 'Danny Schwarz',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AC Milan Junior',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Logo_of_AC_Milan.svg/1200px-Logo_of_AC_Milan.svg.png',
                'coach_name' => 'Ignazio Abate',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // U-19 Teams
            [
                'name' => 'Paris Saint-Germain U19',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/a/a7/Paris_Saint-Germain_F.C..svg/1200px-Paris_Saint-Germain_F.C..svg.png',
                'coach_name' => 'Zoumana Camara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Borussia Dortmund U19',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Borussia_Dortmund_logo.svg/1200px-Borussia_Dortmund_logo.svg.png',
                'coach_name' => 'Mike Tullberg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ajax U19',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/7/79/Ajax_Amsterdam.svg/1200px-Ajax_Amsterdam.svg.png',
                'coach_name' => 'John Heitinga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Juventus U19',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Juventus_FC_2017_icon_%28black%29.svg/1200px-Juventus_FC_2017_icon_%28black%29.svg.png',
                'coach_name' => 'Paolo Montero',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // U-17 Teams
            [
                'name' => 'Inter Milan U17',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/FC_Internazionale_Milano_2021.svg/1200px-FC_Internazionale_Milano_2021.svg.png',
                'coach_name' => 'Christian Chivu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Atlético Madrid U17',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/f/f4/Atletico_Madrid_2017_logo.svg/1200px-Atletico_Madrid_2017_logo.svg.png',
                'coach_name' => 'Fernando Torres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AS Roma U17',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/f/f7/AS_Roma_logo_%282017%29.svg/1200px-AS_Roma_logo_%282017%29.svg.png',
                'coach_name' => 'Alberto De Rossi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Benfica U17',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/6/6d/SL_Benfica_logo.svg/1200px-SL_Benfica_logo.svg.png',
                'coach_name' => 'Luís Castro',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Local Indonesia Teams
            [
                'name' => 'Persib Bandung',
                'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/7/7e/Persib_new_logo.svg/1200px-Persib_new_logo.svg.png',
                'coach_name' => 'Boško Gjurovski',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Persija Jakarta',
                'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/9/95/Persija_Jakarta_crest.svg/1200px-Persija_Jakarta_crest.svg.png',
                'coach_name' => 'Thomas Doll',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arema FC',
                'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/6/6b/Arema_FC_2020_logo.svg/1200px-Arema_FC_2020_logo.svg.png',
                'coach_name' => 'Joko Susilo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bali United',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/8/88/Bali_United_logo.svg/1200px-Bali_United_logo.svg.png',
                'coach_name' => 'Stefano Cugurra',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data ke database
        DB::table('teams')->insert($teams);

        $this->command->info('✅ Successfully seeded '.count($teams).' teams!');
        $this->command->info('📋 Teams list:');

        foreach ($teams as $index => $team) {
            $this->command->info(($index + 1).'. '.$team['name'].' - Coach: '.($team['coach_name'] ?? 'N/A'));
        }
    }
}
