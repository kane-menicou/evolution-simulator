<?php

declare(strict_types=1);

namespace App\World\Entity;

use App\World\World;

interface WorldEntityInterface
{

    public function getX(): int;

    public function getY(): int;

    /**
     * @return int ²
     */
    public function getSize(): int;

    public function interact(WorldEntityInterface $entity): void;

    public function doesExist(): bool;

    public function onEpoch(World $world): void;
}
