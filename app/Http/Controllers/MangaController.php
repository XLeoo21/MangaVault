<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MangaController extends Controller
{
    public function index(): View
    {
        return view('mangas.index', [
            'mangas' => Manga::with(['genres', 'user'])->latest()->paginate(9),
            'collectionMangaIds' => auth()->check()
                ? auth()->user()->collectionMangas()->pluck('mangas.id')->all()
                : [],
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Manga::class);

        return view('mangas.create', [
            'genres' => Genre::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Manga::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'synopsis' => ['nullable', 'string'],
            'total_chapters' => ['nullable', 'integer', 'min:0'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['exists:genres,id'],
        ]);

        $manga = Manga::query()->create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'author' => $validated['author'],
            'synopsis' => $validated['synopsis'] ?? null,
            'total_chapters' => $validated['total_chapters'] ?? null,
        ]);

        $manga->genres()->sync($validated['genres'] ?? []);

        return redirect()
            ->route('mangas.show', $manga)
            ->with('success', 'El manga s\'ha creat correctament.');
    }

    public function show(Manga $manga): View
    {
        $manga->loadMissing(['genres', 'user']);

        return view('mangas.show', [
            'manga' => $manga,
            'collectionEntry' => auth()->check()
                ? auth()->user()->collectionMangas()->find($manga->id)
                : null,
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function edit(Manga $manga): View
    {
        $this->authorize('update', $manga);
        $manga->load('genres');

        return view('mangas.edit', [
            'manga' => $manga,
            'genres' => Genre::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Manga $manga): RedirectResponse
    {
        $this->authorize('update', $manga);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'synopsis' => ['nullable', 'string'],
            'total_chapters' => ['nullable', 'integer', 'min:0'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['exists:genres,id'],
        ]);

        $manga->update([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'synopsis' => $validated['synopsis'] ?? null,
            'total_chapters' => $validated['total_chapters'] ?? null,
        ]);

        $manga->genres()->sync($validated['genres'] ?? []);

        return redirect()
            ->route('mangas.show', $manga)
            ->with('success', 'El manga s\'ha actualitzat correctament.');
    }

    public function destroy(Manga $manga): RedirectResponse
    {
        $this->authorize('delete', $manga);

        $manga->delete();

        return redirect()
            ->route('mangas.index')
            ->with('success', 'El manga s\'ha eliminat correctament.');
    }

    private function statusOptions(): array
    {
        return [
            'plan_to_read' => 'Per llegir',
            'reading' => 'Llegint',
            'completed' => 'Completat',
            'on_hold' => 'En pausa',
            'dropped' => 'Abandonat',
        ];
    }
}
