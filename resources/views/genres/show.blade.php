<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detall del gènere
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-3xl font-bold text-slate-900">{{ $genre->name }}</h3>
                        <p class="mt-1 text-sm text-slate-600">Mangues que actualment tenen assignat aquest gènere.</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('genres.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                            Tornar
                        </a>

                        <a href="{{ route('genres.edit', $genre) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                            Editar
                        </a>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($genre->mangas as $manga)
                        <article class="rounded-2xl border border-slate-200 p-5">
                            <h4 class="text-xl font-bold text-slate-900">{{ $manga->title }}</h4>
                            <p class="mt-1 text-sm text-slate-500">Autor: {{ $manga->author }}</p>
                            <p class="mt-1 text-sm text-slate-500">Creat per {{ $manga->user->name }}</p>

                            <a href="{{ route('mangas.show', $manga) }}" class="mt-5 inline-flex text-sm font-semibold text-rose-700 transition hover:text-rose-900">
                                Veure el manga
                            </a>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center md:col-span-2 xl:col-span-3">
                            <h4 class="text-xl font-semibold text-slate-900">Aquest gènere encara no té mangues</h4>
                            <p class="mt-2 text-slate-600">Quan se n'assigni algun, apareixerà aquí.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
