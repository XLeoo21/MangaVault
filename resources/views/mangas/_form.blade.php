@php
    $selectedGenres = old('genres', isset($manga) ? $manga->genres->pluck('id')->all() : []);
@endphp

<div class="space-y-6">
    <div>
        <label for="title" class="block text-sm font-medium text-slate-700">Títol</label>
        <input
            id="title"
            type="text"
            name="title"
            value="{{ old('title', $manga->title ?? '') }}"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
            required
        >
        @error('title')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="author" class="block text-sm font-medium text-slate-700">Autor</label>
        <input
            id="author"
            type="text"
            name="author"
            value="{{ old('author', $manga->author ?? '') }}"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
            required
        >
        @error('author')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="synopsis" class="block text-sm font-medium text-slate-700">Sinopsi</label>
        <textarea
            id="synopsis"
            name="synopsis"
            rows="5"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
        >{{ old('synopsis', $manga->synopsis ?? '') }}</textarea>
        @error('synopsis')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="total_chapters" class="block text-sm font-medium text-slate-700">Capítols totals</label>
        <input
            id="total_chapters"
            type="number"
            name="total_chapters"
            min="0"
            value="{{ old('total_chapters', $manga->total_chapters ?? '') }}"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
        >
        @error('total_chapters')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <p class="block text-sm font-medium text-slate-700">Gèneres</p>

        @if ($genres->isEmpty())
            <p class="mt-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Encara no hi ha gèneres disponibles. Un administrador n'ha de crear abans si en vols assignar.
            </p>
        @else
            <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($genres as $genre)
                    <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 text-sm text-slate-700">
                        <input
                            type="checkbox"
                            name="genres[]"
                            value="{{ $genre->id }}"
                            @checked(in_array($genre->id, $selectedGenres))
                            class="rounded border-slate-300 text-rose-600 shadow-sm focus:ring-rose-500"
                        >
                        <span>{{ $genre->name }}</span>
                    </label>
                @endforeach
            </div>
        @endif

        @error('genres')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @error('genres.*')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
            {{ $submitLabel }}
        </button>

        <a href="{{ isset($manga) ? route('mangas.show', $manga) : route('mangas.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
            Tornar
        </a>
    </div>
</div>
