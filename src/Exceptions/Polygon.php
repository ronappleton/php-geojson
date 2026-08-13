<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RonAppleton\GeoJson\Enums\PolygonExceptionType;
use RuntimeException;
use Throwable;

use function sprintf;

class Polygon extends RuntimeException
{
    public function __construct(
        PolygonExceptionType $exceptionType,
        string $context = '',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(sprintf($exceptionType->value, $context), $code, $previous);
    }
}
