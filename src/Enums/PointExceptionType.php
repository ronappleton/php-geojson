<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum PointExceptionType: string
{
    case PointSet = 'The %s point is already set.';
    case PointNotSet = 'The %s point is not set.';
}
