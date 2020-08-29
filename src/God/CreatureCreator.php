<?php

declare(strict_types=1);

namespace App\God;

use App\Creature\Allele;
use App\Creature\Creature;
use App\Creature\GeneticStructure;

use function array_rand;

final class CreatureCreator
{

    /**
     * @param int        $number
     * @param float      $mutationChance
     * @param int        $genes
     * @param array|null $genePool
     * @param array[]    $coordinates
     *
     * @return array
     */
    public function createSpeciesCreatures(
        int $number,
        float $mutationChance = 0.1,
        int $genes = 50,
        ?array $genePool = null,
        array $coordinates = []
    ): array {
        if ($genePool === null) {
            $genePool = $this->createRandomGenePool($genes);
        }

        $creatures = [];

        for ($i = 0; $i < $number; $i++) {
            [$x, $y] = $coordinates[$i] ?? [0, 0];

            $creatures[] = $this->createSpeciesCreature($mutationChance, $genes, $genePool, $x, $y);
        }

        return $creatures;
    }

    private function createRandomGenePool(int $genes): array
    {
        $genePool = [];

        for ($i = 0; $i <= ($genes * 2); $i++) {
            $genePool[] = Allele::createRandom();;
        }

        return $genePool;
    }

    /**
     * @param float    $mutationChance
     * @param int      $genes
     * @param Allele[] $genePool (null for random)
     *
     * @param int      $x
     * @param int      $y
     *
     * @return Creature
     */
    public function createSpeciesCreature(
        float $mutationChance = 0.1,
        int $genes = 50,
        ?array $genePool = null,
        int $x = 0,
        int $y = 0
    ): Creature {
        if ($genePool === null) {
            $genePool = $this->createRandomGenePool($genes);
        }

        $leftAlleles = [];
        $rightAlleles = [];

        for ($i = 0; $i <= ($genes * 2); $i++) {
            [$randomAlleles1, $randomAlleles2] = array_rand($genePool, 2);

            $leftAlleles[] = clone $genePool[$randomAlleles1];
            $rightAlleles[] = clone $genePool[$randomAlleles2];
        }

        return new Creature(new GeneticStructure($mutationChance, $leftAlleles, $rightAlleles), $x, $y);
    }
}
