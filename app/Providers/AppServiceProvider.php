<?php

namespace App\Providers;

use App\Models\Manga;
use App\Models\User;
use App\Policies\MangaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Manga::class, MangaPolicy::class);

        Gate::define('is_admin', fn (User $user): bool => $user->is_admin);

        Gate::define('is_owner', fn (User $user, Manga $manga): bool => $user->id === $manga->user_id);
    }
}
