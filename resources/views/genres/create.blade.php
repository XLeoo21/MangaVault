<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear gènere
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-2xl font-bold text-slate-900">Nou gènere</h3>
                <p class="mt-1 text-sm text-slate-600">Afegeix un nou gènere disponible per als mangues del catàleg.</p>

                <form method="POST" action="{{ route('genres.store') }}" class="mt-6">
                    @csrf

                    @include('genres._form', ['submitLabel' => 'Crear gènere'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
