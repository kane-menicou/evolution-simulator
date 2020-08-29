<?php

declare(strict_types=1);

namespace App\Creature;

use App\Math\AttributeValue;
use App\World\Entity\Food;
use App\World\Entity\WorldEntityInterface;
use App\World\World;
use Exception;

use function array_map;
use function array_reduce;
use function max;
use function random_int;
use function round;

final class Creature implements WorldEntityInterface
{

    /**
     * @var Food[]
     */
    private array $food = [];

    private GeneticStructure $genes;

    private int $x;

    private int $y;

    public function __construct(GeneticStructure $genes, int $x = 0, int $y = 0)
    {
        $this->genes = $genes;
        $this->x = $x;
        $this->y = $y;
    }

    public function getReproductionAttribute(): AttributeValue
    {
        $attributes = array_map(
            fn(Allele $allele) => $allele->getTrait()->getReproductionAttribute(),
            $this->genes->getGenes()
        );

        return array_reduce(
            $attributes,
            function (?AttributeValue $a, AttributeValue $b): AttributeValue {
                if ($a === null) {
                    return $b;
                }

                return $a->add($b);
            },
        );
    }

    public function getDeathAttribute(): AttributeValue
    {
        return array_reduce(
            $this->genes->getGenes(),
            fn(Allele $a, ?Allele $b): AttributeValue => $a->getTrait()->getDeathAttribute()->add(
                $b->getTrait()->getDeathAttribute()
            ),
        );
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function reproduceWith(Creature $creature): Creature
    {
        return new Creature($creature->genes->combine($this->genes));
    }

    public function interact(WorldEntityInterface $entity): void
    {
        if ($entity instanceof Food) {
            $entity->eat();

            $this->food[] = $entity;
        }
    }

    public function doesExist(): bool
    {
        return true;
    }

    /**
     * @param World $world
     *
     * @throws Exception
     */
    public function onEpoch(World $world): void
    {
        $speed = max((int)round($this->getSpeedAttribute()->getFloat()), 5);

        $canMovePositiveX = ($speed + $this->x + $this->getSize()) <= $world->getWidth();
        $canMovePositiveY = ($speed + $this->y + $this->getSize()) <= $world->getHeight();

        $shouldAddToX = ((bool)random_int(0, 2) && $canMovePositiveX) || ! $this->canMoveNegativeFrom($this->x);
        $shouldAddToY = ((bool)random_int(0, 2) && $canMovePositiveY) || ! $this->canMoveNegativeFrom($this->y);

        if ($shouldAddToX) {
            $this->x = $this->x + $speed;
        } else {
            $this->x = $this->x - $speed;
        }

        if ($shouldAddToY) {
            $this->y = $this->y + $speed;
        } else {
            $this->y = $this->y - $speed;
        }
    }

    public function getSpeedAttribute(): AttributeValue
    {
        $attributes = array_map(
            fn(Allele $allele) => $allele->getTrait()->getSpeedAttribute(),
            $this->genes->getGenes()
        );

        return array_reduce(
            $attributes,
            function (?AttributeValue $a, AttributeValue $b): AttributeValue {
                if ($a === null) {
                    return $b;
                }

                return $a->add($b);
            },
        );
    }

    public function getSize(): int
    {
        return 1;
    }

    private function canMoveNegativeFrom(int $position): bool
    {
        $speed = $this->getSpeedAttribute()->getFloat();

        return ($speed - ($position + $this->getSize())) < 0;
    }
}
