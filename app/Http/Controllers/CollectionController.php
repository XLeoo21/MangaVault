<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(Request $request): View
    {
        return view('collections.index', [
            'collectionMangas' => $request->user()
                ->collectionMangas()
                ->with(['user', 'genres'])
                ->orderBy('title')
                ->get(),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function store(Request $request, Manga $manga): RedirectResponse
    {
        $validated = $this->validateCollectionData($request);
        $relation = $request->user()->collectionMangas();

        if ($relation->find($manga->id)) {
            $relation->updateExistingPivot($manga->id, $validated);

            return back()->with('success', 'Les dades de la col·lecció s\'han actualitzat correctament.');
        }

        $relation->attach($manga->id, $validated);

        return back()->with('success', 'El manga s\'ha afegit a la teva col·lecció.');
    }

    public function update(Request $request, Manga $manga): RedirectResponse
    {
        $validated = $this->validateCollectionData($request);
        $request->user()->collectionMangas()->updateExistingPivot($manga->id, $validated);

        return back()->with('success', 'La col·lecció s\'ha actualitzat correctament.');
    }

    public function destroy(Request $request, Manga $manga): RedirectResponse
    {
        $request->user()->collectionMangas()->detach($manga->id);

        return back()->with('success', 'El manga s\'ha eliminat de la teva col·lecció.');
    }

    private function validateCollectionData(Request $request): array
    {
        return $request->validate([
            'status' => ['required', Rule::in(array_keys($this->statusOptions()))],
            'rating' => ['nullable', 'integer', 'between:1,10'],
            'current_chapter' => ['required', 'integer', 'min:0'],
        ]);
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
