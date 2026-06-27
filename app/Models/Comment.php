<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Comment
 *
 * @property int $id
 * @property string $text
 * @property int $rating
 * @property int $user_id
 * @property int $film_id
 * @property int|null $comment_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Comment|null $comment
 * @property Film $film
 * @property User $user
 * @property Collection|Comment[] $comments
 *
 * @package App\Models
 */
class Comment extends Model
{
    protected $casts = [
        'rating' => 'int',
        'user_id' => 'int',
        'film_id' => 'int',
        'comment_id' => 'int'
    ];

    protected $fillable = [
        'text',
        'rating',
        'user_id',
        'film_id',
        'comment_id'
    ];

    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }
}
