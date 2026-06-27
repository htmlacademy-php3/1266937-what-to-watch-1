<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Director whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Director extends Model
{
    //
}
