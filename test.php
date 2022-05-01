<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Enums\GeoJsonType;

$feature = Factory::make(GeoJsonType::Feature);

var_dump($feature);
