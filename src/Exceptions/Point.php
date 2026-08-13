<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RonAppleton\GeoJson\Enums\PointExceptionType;
use RuntimeException;
use Throwable;

use function sprintf;

class Point extends RuntimeException
{
    public function __construct(
        PointExceptionType $exceptionType,
        string $context = '',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(sprintf($exceptionType->value, $context), $code, $previous);
    }
}
