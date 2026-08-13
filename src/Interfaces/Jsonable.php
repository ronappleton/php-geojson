<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Interfaces;

use JsonException;

interface Jsonable
{
    /**
     * @throws JsonException
     */
    public function toJson(): string;
}
