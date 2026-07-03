<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Film
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string|null $poster_image
 * @property string|null $preview_image
 * @property string|null $background_image
 * @property string|null $background_color
 * @property string|null $video_link
 * @property string|null $preview_video_link
 * @property string|null $description
 * @property int|null $run_time
 * @property int|null $released
 * @property string $imdb_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_promo
 * @property-read Collection<int, \App\Models\Actor> $actors
 * @property-read int|null $actors_count
 * @property-read Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read Collection<int, \App\Models\Director> $directors
 * @property-read int|null $directors_count
 * @property-read Collection<int, \App\Models\User> $favoritedByUsers
 * @property-read int|null $favorited_by_users_count
 * @property-read Collection<int, \App\Models\Genre> $genres
 * @property-read int|null $genres_count
 * @method static \Database\Factories\FilmFactory factory($count = null, $state = [])
 * @method static Builder<static>|Film newModelQuery()
 * @method static Builder<static>|Film newQuery()
 * @method static Builder<static>|Film query()
 * @method static Builder<static>|Film whereBackgroundColor($value)
 * @method static Builder<static>|Film whereBackgroundImage($value)
 * @method static Builder<static>|Film whereCreatedAt($value)
 * @method static Builder<static>|Film whereDescription($value)
 * @method static Builder<static>|Film whereId($value)
 * @method static Builder<static>|Film whereImdbId($value)
 * @method static Builder<static>|Film whereIsPromo($value)
 * @method static Builder<static>|Film whereName($value)
 * @method static Builder<static>|Film wherePosterImage($value)
 * @method static Builder<static>|Film wherePreviewImage($value)
 * @method static Builder<static>|Film wherePreviewVideoLink($value)
 * @method static Builder<static>|Film whereReleased($value)
 * @method static Builder<static>|Film whereRunTime($value)
 * @method static Builder<static>|Film whereStatus($value)
 * @method static Builder<static>|Film whereUpdatedAt($value)
 * @method static Builder<static>|Film whereVideoLink($value)
 * @method static Builder<static>|Film withRating()
 * @mixin \Eloquent
 */
class Film extends Model
{
    use HasFactory;

    protected $casts = [
        'run_time' => 'int',
        'released' => 'int'
    ];

    protected $fillable = [
        'name',
        'poster_image',
        'preview_image',
        'background_image',
        'background_color',
        'video_link',
        'preview_video_link',
        'description',
        'run_time',
        'released',
        'imdb_id',
        'status',
        'is_promo',
    ];

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class)->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(Director::class)->withTimestamps();
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_film');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class)->withTimestamps();
    }

    /**
     * Scope a query to include the average rating of the film.
     */
    #[Scope]
    protected function withRating(Builder $query): void
    {
        $query->withAvg('comments as rating', 'rating')->withCasts(['rating' => 'decimal:1']);
    }
}
