<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'MangaVault') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 text-slate-900 antialiased">
        <div class="min-h-screen">
            <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-rose-600">MangaVault</p>
                        <h1 class="text-2xl font-bold text-slate-900">Catàleg comunitari de manga</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('mangas.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                            Veure el catàleg
                        </a>

                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
                                Entrar al tauler
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
                                Inicia sessió
                            </a>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <section class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                    <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-rose-900 p-8 text-white shadow-xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-rose-200">Comunitat lectora</p>
                        <h2 class="mt-4 text-4xl font-bold leading-tight sm:text-5xl">
                            Organitza, descobreix i comparteix els teus mangues preferits.
                        </h2>
                        <p class="mt-6 max-w-2xl text-lg text-slate-200">
                            MangaVault et permet consultar el catàleg públic, crear noves fitxes de manga i gestionar la teva llista personal de lectura amb estat, puntuació i capítol actual.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ route('mangas.index') }}" class="rounded-lg bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-200">
                                Explorar mangues
                            </a>

                            @guest
                                <a href="{{ route('register') }}" class="rounded-lg border border-white/40 px-5 py-3 text-sm font-semibold text-white transition hover:border-white hover:bg-white/10">
                                    Crear un compte
                                </a>
                            @endguest
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <p class="text-sm font-medium text-slate-500">Mangues registrats</p>
                            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['mangas'] }}</p>
                        </div>

                        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <p class="text-sm font-medium text-slate-500">Gèneres disponibles</p>
                            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['genres'] }}</p>
                        </div>

                        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <p class="text-sm font-medium text-slate-500">Usuaris registrats</p>
                            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['users'] }}</p>
                        </div>
                    </div>
                </section>

                <section class="mt-12">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-rose-600">Últims afegits</p>
                            <h2 class="mt-2 text-3xl font-bold text-slate-900">Mangues destacats del catàleg</h2>
                        </div>

                        <a href="{{ route('mangas.index') }}" class="text-sm font-semibold text-rose-700 transition hover:text-rose-900">
                            Veure tots els mangues
                        </a>
                    </div>

                    <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @forelse ($latestMangas as $manga)
                            <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900">{{ $manga->title }}</h3>
                                        <p class="mt-1 text-sm text-slate-500">Autor: {{ $manga->author }}</p>
                                        <p class="text-sm text-slate-500">Creat per {{ $manga->user->name }}</p>
                                    </div>

                                    <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">
                                        {{ $manga->genres->count() }} gèneres
                                    </span>
                                </div>

                                <p class="mt-4 line-clamp-3 text-sm text-slate-600">
                                    {{ $manga->synopsis ?: 'Aquest manga encara no té sinopsi informada.' }}
                                </p>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach ($manga->genres as $genre)
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $genre->name }}</span>
                                    @endforeach
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('mangas.show', $manga) }}" class="text-sm font-semibold text-rose-700 transition hover:text-rose-900">
                                        Veure el detall
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl bg-white p-6 text-slate-600 shadow-sm ring-1 ring-slate-200 md:col-span-2 xl:col-span-3">
                                Encara no hi ha mangues registrats.
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
