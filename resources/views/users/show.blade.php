<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detall de l'usuari
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-3xl font-bold text-slate-900">{{ $user->name }}</h3>
                        <p class="mt-1 text-sm text-slate-600">{{ $user->email }}</p>
                        <p class="mt-2">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $user->is_admin ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $user->is_admin ? 'Administrador' : 'Usuari normal' }}
                            </span>
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('users.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                            Tornar
                        </a>

                        <a href="{{ route('users.edit', $user) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                            Editar
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[0.7fr_1.3fr]">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h4 class="text-xl font-bold text-slate-900">Resum</h4>
                    <dl class="mt-4 space-y-4 text-sm text-slate-600">
                        <div>
                            <dt class="font-semibold text-slate-900">Mangues creats</dt>
                            <dd>{{ $user->mangas->count() }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-900">Mangues a la col·lecció</dt>
                            <dd>{{ $collectionCount }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-900">Compte creat</dt>
                            <dd>{{ $user->created_at?->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h4 class="text-xl font-bold text-slate-900">Mangues creats per aquest usuari</h4>

                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        @forelse ($user->mangas as $manga)
                            <article class="rounded-2xl border border-slate-200 p-5">
                                <h5 class="text-lg font-bold text-slate-900">{{ $manga->title }}</h5>
                                <p class="mt-1 text-sm text-slate-500">Autor: {{ $manga->author }}</p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($manga->genres as $genre)
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $genre->name }}</span>
                                    @endforeach
                                </div>

                                <a href="{{ route('mangas.show', $manga) }}" class="mt-5 inline-flex text-sm font-semibold text-rose-700 transition hover:text-rose-900">
                                    Veure el manga
                                </a>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center md:col-span-2">
                                <h5 class="text-lg font-semibold text-slate-900">Aquest usuari encara no ha creat cap manga</h5>
                                <p class="mt-2 text-slate-600">Quan en creï un, apareixerà aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
