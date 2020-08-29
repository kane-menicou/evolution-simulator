<?php

declare(strict_types=1);

namespace App\Math;

use function mt_rand;

final class AttributeValue
{

    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function add(AttributeValue $value): AttributeValue
    {
        return new AttributeValue($value->value + $this->value);
    }

    public function getFloat(): float
    {
        return $this->value;
    }

    public function cloneToRandomValue(): AttributeValue
    {
        $shouldInverse = (bool)mt_rand(0, 1);
        $value = mt_rand(0, 10000000000) / 10000000000;

        return new AttributeValue($shouldInverse ? $value : $value * -1);
    }
}
