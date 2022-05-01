<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory as ObjectFactory;
use RonAppleton\GeoJson\Objects\Point;

class FactoryTest extends TestCase
{
    public function testMakePoint(): void
    {
        $point = ObjectFactory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point);
    }
}
