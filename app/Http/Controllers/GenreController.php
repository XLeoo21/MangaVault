<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function index(): View
    {
        return view('genres.index', [
            'genres' => Genre::withCount('mangas')->orderBy('name')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('genres.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:genres,name'],
        ]);

        $genre = Genre::query()->create($validated);

        return redirect()
            ->route('genres.show', $genre)
            ->with('success', 'El gènere s\'ha creat correctament.');
    }

    public function show(Genre $genre): View
    {
        $genre->load(['mangas.user', 'mangas.genres']);

        return view('genres.show', [
            'genre' => $genre,
        ]);
    }

    public function edit(Genre $genre): View
    {
        return view('genres.edit', [
            'genre' => $genre,
        ]);
    }

    public function update(Request $request, Genre $genre): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('genres', 'name')->ignore($genre->id)],
        ]);

        $genre->update($validated);

        return redirect()
            ->route('genres.show', $genre)
            ->with('success', 'El gènere s\'ha actualitzat correctament.');
    }

    public function destroy(Genre $genre): RedirectResponse
    {
        $genre->delete();

        return redirect()
            ->route('genres.index')
            ->with('success', 'El gènere s\'ha eliminat correctament.');
    }
}
