# php-geojson
GeoJSON object builder.

[![License](http://poser.pugx.org/ronappleton/php-geojson/license)](https://packagist.org/packages/ronappleton/php-geojson)
[![PHP Version Require](http://poser.pugx.org/ronappleton/php-geojson/require/php)](https://packagist.org/packages/ronappleton/php-geojson)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/fe5f212d36ba4eaca8d982362b254ea0)](https://www.codacy.com/gh/ronappleton/php-geojson/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ronappleton/php-geojson&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/fe5f212d36ba4eaca8d982362b254ea0)](https://www.codacy.com/gh/ronappleton/php-geojson/dashboard?utm_source=github.com&utm_medium=referral&utm_content=ronappleton/php-geojson&utm_campaign=Badge_Coverage)
[![Dependents](http://poser.pugx.org/ronappleton/php-geojson/dependents)](https://packagist.org/packages/ronappleton/php-geojson)

## Introduction

This library is a simple builder for GeoJSON objects for use within php.

The inspiration for this is another project I am working on [Tile38 PHP Client](https://github.com/ronappleton/tile38-php-client) in that project part of the requirement is valid GeoJSON objects, so this library is being made to fulfill that requirement.

For more information about GeoJSON objects, please see [This Website](https://terraformer-js.github.io/glossary/) or the official [RFC 7946](https://datatracker.ietf.org/doc/html/rfc7946)

The initial release of this library provides the functionality for use within the Tile38 project, this release ensures full implementation of the RFC 7946 serialisation and unserialisation of GeoJSON data.

## Installation

Install with composer: `composer require ronappleton/php-geojson`. This library requires PHP >= 8.1.

## Usage

The library provides:

```php
RonAppleton\GeoJson\Enums\GeoJsonType::class
RonAppleton\GeoJson\Objects\BoundingBox::class
RonAppleton\GeoJson\Objects\Feature::class
RonAppleton\GeoJson\Objects\FeatureCollection::class
RonAppleton\GeoJson\Objects\GeometryCollection::class
RonAppleton\GeoJson\Objects\LineString::class
RonAppleton\GeoJson\Objects\MultiLineString::class
RonAppleton\GeoJson\Objects\MultiPoint::class
RonAppleton\GeoJson\Objects\MultiPolygon::class
RonAppleton\GeoJson\Objects\Point::class
RonAppleton\GeoJson\Objects\Polygon::class
RonAppleton\GeoJson\Objects\Parser::class
```

It also provides a factory for convenience, this is `RonAppleton\GeoJson\Objects\Factory::class`

And a parser for unserialisation, this is `RonAppleton\GeoJson\Objects\Parser::class`

Using the factory provides a simple interface for creating the objects:

```php
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;

$point = Factory::make(GeoJsonType::Point);
$point->setPoints(100.0, 0.0);
```

And when making several objects of the same type, you can pass a count as the second parameter to the factory:

```php
[$point, $point2, $point3, $point4] = Factory::make(GeoJsonType::Point, 4);
```

All objects provide a `toArray()` method and a `toJson()` method, when making objects of combined types, like Polygons etc, the toArray and toJson methods cascade through all objects so they will all be converted automatically.

### Point

`Point` is used as the position primitive throughout the library, so `toArray()` returns a bare position `[longitude, latitude, ?altitude]` rather than a full GeoJSON object:

```php
$point = Factory::make(GeoJsonType::Point);
$point->setPoints(100.0, 0.0);
$point->setAltitude(10.0);

$point->toArray(); // [100.0, 0.0, 10.0]
$point->toJson();  // [100,0,10]
```

### LineString

```php
$lineString = Factory::make(GeoJsonType::LineString);
$lineString->addPoints($point, $point2);

$lineString->toArray(); // ['type' => 'LineString', 'coordinates' => [[...], [...]]]
```

### Polygon

```php
$polygon = Factory::make(GeoJsonType::Polygon);
$polygon->setExteriorRing($point, $point2, $point3, $point);
$polygon->addInteriorRing($holePoint, $holePoint2, $holePoint3, $holePoint);

$polygon->toArray(); // ['type' => 'Polygon', 'coordinates' => [[...], [...]]]
```

Rings must contain at least four positions and must be closed (the first and last positions identical).

### Feature

```php
$feature = Factory::make(GeoJsonType::Feature);
$feature->setId('0001');
$feature->setGeometry($lineString);
$feature->setProperty('name', 'somewhere');

$feature->toArray(); // ['type' => 'Feature', 'id' => '0001', 'geometry' => [...], 'properties' => [...]]
```

The geometry must be a geometry object (e.g. a `LineString`, `Polygon` or `GeometryCollection`) or `null`.

### FeatureCollection

```php
$collection = Factory::make(GeoJsonType::FeatureCollection);
$collection->addFeatures($feature, $feature2);

$collection->toArray(); // ['type' => 'FeatureCollection', 'features' => [...]]
```

### BoundingBox

```php
$boundingBox = Factory::make(GeoJsonType::BoundingBox);
$boundingBox->setPoints($southwest, $northeast);

$feature->setBoundingBox($boundingBox);
$feature->toArray(); // includes 'bbox' => [west, south, east, north]
```

For three-dimensional data pass a minimum and maximum altitude:

```php
$boundingBox->setAltitudes(-4.0, 6.0);
$boundingBox->toArray(); // [west, south, -4.0, east, north, 6.0]
```

## Unserialisation

The library provides a parser for converting GeoJSON strings and arrays back into objects. This gives clean round-trip symmetry with `toJson()` and `toArray()`:

```php
use RonAppleton\GeoJson\Objects\Parser;

// From a JSON string
$featureCollection = Parser::fromJson('{"type":"FeatureCollection","features":[...]}');
$featureCollection->toArray(); // same structure as toJson() output

// From a decoded array (e.g. from json_decode)
$point = Parser::fromArray(['type' => 'Point', 'coordinates' => [100.0, 0.0]]);
```

A bare position array (2 or 3 elements) is also accepted and returns a `Point`, matching the library's position-primitive convention:

```php
$point = Parser::fromArray([100.0, 0.0, 10.0]);
$point->toArray(); // [100.0, 0.0, 10.0]
```

The parser validates GeoJSON structure and delegates to the existing setters for range checking (e.g. longitude/latitude bounds, bounding box ordering). Invalid input throws `RonAppleton\GeoJson\Exceptions\Parse`.

## Testing

The library ships with a PHPUnit suite and a code style ruleset (PHP_CodeSniffer):

```bash
composer test     # run the test suite
composer cs       # check code style
composer cs:fix   # automatically fix code style
```
