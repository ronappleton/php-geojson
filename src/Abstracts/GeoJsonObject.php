<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Abstracts;

use JsonException;
use RonAppleton\GeoJson\Interfaces\Arrayable;
use RonAppleton\GeoJson\Interfaces\GeoJsonObject as GeoJsonObjectInterface;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Interfaces\Jsonable;

use function json_encode;

use const JSON_THROW_ON_ERROR;

abstract class GeoJsonObject implements GeoJsonObjectInterface, Jsonable, Arrayable
{
    public function __construct(private readonly GeoJsonType $type)
    {
    }

    /**
     * @return array<int, mixed>
     */
    abstract public function toArray(): array;
    
    public function getType(): GeoJsonType
    {
        return $this->type;
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
