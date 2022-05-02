<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Interfaces\Arrayable;

class MultiLineString extends GeoJsonObject implements Arrayable
{
    /**
     * @var array<int, LineString>
     */
    private array $lineStrings;

    /**
     * @return array<int, LineString>
     */
    public function getLineStrings(): array
    {
        return $this->lineStrings;
    }

    /**
     * @param array<int, LineString> $lineStrings
     * @return MultiLineString
     */
    public function setLineStrings(LineString ... $lineStrings): MultiLineString
    {
        $this->lineStrings = array_merge($this->lineStrings ?? [], $lineStrings);
        
        return $this;
    }
    
    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return array_map(static fn (LineString $lineString) => $lineString->toArray(), $this->lineStrings);
    }
}
