# php-geojson
GeoJSON object builder.

[![License](http://poser.pugx.org/ronappleton/php-geojson/license)](https://packagist.org/packages/ronappleton/php-geojson)
[![PHP Version Require](http://poser.pugx.org/ronappleton/php-geojson/require/php)](https://packagist.org/packages/ronappleton/php-geojson)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/fe5f212d36ba4eaca8d982362b254ea0)](https://www.codacy.com/gh/ronappleton/php-geojson/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ronappleton/php-geojson&amp;utm_campaign=Badge_Grade)

## Introduction

This library is a simple builder for GeoJSON objects for use within php.

The inspiration for this is another project I am working on [Tile38 PHP Client](https://github.com/ronappleton/tile38-php-client) in that project part of the requirement is valid GeoJSON objects, so this library is being made to fulfill that requirement.

For more information about GeoJSON object, please see [This Website](https://terraformer-js.github.io/glossary/) or the official [RFC 7946](https://datatracker.ietf.org/doc/html/rfc7946)

The initial release of this library, will provide the functionality for use within the Tile38 project, the subsequent release will be to ensure full implementation of the rfc, and then the 3rd release will provide unserialisation of GeoJSON data, back into objects.


## Installation


Please use composer to pull in the package `composer require ronappleton/php-geojson` please note that this library requires php ^8.1, I may at some point release for php ^7.4 but for the moment it is ^8.1, if anyone would like to branch this to other php version


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
```

It also provides a factory for convenience, this is `RonAppleton\GeoJson\Objects\Factory::class`

Using the factory provides a simple interface for creating the objects:

```php
$point = Factory::make(GeoJsonType::Point);
```

And when making LineStrings for example, you can also pass a count as the second parameter to the factory:

```php
[$point, $point2, $point3, $point4] = Factory::make(Point::class, 4);
```

All objects provide a `toArray()` method and a `toJson()` method, when making objects of combined types, like Polygons etc, the toArray and toJson methods cascade through all objects so they will all be converted automatically.
