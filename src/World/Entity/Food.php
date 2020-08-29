<?php

declare(strict_types=1);

namespace App\World\Entity;

use App\World\World;

final class Food implements WorldEntityInterface
{

    private int $x;

    private int $y;

    private bool $ate = false;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function eat(): void
    {
        $this->ate = true;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getSize(): int
    {
        return 1;
    }

    public function interact(WorldEntityInterface $entity): void
    {
    }

    public function doesExist(): bool
    {
        return ! $this->ate;
    }

    public function onEpoch(World $world): void
    {
    }
}
