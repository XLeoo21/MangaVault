<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear manga
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-2xl font-bold text-slate-900">Nova fitxa de manga</h3>
                <p class="mt-1 text-sm text-slate-600">Omple les dades bàsiques i assigna els gèneres que corresponguin.</p>

                <form method="POST" action="{{ route('mangas.store') }}" class="mt-6">
                    @csrf

                    @include('mangas._form', ['submitLabel' => 'Crear manga'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
