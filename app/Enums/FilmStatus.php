<?php

namespace App\Enums;

enum FilmStatus: string
{
    case PENDING = 'pending';
    case ON_MODERATION = 'on moderation';
    case READY = 'ready';
}
