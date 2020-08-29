<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\Creature\Creature;
use App\God\CreatureCreator;

$god = new CreatureCreator();

$creatures = $god->createSpeciesCreatures(1000, 1, 10);

$printMeanSpeed = function (array $creatures): void {
    if (count($creatures) <= 0) {
        echo 0;

        return;
    }

    $speeds = array_map(
        function (Creature $creature) {
            return $creature->getSpeedAttribute()->getFloat();
        },
        $creatures
    );

    $total = array_sum($speeds);

    echo $total / count($creatures);
};

//$printMeanSpeed($creatures);

$naturalSelection = function (array $creatures) {
//    usort(
//        $creatures,
//        fn(Creature $a, Creature $b) => $a->getSpeedAttribute()->getFloat() <=> $b->getSpeedAttribute()->getFloat()
//    );

    usort(
        $creatures,
        fn(Creature $a, Creature $b) =>  $b->getSpeedAttribute()->getFloat() <=> $a->getSpeedAttribute()->getFloat()
    );

    return array_filter(
        $creatures,
        fn(Creature $creature) =>  $creature->getSpeedAttribute()->getFloat() > 0.4
    );

};

for ($generation = 0; $generation < 50; $generation++) {
    $nextGeneration = [];

    /** @var Creature $creature */
    foreach ($creatures as $index => $creature) {
        $offSpringCount = round($creature->getReproductionAttribute()->getFloat() * mt_rand(1, 3));

        for ($child = 0; $child < $offSpringCount; $child++) {
            $mate = $creatures[array_rand($creatures)] ?? null;

            if ($mate instanceof Creature && $mate !== $creature) {
                $nextGeneration[] = $creature->reproduceWith($mate);
            }
        }
    }

    echo "Generation $generation before NS: ";
    $printMeanSpeed($nextGeneration);
    echo PHP_EOL;
    $numbers = count($nextGeneration);
    echo "There are $numbers animals left" . PHP_EOL;
    echo PHP_EOL;

    $nextGeneration = $naturalSelection($nextGeneration);

    echo "Generation $generation after NS: ";
    $printMeanSpeed($nextGeneration);
    echo PHP_EOL;
    $numbers = count($nextGeneration);
    echo "There are $numbers animals left" . PHP_EOL;
    echo PHP_EOL;


    $creatures = $nextGeneration;

}
