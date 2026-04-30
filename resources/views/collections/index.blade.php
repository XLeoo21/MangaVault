<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            La meva col·lecció
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Seguiment de lectura</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Gestiona l'estat, la puntuació i el capítol actual dels mangues que tens desats.
                        </p>
                    </div>

                    <a href="{{ route('mangas.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                        Tornar al catàleg
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse ($collectionMangas as $manga)
                        <article class="rounded-2xl border border-slate-200 p-5">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                                <div class="max-w-2xl">
                                    <h4 class="text-xl font-bold text-slate-900">{{ $manga->title }}</h4>
                                    <p class="mt-1 text-sm text-slate-500">Autor: {{ $manga->author }}</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $manga->synopsis ?: 'Aquest manga encara no té sinopsi informada.' }}
                                    </p>

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach ($manga->genres as $genre)
                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $genre->name }}</span>
                                        @endforeach
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('mangas.show', $manga) }}" class="text-sm font-semibold text-rose-700 transition hover:text-rose-900">
                                            Veure el detall del manga
                                        </a>
                                    </div>
                                </div>

                                <div class="w-full max-w-xl">
                                    <form method="POST" action="{{ route('collections.update', $manga) }}" class="grid gap-4 rounded-2xl bg-slate-50 p-4 md:grid-cols-3">
                                        @csrf
                                        @method('PATCH')

                                        <div class="md:col-span-3">
                                            <label for="status-{{ $manga->id }}" class="block text-sm font-medium text-slate-700">Estat de lectura</label>
                                            <select id="status-{{ $manga->id }}" name="status" class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500">
                                                @foreach ($statusOptions as $value => $label)
                                                    <option value="{{ $value }}" @selected(old('status', $manga->pivot->status) === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="rating-{{ $manga->id }}" class="block text-sm font-medium text-slate-700">Puntuació</label>
                                            <input
                                                id="rating-{{ $manga->id }}"
                                                type="number"
                                                name="rating"
                                                min="1"
                                                max="10"
                                                value="{{ old('rating', $manga->pivot->rating) }}"
                                                class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500"
                                            >
                                        </div>

                                        <div>
                                            <label for="chapter-{{ $manga->id }}" class="block text-sm font-medium text-slate-700">Capítol actual</label>
                                            <input
                                                id="chapter-{{ $manga->id }}"
                                                type="number"
                                                name="current_chapter"
                                                min="0"
                                                value="{{ old('current_chapter', $manga->pivot->current_chapter) }}"
                                                class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500"
                                            >
                                        </div>

                                        <div class="flex items-end">
                                            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                                                Desar canvis
                                            </button>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('collections.destroy', $manga) }}" class="mt-3">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-50">
                                            Treure de la col·lecció
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center">
                            <h4 class="text-xl font-semibold text-slate-900">Encara no tens cap manga desat</h4>
                            <p class="mt-2 text-slate-600">Afegeix-ne un des del detall del manga per començar a fer seguiment de la lectura.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
