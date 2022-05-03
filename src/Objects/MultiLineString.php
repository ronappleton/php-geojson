<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

use function array_map;
use function array_merge;

/**
 * @phpcs:disable SlevomatCodingStandard.Commenting.RequireOneLinePropertyDocComment.MultiLinePropertyComment
 */
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
     */
    public function setLineStrings(LineString ... $lineStrings): MultiLineString
    {
        $this->lineStrings = array_merge($this->lineStrings ?? [], $lineStrings);
        
        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        return array_map(static fn (LineString $lineString) => $lineString->toArray(), $this->lineStrings);
    }
}
