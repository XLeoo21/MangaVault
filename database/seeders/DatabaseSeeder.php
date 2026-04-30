<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Manga;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->create([
            'name' => 'Administrador MangaVault',
            'email' => 'admin@mangavault.test',
            'password' => 'password',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $user = User::query()->create([
            'name' => 'Usuari MangaVault',
            'email' => 'user@mangavault.test',
            'password' => 'password',
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $genres = collect([
            'Acció',
            'Aventura',
            'Comèdia',
            'Fantasia',
            'Slice of Life',
            'Seinen',
            'Shonen',
        ])->mapWithKeys(function (string $name): array {
            $genre = Genre::query()->create(['name' => $name]);

            return [$name => $genre];
        });

        $mangas = [
            [
                'user_id' => $admin->id,
                'title' => 'Fullmetal Alchemist',
                'author' => 'Hiromu Arakawa',
                'synopsis' => 'Dos germans alquimistes busquen una manera de recuperar el que han perdut després d\'un experiment prohibit.',
                'total_chapters' => 116,
                'genres' => ['Acció', 'Aventura', 'Fantasia', 'Shonen'],
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Monster',
                'author' => 'Naoki Urasawa',
                'synopsis' => 'Un neurocirurgià brillant intenta aturar el monstre humà que va salvar anys enrere.',
                'total_chapters' => 162,
                'genres' => ['Seinen'],
            ],
            [
                'user_id' => $user->id,
                'title' => 'Haikyuu!!',
                'author' => 'Haruichi Furudate',
                'synopsis' => 'Un equip de voleibol d\'institut lluita per tornar a competir al màxim nivell.',
                'total_chapters' => 402,
                'genres' => ['Acció', 'Comèdia', 'Shonen'],
            ],
            [
                'user_id' => $user->id,
                'title' => 'Yotsuba&!',
                'author' => 'Kiyohiko Azuma',
                'synopsis' => 'La Yotsuba descobreix el dia a dia amb una energia inesgotable i una mirada molt innocent.',
                'total_chapters' => 117,
                'genres' => ['Comèdia', 'Slice of Life'],
            ],
        ];

        $createdMangas = collect($mangas)->map(function (array $mangaData) use ($genres): Manga {
            $selectedGenres = collect($mangaData['genres'])
                ->map(fn (string $genreName) => $genres[$genreName]->id)
                ->all();

            unset($mangaData['genres']);

            $manga = Manga::query()->create($mangaData);
            $manga->genres()->sync($selectedGenres);

            return $manga;
        });

        $user->collectionMangas()->attach($createdMangas[0]->id, [
            'status' => 'reading',
            'rating' => 9,
            'current_chapter' => 37,
        ]);

        $user->collectionMangas()->attach($createdMangas[1]->id, [
            'status' => 'plan_to_read',
            'rating' => null,
            'current_chapter' => 0,
        ]);

        $admin->collectionMangas()->attach($createdMangas[2]->id, [
            'status' => 'completed',
            'rating' => 8,
            'current_chapter' => 402,
        ]);
    }
}
