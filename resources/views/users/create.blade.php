<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear usuari
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-2xl font-bold text-slate-900">Nou usuari</h3>
                <p class="mt-1 text-sm text-slate-600">Crea un nou compte i decideix si ha de tenir permisos d'administració.</p>

                <form method="POST" action="{{ route('users.store') }}" class="mt-6">
                    @csrf

                    @include('users._form', ['submitLabel' => 'Crear usuari'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
