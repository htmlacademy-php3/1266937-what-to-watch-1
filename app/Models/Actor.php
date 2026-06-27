<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Actor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Actor extends Model
{
    //
}
