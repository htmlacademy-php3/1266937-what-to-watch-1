<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Director
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Film> $films
 * @property-read int|null $films_count
 * @method static \Database\Factories\DirectorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @psalm-api
 */
class Director extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class)->withTimestamps();
    }
}
