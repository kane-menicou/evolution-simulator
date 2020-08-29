<?php

declare(strict_types=1);

namespace App\World;

use App\World\Entity\WorldEntityInterface;
use InvalidArgumentException;

use function array_intersect;
use function count;
use function range;

final class World
{

    /**
     * @var WorldEntityInterface[]
     */
    private array $entities;

    private int $height;

    private int $width;

    /**
     * @param WorldEntityInterface[] $entities
     * @param int                    $width
     * @param int                    $height
     */
    public function __construct(array $entities, int $width, int $height)
    {
        $this->entities = $entities;

        if ($width < 0 || $height < 0) {
            throw new InvalidArgumentException("$width and/or $height are not positive");
        }

        $this->width = $width;
        $this->height = $height;
    }

    public function addNewEpoch(): void
    {
        foreach ($this->entities as $entity) {
            if ( ! $entity->doesExist()) {
                continue;
            }

            $entity->onEpoch($this);
        }

        foreach ($this->entities as $actor) {
            foreach ($this->entities as $recipient) {
                if ($this->areTouching($actor, $recipient)) {
                    $actor->interact($recipient);
                }
            }
        }
    }

    private function areTouching(WorldEntityInterface ...$entities): bool
    {
        $xAxisList = [];
        $yAxisList = [];

        foreach ($entities as $entity) {
            if ( ! $entity->doesExist()) {
                return false;
            }

            $size = $entity->getSize();

            $x = $entity->getX();
            $y = $entity->getY();

            $xAxisList[] = range($x, $x + $size);
            $yAxisList[] = range($y, $y + $size);
        }

        $sharedXAxisList = array_intersect(...$xAxisList);
        $sharedYAxisList = array_intersect(...$yAxisList);

        return count($sharedXAxisList) > 0 && $sharedYAxisList > 0;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return WorldEntityInterface[]
     */
    public function getEntitiesAtCoordinate(int $x, int $y): array
    {
        $there = [];

        foreach ($this->entities as $entity) {
            if ( ! $entity->doesExist()) {
                continue;
            }

            $size = $entity->getSize();

            $entityX = $entity->getX();
            $entityY = $entity->getY();

            $xAxisList = range($entityX, $entityX + $size);
            $yAxisList = range($entityY, $entityY + $size);

            $xIntersection = array_intersect([$x], $xAxisList);
            $yIntersection = array_intersect([$y], $yAxisList);

            if ($xIntersection && $yIntersection) {
                $there[] = $entity;
            }
        }

        return $there;
    }
}
