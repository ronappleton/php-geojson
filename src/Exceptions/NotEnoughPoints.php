<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Exceptions;

use RuntimeException;
use Throwable;

use function sprintf;

class NotEnoughPoints extends RuntimeException
{
    public function __construct(int $points = 1, int $required = 2, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('You have not provided enough points, %d provided, %d required.', $points, $required);
        
        parent::__construct($message, $code, $previous);
    }
}
