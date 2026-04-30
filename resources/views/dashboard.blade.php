<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tauler
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">Mangues del catàleg</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $stats['mangasTotals'] }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">Mangues creats per tu</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $stats['mangasCreats'] }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">Mangues a la teva col·lecció</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $stats['mangasColleccio'] }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">Gèneres disponibles</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $stats['genresTotals'] }}</p>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-2xl font-bold text-slate-900">Accés ràpid</h3>
                <p class="mt-2 text-slate-600">
                    Des d'aquí pots revisar el catàleg, crear nous mangues o mantenir al dia la teva col·lecció personal.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('mangas.index') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                        Veure el catàleg
                    </a>

                    <a href="{{ route('mangas.create') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                        Crear manga
                    </a>

                    <a href="{{ route('collections.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
                        Obrir la meva col·lecció
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
