<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereBackgroundImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereImdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film wherePosterImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film wherePreviewImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film wherePreviewVideoLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereReleased($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereRunTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Film whereVideoLink($value)
 * @mixin \Eloquent
 */
class Film extends Model
{
    //
}
