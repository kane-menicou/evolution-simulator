<?php

declare(strict_types=1);

namespace App\Inspection;

use App\Creature\Creature;
use App\World\Entity\Food;
use App\World\World;

use function range;
use function str_repeat;

use const PHP_EOL;

final class CliWorldView implements WorldVisualisationInterface
{

    public function stringifyWorld(World $world): string
    {
        $output = str_repeat("-", $world->getWidth() + 3) . PHP_EOL;

        foreach (range(0, $world->getHeight()) as $y) {
            $output .= '|';

            foreach (range(0, $world->getWidth()) as $x) {
                $firstEntity = $world->getEntitiesAtCoordinate($x, $y)[0] ?? null;

                if ($firstEntity instanceof Food) {
                    $output .= '0';
                } else {
                    if ($firstEntity instanceof Creature) {
                        $output .= 'X';
                    } else {
                        $output .= ' ';
                    }
                }
            }

            $output .= "|" . PHP_EOL;
        }

        $output .= str_repeat("-", $world->getWidth() + 3);

        return $output;
    }
}
