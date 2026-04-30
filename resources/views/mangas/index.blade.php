<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catàleg de mangues
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Llistat de mangues</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Consulta el catàleg públic i entra al detall de cada manga per veure'n la informació completa.
                        </p>
                    </div>

                    @can('create', App\Models\Manga::class)
                        <a href="{{ route('mangas.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                            Crear manga
                        </a>
                    @endcan
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($mangas as $manga)
                        <article class="flex h-full flex-col rounded-2xl border border-slate-200 p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900">{{ $manga->title }}</h4>
                                    <p class="mt-1 text-sm text-slate-500">Autor: {{ $manga->author }}</p>
                                    <p class="text-sm text-slate-500">Creat per {{ $manga->user->name }}</p>
                                </div>

                                @auth
                                    @if (in_array($manga->id, $collectionMangaIds))
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            A la teva col·lecció
                                        </span>
                                    @endif
                                @endauth
                            </div>

                            <p class="mt-4 flex-1 text-sm text-slate-600">
                                {{ \Illuminate\Support\Str::limit($manga->synopsis ?: 'Aquest manga encara no té sinopsi informada.', 160) }}
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach ($manga->genres as $genre)
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $genre->name }}</span>
                                @endforeach
                            </div>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="{{ route('mangas.show', $manga) }}" class="rounded-lg bg-rose-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-800">
                                    Veure detall
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
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center md:col-span-2 xl:col-span-3">
                            <h4 class="text-xl font-semibold text-slate-900">Encara no hi ha cap manga registrat</h4>
                            <p class="mt-2 text-slate-600">Quan es creï el primer manga, apareixerà aquí.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $mangas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
