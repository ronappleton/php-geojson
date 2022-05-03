<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RonAppleton\GeoJson\Enums\FeatureExceptionType;
use RuntimeException;
use Throwable;

use function sprintf;

/**
 * @phpcs:disable SlevomatCodingStandard.Functions.RequireSingleLineCall.RequiredSingleLineCall
 */
class Feature extends RuntimeException
{
    public function __construct(
        private readonly FeatureExceptionType $exceptionType,
        private readonly null|string $property = null,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($this->findMessage(), $code, $previous);
    }
    
    private function findMessage(): string
    {
        if ($this->property) {
            return match($this->exceptionType) {
                FeatureExceptionType::PropertySet => sprintf(
                    FeatureExceptionType::PropertySet->value,
                    $this->property,
                ),
                default => sprintf(FeatureExceptionType::PropertyNotSet->value, $this->property),
            };
        }
        
        return $this->exceptionType->value;
    }
}
