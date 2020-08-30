<?php

declare(strict_types=1);

namespace App\Inspection;

use App\Creature\Creature;
use App\World\Entity\Food;
use App\World\World;

class HtmlTableWorldView implements WorldVisualisationInterface
{

    public function stringifyWorld(World $world): string
    {
        $worldString = '<table>';

        for ($x = 0; $x < $world->getWidth(); $x++) {
            $worldString .= '<tr>';

            for ($y = 0; $y < $world->getHeight(); $y++) {
                $worldString .= '<td style="width: 20px; height: 20px; text-align: center">';

                foreach ($world->getEntitiesAtCoordinate($x, $y) as $entity) {
                    if ($entity instanceof Creature) {
                        $worldString .= "0";
                    }

                    if ($entity instanceof Food) {
                        $worldString .= "1";
                    }
                }
            }
        }

        return $worldString;
    }
}
