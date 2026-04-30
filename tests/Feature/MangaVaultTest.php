<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Manga;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MangaVaultTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_visitors_can_view_the_catalogue_and_a_manga_detail(): void
    {
        $creator = User::factory()->create();
        $genre = Genre::query()->create(['name' => 'Acció']);

        $manga = Manga::query()->create([
            'user_id' => $creator->id,
            'title' => 'Blue Lock',
            'author' => 'Muneyuki Kaneshiro',
            'synopsis' => 'Un projecte radical vol trobar el davanter més egoista del Japó.',
            'total_chapters' => 100,
        ]);

        $manga->genres()->attach($genre->id);

        $this->get(route('mangas.index'))
            ->assertOk()
            ->assertSee('Blue Lock');

        $this->get(route('mangas.show', $manga))
            ->assertOk()
            ->assertSee('Muneyuki Kaneshiro')
            ->assertSee('Acció');
    }

    public function test_authenticated_users_can_create_mangas_and_manage_their_collection(): void
    {
        $user = User::factory()->create();
        $genre = Genre::query()->create(['name' => 'Shonen']);

        $this->actingAs($user)
            ->get(route('mangas.create'))
            ->assertOk();

        $this->actingAs($user)
            ->post(route('mangas.store'), [
                'title' => 'Dandadan',
                'author' => 'Yukinobu Tatsu',
                'synopsis' => 'Dues persones molt diferents acaben envoltades de fenòmens paranormals i aliens.',
                'total_chapters' => 50,
                'genres' => [$genre->id],
            ])
            ->assertRedirect();

        $manga = Manga::query()->first();

        $this->assertNotNull($manga);
        $this->assertSame($user->id, $manga->user_id);
        $this->assertTrue($manga->genres()->whereKey($genre->id)->exists());

        $this->actingAs($user)
            ->post(route('collections.store', $manga), [
                'status' => 'reading',
                'rating' => 8,
                'current_chapter' => 12,
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('manga_user', [
            'user_id' => $user->id,
            'manga_id' => $manga->id,
            'status' => 'reading',
            'rating' => 8,
            'current_chapter' => 12,
        ]);

        $this->actingAs($user)
            ->patch(route('collections.update', $manga), [
                'status' => 'completed',
                'rating' => 9,
                'current_chapter' => 50,
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('manga_user', [
            'user_id' => $user->id,
            'manga_id' => $manga->id,
            'status' => 'completed',
            'rating' => 9,
            'current_chapter' => 50,
        ]);

        $this->actingAs($user)
            ->delete(route('collections.destroy', $manga))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('manga_user', [
            'user_id' => $user->id,
            'manga_id' => $manga->id,
        ]);
    }

    public function test_users_cannot_edit_or_delete_mangas_from_other_users_but_admin_can(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $manga = Manga::query()->create([
            'user_id' => $owner->id,
            'title' => 'Pluto',
            'author' => 'Naoki Urasawa',
            'synopsis' => 'Una reinterpretació fosca de l\'univers d\'Astro Boy.',
            'total_chapters' => 65,
        ]);

        $this->actingAs($otherUser)
            ->get(route('mangas.edit', $manga))
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('mangas.destroy', $manga))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('mangas.edit', $manga))
            ->assertOk();
    }

    public function test_only_admin_can_access_genre_and_user_management(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get(route('genres.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('genres.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk();
    }
}
