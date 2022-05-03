<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RonAppleton\GeoJson\Enums\FeatureExceptionType;
use RuntimeException;
use Throwable;

class Feature extends RuntimeException
{
    public function __construct(
        private readonly FeatureExceptionType $exceptionType,
        private readonly string|null $property = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($this->findMessage(), $code, $previous);
    }
    
    private function findMessage(): string
    {
        return match($this->exceptionType) {
            FeatureExceptionType::IdSet => FeatureExceptionType::IdSet->value,
            FeatureExceptionType::IdNotSet => FeatureExceptionType::IdNotSet->value,
            FeatureExceptionType::GeometrySet => FeatureExceptionType::GeometrySet->value,
            FeatureExceptionType::GeometryNotSet => FeatureExceptionType::GeometryNotSet->value,
            FeatureExceptionType::BoundingBoxSet => FeatureExceptionType::BoundingBoxSet->value,
            FeatureExceptionType::BoundingBoxNotSet => FeatureExceptionType::BoundingBoxNotSet->value,
            FeatureExceptionType::PropertiesNotSet => FeatureExceptionType::PropertiesNotSet->value,
            FeatureExceptionType::PropertySet => 
            sprintf(FeatureExceptionType::PropertySet->value, $this->property),
            FeatureExceptionType::PropertyNotSet =>
            sprintf(FeatureExceptionType::PropertyNotSet->value, $this->property),
        };
    }
}
