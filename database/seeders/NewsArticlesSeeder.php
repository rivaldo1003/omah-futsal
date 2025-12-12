<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use Illuminate\Database\Seeder;

class NewsArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
            [
                'title' => 'OFS Futsal Championship 2025 Dimulai Pekan Depan',
                'excerpt' => 'Turnamen futsal terbesar di Jombang akan segera dimulai dengan 32 tim peserta.',
                'source' => 'Kompas Olahraga',
                'source_url' => 'https://sports.kompas.com/futsal-championship-2025',
                'author' => 'Redaksi Kompas',
                'image_url' => 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=800&auto=format&fit=crop',
                'published_at' => now(),
                'category' => 'tournament',
                'is_active' => true,
                'is_featured' => true,
                'views_count' => 125,
            ],
            [
                'title' => 'Tim Junior OFS Raih Juara di Turnamen Regional',
                'excerpt' => 'Tim junior OFS Futsal Center berhasil menjadi juara dalam turnamen regional Jawa Timur.',
                'source' => 'Tribun Jatim',
                'source_url' => 'https://jatim.tribunnews.com/tim-junior-ofs-juara',
                'author' => 'Reporter Tribun',
                'image_url' => 'https://images.unsplash.com/photo-1577223625818-75bc1f2ac0e5?w=800&auto=format&fit=crop',
                'published_at' => now()->subDays(2),
                'category' => 'achievement',
                'is_active' => true,
                'is_featured' => true,
                'views_count' => 89,
            ],
            [
                'title' => 'Pendaftaran Turnamen Amatir Dibuka',
                'excerpt' => 'OFS Futsal Center membuka pendaftaran untuk turnamen amatir musim ini.',
                'source' => 'Detik Sport',
                'source_url' => 'https://sport.detik.com/turnamen-amatir-ofs',
                'author' => 'Tim Detik',
                'image_url' => 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=800&auto=format&fit=crop',
                'published_at' => now()->subDays(5),
                'category' => 'tournament',
                'is_active' => true,
                'is_featured' => false,
                'views_count' => 45,
            ],
            [
                'title' => 'Pelatihan Futsal untuk Pemula',
                'excerpt' => 'OFS membuka kelas pelatihan futsal khusus untuk pemula setiap akhir pekan.',
                'source' => 'Jawa Pos',
                'source_url' => 'https://www.jawapos.com/pelatihan-futsal-pemula',
                'author' => 'Koresponden Jawa Pos',
                'image_url' => 'https://images.unsplash.com/photo-1577223625818-75bc1f2ac0e5?w=800&auto=format&fit=crop',
                'published_at' => now()->subDays(7),
                'category' => 'training',
                'is_active' => true,
                'is_featured' => false,
                'views_count' => 67,
            ],
        ];

        foreach ($news as $item) {
            NewsArticle::create($item);
        }
    }
}
