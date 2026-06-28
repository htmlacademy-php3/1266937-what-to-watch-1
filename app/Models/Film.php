<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Film
 *
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
 * @property bool $is_promo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Actor[] $actors
 * @property Collection|Comment[] $comments
 * @property Collection|Director[] $directors
 * @property Collection|Genre[] $genres
 *
 * @package App\Models
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
}
