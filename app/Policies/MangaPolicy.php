<?php

namespace App\Policies;

use App\Models\Manga;
use App\Models\User;

class MangaPolicy
{
    public function before(?User $user, string $ability): ?bool
    {
        if ($user?->is_admin) {
            return true;
        }

        return null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Manga $manga): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Manga $manga): bool
    {
        return $user->id === $manga->user_id;
    }

    public function delete(User $user, Manga $manga): bool
    {
        return $user->id === $manga->user_id;
    }

    public function restore(User $user, Manga $manga): bool
    {
        return $user->id === $manga->user_id;
    }

    public function forceDelete(User $user, Manga $manga): bool
    {
        return $user->id === $manga->user_id;
    }
}
