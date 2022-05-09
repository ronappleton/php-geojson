<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RonAppleton\GeoJson\Enums\PointExceptionType;
use Throwable;

use function sprintf;

class Point extends \RuntimeException
{
    public function __construct(
        private readonly PointExceptionType $exceptionType,
        private readonly ?string $point = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($this->findMessage(), $code, $previous);
    }

    private function findMessage(): string
    {
        return match($this->exceptionType) {
            PointExceptionType::PointSet => sprintf(PointExceptionType::PointSet->value, $this->point),
            default => sprintf(PointExceptionType::PointNotSet->value, $this->point)
        };
    }
}
