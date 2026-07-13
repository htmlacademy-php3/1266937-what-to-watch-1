<?php

namespace App\Actions;

use App\Models\Film;
use Illuminate\Support\Facades\Cache;

final class SetPromoAction
{
    /**
     * Set the film as a promo.
     */
    public function execute(Film $film): Film
    {
        Film::query()->where('is_promo', true)->update(['is_promo' => false]);

        $film->update(['is_promo' => true]);

        Cache::forget('promo_film_id');

        return $film;
    }
}
