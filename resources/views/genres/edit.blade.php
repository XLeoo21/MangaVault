<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar gènere
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-2xl font-bold text-slate-900">Editar {{ $genre->name }}</h3>
                <p class="mt-1 text-sm text-slate-600">Modifica el nom del gènere mantenint la coherència del catàleg.</p>

                <form method="POST" action="{{ route('genres.update', $genre) }}" class="mt-6">
                    @csrf
                    @method('PATCH')

                    @include('genres._form', ['submitLabel' => 'Desar canvis'])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
