<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RonAppleton\GeoJson\Enums\PolygonExceptionType;
use RuntimeException;
use Throwable;

class Polygon extends RuntimeException
{
    public function __construct(private readonly PolygonExceptionType $exceptionType, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($this->findMessage(), $code, $previous);
    }

    private function findMessage(): string
    {
        return match($this->exceptionType) {
            PolygonExceptionType::PointsNotSet => PolygonExceptionType::PointsNotSet->value,
            default => PolygonExceptionType::PointsSet->value,
        };
    }
}
