<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\FeatureExceptionType;
use RonAppleton\GeoJson\Exceptions\Feature as FeatureException;

use function array_merge;
use function array_key_exists;
use function key;

/**
 * phpcs:disable SlevomatCodingStandard.Commenting.RequireOneLinePropertyDocComment.MultiLinePropertyComment
 */
class Feature extends GeoJsonObject
{
    private string $id;
    
    private BoundingBox $boundingBox;
    
    private GeoJsonObject $geometry;

    /**
     * @var array<int, mixed>
     */
    private array $properties;
    
    public function setId(string $id): Feature
    {
        if (isset($this->id)) {
            throw new FeatureException(FeatureExceptionType::IdNotSet);
        }
        
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): string
    {
        return $this->id ?? throw new FeatureException(FeatureExceptionType::IdNotSet);
    }
    
    public function setBoundingBox(BoundingBox $boundingBox): Feature
    {
        if (isset($this->boundingBox)) {
            throw new FeatureException(FeatureExceptionType::BoundingBoxSet);
        }
        
        $this->boundingBox = $boundingBox;
        
        return $this;
    }
    
    public function getBoundingBox(): BoundingBox
    {
        return $this->boundingBox ?? throw new FeatureException(FeatureExceptionType::BoundingBoxNotSet);
    }
    
    public function setGeometry(GeoJsonObject $geometry): Feature
    {
        if (isset($this->geometry)) {
            throw new FeatureException(FeatureExceptionType::GeometrySet);
        }
        
        $this->geometry = $geometry;
        
        return $this;
    }
    
    public function getGeometry(): GeoJsonObject
    {
        return $this->geometry ?? throw new FeatureException(FeatureExceptionType::GeometryNotSet);
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): GeoJsonObject
    {
        if (!isset($this->properties)) {
            $this->properties = array_merge($this->properties ?? [], $properties);
            
            return $this;
        }
        
        foreach ($properties as $value) {
            if (array_key_exists(key($value), $this->properties)) {
                throw new FeatureException(FeatureExceptionType::PropertySet, key($value));
            }
        }
        
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getProperties(): array
    {
        return $this->properties ?? throw new FeatureException(FeatureExceptionType::PropertiesNotSet);
    }
    
    public function setProperty(string $key, mixed $value): GeoJsonObject
    {
        if ($this->properties[$key] ?? null) {
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
            'type' => $this->getType(),
            'geometry' => $this->geometry?->toArray(),
            'properties' => $this->properties ?? null,
        ];
        
        if ($this->boundingBox ?? null) {
            $array['bbox'] = $this->boundingBox->toArray();
        }
        
        return $array;
    }
}
