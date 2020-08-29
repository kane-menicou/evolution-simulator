<?php

declare(strict_types=1);

namespace App\Creature;

use App\Math\AttributeValue;

final class GeneticTrait
{

    private AttributeValue $speed;

    private AttributeValue $reproductionChance;

    private AttributeValue $deathChance;

    public function __construct(float $speed, float $reproductionChance, float $deathChance)
    {
        $this->speed = new AttributeValue($speed);
        $this->reproductionChance = new AttributeValue($reproductionChance);
        $this->deathChance = new AttributeValue($deathChance);
    }

    public static function createRandom(): self
    {
        $trait = new GeneticTrait(0, 0, 0);

        $trait->randomiseValues();

        return $trait;
    }

    public function randomiseValues(): void
    {
        $this->speed = $this->speed->cloneToRandomValue();
        $this->reproductionChance = $this->reproductionChance->cloneToRandomValue();
        $this->deathChance = $this->deathChance->cloneToRandomValue();
    }

    public function getSpeedAttribute(): AttributeValue
    {
        return $this->speed;
    }

    public function getReproductionAttribute(): AttributeValue
    {
        return $this->reproductionChance;
    }

    public function getDeathAttribute(): AttributeValue
    {
        return $this->deathChance;
    }
}
