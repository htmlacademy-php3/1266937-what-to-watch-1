<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use App\Repositories\OmdbFilmRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FilmRepositoryInterface::class, function ($app) {
            return new OmdbFilmRepository(
                config('services.omdb.key')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('film-api-limit', fn(object $job) => Limit::perMinute(30));
    }
}
