<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum PointExceptionType: string
{
    case PointSet = 'The %s coordinate is already set.';
    case PointNotSet = 'The %s coordinate is not set.';
    case InvalidLongitude = 'Longitude must be between -180 and 180, %s given.';
    case InvalidLatitude = 'Latitude must be between -90 and 90, %s given.';
    case AltitudeSet = 'The altitude is already set.';
}
