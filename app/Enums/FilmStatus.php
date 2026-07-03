<?php

namespace App\Enums;

enum FilmStatus: string
{
    case Pending = 'pending';
    case OnModeration = 'on moderation';
    case Ready = 'ready';
}
