<?php

namespace App\Actions;

use App\Models\Film;
use Illuminate\Support\Facades\Cache;

class SetPromoAction
{
    public function execute(Film $film): Film
    {
        Film::where('is_promo', true)->update(['is_promo' => false]);

        $film->update(['is_promo' => true]);

        Cache::forget('promo_film_id');

        return $film;
    }
}
