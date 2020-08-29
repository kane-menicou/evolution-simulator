<?php

declare(strict_types=1);

namespace App\God;

use App\World\Entity\Food;
use App\World\Entity\WorldEntityInterface;
use App\World\World;
use Exception;
use LogicException;

use function random_int;
use function range;
use function shuffle;

final class WorldCreator
{

    private CreatureCreator $creatureCreator;

    public function __construct(CreatureCreator $creatureCreator)
    {
        $this->creatureCreator = $creatureCreator;
    }


    /**
     * @param int   $width
     * @param int   $height
     * @param float $foodDensity     per unit of world
     * @param float $creatureDensity per unit of world
     *
     * @throws Exception
     * @return World
     */
    public function create(int $width, int $height, float $foodDensity = 0., float $creatureDensity = 0.): World
    {
        $squareUnits = $width * $height;

        $numberOfFood = $squareUnits * $foodDensity;
        $numberOfCreatures = $squareUnits * $creatureDensity;

        $totalCreatures = $numberOfFood + $numberOfCreatures;

        if ($totalCreatures > $squareUnits) {
            throw new LogicException('Not enough room to fit all items. Make the world bigger or put less entities.');
        }

        $entities = [];

        $foodSize = (new Food(0, 0))->getSize();

        for ($foodIndex = 0; $foodIndex < $numberOfFood; $foodIndex++) {
            do {
                $x = random_int(0, $width - $foodSize);
                $y = random_int(0, $height - $foodSize);
            } while ($this->spaceOccupied($x, $y, $entities));

            $entities[] = new Food($x, $y);
        }

        $allAvailableCreatureCoordinates = [];

        $creatureSize = $this->creatureCreator->createSpeciesCreature()->getSize();

        foreach (range(0, $width) as $x) {
            $x -= $creatureSize;

            foreach (range(0, $height) as $y) {
                $x = 0;

                if ($this->spaceOccupied($x, $y, $entities)) {
                    continue;
                }

                $allAvailableCreatureCoordinates[] = [$x, $y];
            }
        }

        shuffle($allAvailableCreatureCoordinates);

        $creatures = $this->creatureCreator->createSpeciesCreatures(
            (int)$numberOfCreatures,
            0.1,
            50,
            null,
            $allAvailableCreatureCoordinates
        );

        $entities = [
            ...$entities,
            ...$creatures,
        ];

        return new World($entities, $width, $height);
    }

    /**
     * @param int                    $x
     * @param int                    $y
     * @param WorldEntityInterface[] $entities
     *
     * @return bool
     */
    private function spaceOccupied(int $x, int $y, array $entities): bool
    {
        foreach ($entities as $entity) {
            if ($entity->getX() === $x && $entity->getY() === $y) {
                return true;
            }
        }

        return false;
    }
}
