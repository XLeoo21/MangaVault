<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Usuaris
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Gestió d'usuaris</h3>
                        <p class="mt-1 text-sm text-slate-600">Zona d'administració per crear, editar i eliminar usuaris del projecte.</p>
                    </div>

                    <a href="{{ route('users.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                        Crear usuari
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr class="text-left text-sm font-semibold text-slate-700">
                                <th class="pb-3">Nom</th>
                                <th class="pb-3">Correu electrònic</th>
                                <th class="pb-3">Rol</th>
                                <th class="pb-3 text-right">Accions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="py-4 font-medium text-slate-900">{{ $user->name }}</td>
                                    <td class="py-4">{{ $user->email }}</td>
                                    <td class="py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $user->is_admin ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $user->is_admin ? 'Administrador' : 'Usuari' }}
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('users.show', $user) }}" class="font-semibold text-rose-700 transition hover:text-rose-900">
                                                Veure
                                            </a>

                                            <a href="{{ route('users.edit', $user) }}" class="font-semibold text-slate-700 transition hover:text-slate-900">
                                                Editar
                                            </a>

                                            <form method="POST" action="{{ route('users.destroy', $user) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="font-semibold text-red-700 transition hover:text-red-900">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-600">No hi ha cap usuari registrat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
