<?php

namespace App\Policies;

use App\Models\User;
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
}
