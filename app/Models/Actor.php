<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Actor
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Film> $films
 * @property-read int|null $films_count
 * @method static \Database\Factories\ActorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @psalm-api
 */
class Actor extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class)->withTimestamps();
    }
}
