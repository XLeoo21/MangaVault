<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nom del gènere</label>
        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $genre->name ?? '') }}"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
            required
        >
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
            {{ $submitLabel }}
        </button>

        <a href="{{ isset($genre) ? route('genres.show', $genre) : route('genres.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
            Tornar
        </a>
    </div>
</div>
