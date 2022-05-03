<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Interfaces;

interface Arrayable
{
    /**
     * @return array<int, mixed>
     */
    public function toArray(): array;
}
