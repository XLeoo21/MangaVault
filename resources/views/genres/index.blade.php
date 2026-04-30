<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gèneres
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Gestió de gèneres</h3>
                        <p class="mt-1 text-sm text-slate-600">Només l'administrador pot crear, editar o eliminar gèneres.</p>
                    </div>

                    <a href="{{ route('genres.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                        Crear gènere
                    </a>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($genres as $genre)
                        <article class="rounded-2xl border border-slate-200 p-5">
                            <h4 class="text-xl font-bold text-slate-900">{{ $genre->name }}</h4>
                            <p class="mt-2 text-sm text-slate-600">{{ $genre->mangas_count }} mangues relacionats</p>

                            <div class="mt-5 flex flex-wrap gap-3">
                                <a href="{{ route('genres.show', $genre) }}" class="rounded-lg bg-rose-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-800">
                                    Veure
                                </a>

                                <a href="{{ route('genres.edit', $genre) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('genres.destroy', $genre) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-50">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center md:col-span-2 xl:col-span-3">
                            <h4 class="text-xl font-semibold text-slate-900">No hi ha cap gènere registrat</h4>
                            <p class="mt-2 text-slate-600">Crea'n un per començar a classificar els mangues.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $genres->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
