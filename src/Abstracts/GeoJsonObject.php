<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Abstracts;

use JsonException;
use RonAppleton\GeoJson\Enums\BoundingBoxExceptionType;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\BoundingBox as BoundingBoxException;
use RonAppleton\GeoJson\Interfaces\Arrayable;
use RonAppleton\GeoJson\Interfaces\GeoJsonObject as GeoJsonObjectInterface;
use RonAppleton\GeoJson\Interfaces\Jsonable;
use RonAppleton\GeoJson\Objects\BoundingBox;

use function json_encode;

use const JSON_THROW_ON_ERROR;

abstract class GeoJsonObject implements GeoJsonObjectInterface, Jsonable, Arrayable
{
    private ?BoundingBox $boundingBox = null;

    public function __construct(private readonly GeoJsonType $type)
    {
    }

    /**
     * @return array<int|string, mixed>
     */
    abstract public function toArray(): array;

    public function getType(): GeoJsonType
    {
        return $this->type;
    }

    public function setBoundingBox(BoundingBox $boundingBox): static
    {
        if ($this->boundingBox !== null) {
            throw new BoundingBoxException(BoundingBoxExceptionType::AlreadySet);
        }

        $this->boundingBox = $boundingBox;

        return $this;
    }

    public function getBoundingBox(): ?BoundingBox
    {
        return $this->boundingBox;
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * @param array<int, mixed> $coordinates
     *
     * @return array<string, mixed>
     */
    protected function geometryArray(array $coordinates): array
    {
        $array = [
            'type' => $this->getType()->value,
        ];

        $array['coordinates'] = $coordinates;

        return $this->withBoundingBox($array);
    }

    /**
     * @param array<string, mixed> $array
     *
     * @return array<string, mixed>
     */
    protected function withBoundingBox(array $array): array
    {
        if ($this->boundingBox !== null) {
            $array['bbox'] = $this->boundingBox->toArray();
        }

        return $array;
    }
}
