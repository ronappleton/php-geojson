<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum PolygonExceptionType: string
{
    case ExteriorRingNotSet = 'The exterior ring must be set before adding interior rings.';
    case ExteriorRingSet = 'The exterior ring is already set.';
    case RingNotClosed = 'Polygon rings must be closed. The first and last positions must be identical.';
}
