<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detall del manga
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-3xl font-bold text-slate-900">{{ $manga->title }}</h3>

                            @foreach ($manga->genres as $genre)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        <div class="mt-4 space-y-2 text-sm text-slate-600">
                            <p><span class="font-semibold text-slate-900">Autor:</span> {{ $manga->author }}</p>
                            <p><span class="font-semibold text-slate-900">Creat per:</span> {{ $manga->user->name }}</p>
                            <p><span class="font-semibold text-slate-900">Capítols totals:</span> {{ $manga->total_chapters ?? 'No informat' }}</p>
                        </div>

                        <div class="mt-6 rounded-2xl bg-slate-50 p-5">
                            <h4 class="text-lg font-semibold text-slate-900">Sinopsi</h4>
                            <p class="mt-3 leading-7 text-slate-700">
                                {{ $manga->synopsis ?: 'Aquest manga encara no té una sinopsi informada.' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <a href="{{ route('mangas.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                            Tornar
                        </a>

                        @can('update', $manga)
                            <a href="{{ route('mangas.edit', $manga) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                                Editar
                            </a>
                        @endcan

                        @can('delete', $manga)
                            <form method="POST" action="{{ route('mangas.destroy', $manga) }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-50">
                                    Eliminar
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>

            @auth
                <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">La meva col·lecció</h3>
                            <p class="mt-1 text-sm text-slate-600">
                                Desa aquest manga a la teva llista personal i actualitza el seguiment de lectura quan vulguis.
                            </p>
                        </div>

                        @if ($collectionEntry)
                            <span class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                                Aquest manga ja és a la teva col·lecció
                            </span>
                        @endif
                    </div>

                    <form method="POST" action="{{ $collectionEntry ? route('collections.update', $manga) : route('collections.store', $manga) }}" class="mt-6 grid gap-4 md:grid-cols-3">
                        @csrf
                        @if ($collectionEntry)
                            @method('PATCH')
                        @endif

                        <div class="md:col-span-3">
                            <label for="status" class="block text-sm font-medium text-slate-700">Estat de lectura</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500">
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $collectionEntry?->pivot?->status ?? 'plan_to_read') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rating" class="block text-sm font-medium text-slate-700">Puntuació</label>
                            <input
                                id="rating"
                                type="number"
                                name="rating"
                                min="1"
                                max="10"
                                value="{{ old('rating', $collectionEntry?->pivot?->rating) }}"
                                class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500"
                            >
                            @error('rating')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="current_chapter" class="block text-sm font-medium text-slate-700">Capítol actual</label>
                            <input
                                id="current_chapter"
                                type="number"
                                name="current_chapter"
                                min="0"
                                value="{{ old('current_chapter', $collectionEntry?->pivot?->current_chapter ?? 0) }}"
                                class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500"
                            >
                            @error('current_chapter')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                                {{ $collectionEntry ? 'Desar canvis' : 'Afegir a la meva col·lecció' }}
                            </button>
                        </div>
                    </form>

                    @if ($collectionEntry)
                        <form method="POST" action="{{ route('collections.destroy', $manga) }}" class="mt-4">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-50">
                                Treure de la meva col·lecció
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-xl font-semibold text-slate-900">Vols guardar aquest manga?</h3>
                    <p class="mt-2 text-slate-600">Inicia sessió per afegir-lo a la teva col·lecció personal i portar el seguiment de lectura.</p>
                    <a href="{{ route('login') }}" class="mt-4 inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                        Inicia sessió
                    </a>
                </div>
            @endauth
        </div>
    </div>
</x-app-layout>
