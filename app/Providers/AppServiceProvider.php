<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use App\Repositories\OmdbFilmRepository;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        $this->app->bind(FilmRepositoryInterface::class, static function (): FilmRepositoryInterface {
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
        RateLimiter::for('film-api-limit', static fn() => Limit::perMinute(30));
    }
}
