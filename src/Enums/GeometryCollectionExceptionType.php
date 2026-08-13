<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum GeometryCollectionExceptionType: string
{
    case NotAGeometry = 'Only geometry objects may be added to a GeometryCollection.';
}
