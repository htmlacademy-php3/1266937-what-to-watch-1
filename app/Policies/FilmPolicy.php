<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Film;
use App\Enums\FilmStatus;

class FilmPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Film $film): bool
    {
        if ($film->status === FilmStatus::Ready->value) {
            return true;
        }

        return $user?->isModerator() ?? false;
    }

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
}
