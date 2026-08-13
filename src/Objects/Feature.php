<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\FeatureExceptionType;
use RonAppleton\GeoJson\Exceptions\Feature as FeatureException;
use RonAppleton\GeoJson\Interfaces\GeoJsonObject as GeoJsonObjectInterface;

use function array_key_exists;
use function array_keys;
use function array_merge;

class Feature extends GeoJsonObject
{
    private null | string | int $id = null;

    private ?GeoJsonObjectInterface $geometry = null;

    /**
     * @var array<string, mixed>|null
     */
    private ?array $properties = null;

    public function setId(string | int $id): static
    {
        if ($this->id !== null) {
            throw new FeatureException(FeatureExceptionType::IdSet);
        }

        $this->id = $id;

        return $this;
    }

    public function getId(): string | int
    {
        return $this->id ?? throw new FeatureException(FeatureExceptionType::IdNotSet);
    }

    public function setGeometry(?GeoJsonObjectInterface $geometry): static
    {
        if ($this->geometry !== null) {
            throw new FeatureException(FeatureExceptionType::GeometrySet);
        }

        if ($geometry !== null && !$geometry->getType()->isGeometry()) {
            throw new FeatureException(FeatureExceptionType::NotAGeometry);
        }

        $this->geometry = $geometry;

        return $this;
    }

    public function getGeometry(): ?GeoJsonObjectInterface
    {
        return $this->geometry;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): static
    {
        $this->assertPropertiesNotSet($properties);

        $this->properties = array_merge($this->properties ?? [], $properties);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getProperties(): array
    {
        return $this->properties ?? throw new FeatureException(FeatureExceptionType::PropertiesNotSet);
    }

    public function setProperty(string $key, mixed $value): static
    {
        if (isset($this->properties) && array_key_exists($key, $this->properties)) {
            throw new FeatureException(FeatureExceptionType::PropertySet, $key);
        }

        $this->properties[$key] = $value;

        return $this;
    }

    public function getProperty(string $key): mixed
    {
        return $this->properties[$key] ?? throw new FeatureException(FeatureExceptionType::PropertyNotSet, $key);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->getType()->value,
        ];

        if ($this->id !== null) {
            $array['id'] = $this->id;
        }

        $array['geometry'] = $this->geometry?->toArray();
        $array['properties'] = $this->properties;

        return $this->withBoundingBox($array);
    }

    /**
     * @param array<string, mixed> $properties
     */
    private function assertPropertiesNotSet(array $properties): void
    {
        foreach (array_keys($properties) as $key) {
            if (isset($this->properties) && array_key_exists($key, $this->properties)) {
                throw new FeatureException(FeatureExceptionType::PropertySet, (string) $key);
            }
        }
    }
}
