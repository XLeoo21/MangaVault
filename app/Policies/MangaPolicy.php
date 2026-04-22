<?php

namespace App\Policies;

use App\Models\Manga;
use App\Models\User;

class MangaPolicy
{
    /**
     * Grant admins full access before checking specific abilities.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->is_admin ? true : null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Manga $manga): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Manga $manga): bool
    {
        return $user->id === $manga->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Manga $manga): bool
    {
        return $user->id === $manga->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Manga $manga): bool
    {
        return $user->id === $manga->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Manga $manga): bool
    {
        return $user->id === $manga->user_id;
    }
}
