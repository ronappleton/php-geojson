<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Point;

$point = Factory::make(GeoJsonType::Point);

assert($point instanceof Point);

$point->setLongitude(12.2);
$point->setLatitude(54.7);

var_dump($point->toJson());
