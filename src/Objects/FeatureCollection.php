<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

use function array_map;
use function array_merge;

class FeatureCollection extends GeoJsonObject
{
    /**
     * @var array<int, Feature>
     */
    private array $features = [];

    /**
     * @return array<int, Feature>
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): static
    {
        $this->features[] = $feature;

        return $this;
    }

    public function addFeatures(Feature ... $features): static
    {
        $this->features = array_merge($this->features, $features);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->getType()->value,
        ];

        $array['features'] = array_map(static fn (Feature $feature) => $feature->toArray(), $this->features);

        return $this->withBoundingBox($array);
    }
}
