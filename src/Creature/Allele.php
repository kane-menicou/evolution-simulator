<?php

declare(strict_types=1);

namespace App\Creature;

use function mt_rand;

final class Allele
{

    private int $dominance;

    private GeneticTrait $trait;

    public function __construct(GeneticTrait $trait, int $dominance)
    {
        $this->trait = $trait;
        $this->dominance = $dominance;
    }

    public static function createRandom(): Allele
    {
        return new self(GeneticTrait::createRandom(), mt_rand(0, 1000));
    }

    public function getDominance(): int
    {
        return $this->dominance;
    }

    public function getTrait(): GeneticTrait
    {
        return $this->trait;
    }

    public function mutate(): void
    {
        $this->trait->randomiseValues();
    }

    public function __clone()
    {
        $this->trait = clone $this->trait;
    }
}
