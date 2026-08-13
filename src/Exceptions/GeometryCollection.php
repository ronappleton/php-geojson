<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RonAppleton\GeoJson\Enums\GeometryCollectionExceptionType;
use RuntimeException;
use Throwable;

use function sprintf;

class GeometryCollection extends RuntimeException
{
    public function __construct(
        GeometryCollectionExceptionType $exceptionType,
        string $context = '',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(sprintf($exceptionType->value, $context), $code, $previous);
    }
}
