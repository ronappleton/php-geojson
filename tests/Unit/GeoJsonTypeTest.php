<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;

class GeoJsonTypeTest extends TestCase
{
    public function testValues(): void
    {
        $array = GeoJsonType::values();
        
        $this->assertIsArray($array);
    }
}
