<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

class MultiLineString extends GeoJsonObject
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

    public function toArray(): array
    {
        return array_map(static fn (LineString $lineString) => $lineString->toArray(), $this->lineStrings);
    }
}
