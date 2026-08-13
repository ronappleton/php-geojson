<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

use function array_map;
use function array_merge;

class MultiLineString extends GeoJsonObject
{
    /**
     * @var array<int, LineString>
     */
    private array $lineStrings = [];

    /**
     * @return array<int, LineString>
     */
    public function getLineStrings(): array
    {
        return $this->lineStrings;
    }

    public function setLineStrings(LineString ... $lineStrings): static
    {
        $this->lineStrings = array_merge($this->lineStrings, $lineStrings);

        return $this;
    }

    /**
     * @return array<int, array<int, array<int, float>>>
     */
    public function coordinates(): array
    {
        return array_map(static fn (LineString $lineString) => $lineString->coordinates(), $this->lineStrings);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->geometryArray($this->coordinates());
    }
}
