<?php

declare(strict_types=1);

namespace App\Inspection;

use App\World\World;

interface WorldVisualisationInterface
{

    public function stringifyWorld(World $world): string;
}
