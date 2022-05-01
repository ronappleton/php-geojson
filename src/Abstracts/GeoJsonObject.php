<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Abstracts;

use RonAppleton\GeoJson\Interfaces\GeoJsonObject as GeoJsonObjectInterface;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Interfaces\Jsonable;

abstract class GeoJsonObject implements GeoJsonObjectInterface, Jsonable
{
    public function __construct(private readonly GeoJsonType $type)
    {
    }
    
    abstract public function toJson(): string;
}
