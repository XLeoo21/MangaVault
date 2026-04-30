<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nom</label>
        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $user->name ?? '') }}"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
            required
        >
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-slate-700">Correu electrònic</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
            required
        >
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Contrasenya {{ isset($user) ? '(deixa-ho buit per no canviar-la)' : '' }}</label>
            <input
                id="password"
                type="password"
                name="password"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                {{ isset($user) ? '' : 'required' }}
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmació de la contrasenya</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                {{ isset($user) ? '' : 'required' }}
            >
        </div>
    </div>

    <label class="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 text-sm text-slate-700">
        <input
            type="checkbox"
            name="is_admin"
            value="1"
            @checked(old('is_admin', $user->is_admin ?? false))
            class="rounded border-slate-300 text-rose-600 shadow-sm focus:ring-rose-500"
        >
        <span>Usuari administrador</span>
    </label>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
            {{ $submitLabel }}
        </button>

        <a href="{{ isset($user) ? route('users.show', $user) : route('users.index') }}" class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900">
            Tornar
        </a>
    </div>
</div>
