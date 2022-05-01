<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Interfaces\GeoJsonObject;

class Factory
{
    public static function make(GeoJsonType $type): GeoJsonObject
    {
        return new (self::getClass($type))($type);
    }
    
    private static function getClass(GeoJsonType $type): string
    {
        return 'RonAppleton\\GeoJson\\Objects\\' . $type->name;
    }
}
