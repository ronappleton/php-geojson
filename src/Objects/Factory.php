<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Interfaces\GeoJsonObject;

class Factory
{
    /**
     * @return GeoJsonObject|array<int, GeoJsonObject>
     */
    public static function make(GeoJsonType $type, int $count = 1): GeoJsonObject|array
    {
        if ($count > 1) {
            return self::getManyObjects($type, $count);
        }
        
        return new (self::getClass($type))($type);
    }
    
    private static function getClass(GeoJsonType $type): string
    {
        return 'RonAppleton\\GeoJson\\Objects\\' . $type->name;
    }

    /**
     * @return array<int, GeoJsonObject>
     */
    private static function getManyObjects(GeoJsonType $type, int $count): array
    {
        $objects = [];
        
        for ($i = 0; $i < $count; $i++) {
            $objects[] = new (self::getClass($type))($type);
        }

        return $objects;
    }
}
