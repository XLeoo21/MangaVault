<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar manga
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-2xl font-bold text-slate-900">Editar {{ $manga->title }}</h3>
                <p class="mt-1 text-sm text-slate-600">Actualitza la informació del manga i els gèneres relacionats.</p>

                <form method="POST" action="{{ route('mangas.update', $manga) }}" class="mt-6">
                    @csrf
                    @method('PATCH')

                    @include('mangas._form', ['submitLabel' => 'Desar canvis'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
