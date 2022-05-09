<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum PolygonExceptionType: string
{
    case PointsNotSet = 'Not points are set on the polygon.';
    case PointsSet = 'Polygons points are already set.';
}
