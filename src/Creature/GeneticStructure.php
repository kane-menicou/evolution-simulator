<?php

declare(strict_types=1);

namespace App\Creature;

use function mt_rand;

final class GeneticStructure
{

    /**
     * @var Allele[]
     */
    private array $left;

    private float $mutationChance;

    /**
     * @var Allele[]
     */
    private array $right;

    /**
     *
     * @param float|null $mutationChance (0 - 100) precision of 5
     * @param Allele[]   $left
     * @param Allele[]   $right
     */
    public function __construct(float $mutationChance, array $left, array $right)
    {
        $this->mutationChance = $mutationChance;
        $this->left = $left;
        $this->right = $right;

        foreach ($this->getGenes() as $gene) {
            $random = $this->getRandomMutationChance();

            if ($random <= $this->mutationChance) {
                $gene->mutate();
            }
        }
    }

    /**
     * @return Allele[]
     */
    public function getGenes(): array
    {
        $activeGenes = [];

        $alleles = [
            $this->left,
            $this->right,
        ];

        /**
         * @var Allele $leftAllele
         * @var Allele $rightAllele
         */
        foreach ($alleles as [$leftAllele, $rightAllele]) {
            if ($leftAllele->getDominance() >= $rightAllele->getDominance()) {
                $activeGenes[] = $leftAllele;
            } else {
                $activeGenes[] = $rightAllele;
            }
        }

        return $activeGenes;
    }

    private function getRandomMutationChance(): float
    {
        return mt_rand(1, 10000000000) / 100000000;
    }

    public function combine(GeneticStructure $geneticStructure): GeneticStructure
    {
        if (mt_rand(0, 1) === 1) {
            $right = $this->cloneAll($geneticStructure->left);
            $left = $this->cloneAll($geneticStructure->right);
        } else {
            $right = $this->cloneAll($geneticStructure->right);
            $left = $this->cloneAll($geneticStructure->left);
        }

        return new self($this->mutationChance, $left, $right);
    }

    private function cloneAll(array $array): array
    {
        $all = [];

        foreach ($array as $item) {
            $all[] = clone $item;
        }

        return $all;
    }
}
