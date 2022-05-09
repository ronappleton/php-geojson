<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RonAppleton\GeoJson\Enums\BoundingBoxExceptionType;
use RuntimeException;
use Throwable;

class BoundingBox extends RuntimeException
{
    public function __construct(private readonly BoundingBoxExceptionType $exceptionType, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($this->findMessage(), $code, $previous);
    }

    private function findMessage(): string
    {
        return match($this->exceptionType) {
            BoundingBoxExceptionType::PointsNotSet => BoundingBoxExceptionType::PointsNotSet->value,
            BoundingBoxExceptionType::AltitudesNotSet => BoundingBoxExceptionType::AltitudesNotSet->value,
            BoundingBoxExceptionType::AltitudesSet => BoundingBoxExceptionType::AltitudesSet->value,
            default => BoundingBoxExceptionType::PointsSet->value,
        };
    }
}
