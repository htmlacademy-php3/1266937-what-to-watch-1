<?php

namespace App\Policies;

use App\Models\Film;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\FilmStatus;

class FilmPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user, string $status): bool
    {
        if ($status === FilmStatus::Ready->value) {
            return true;
        }

        return $user?->isModerator() ?? false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Film $film): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // return true;
        return $user->isModerator();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Film $film): bool
    {
        return $user->isModerator();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Film $film): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Film $film): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Film $film): bool
    {
        return false;
    }
}
