<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\God\CreatureCreator;
use App\God\WorldCreator;
use App\Inspection\CliWorldView;

$worldCreator = new WorldCreator(new CreatureCreator());
$world = $worldCreator->create(50, 25, 0.01, 0.01);

echo (new CliWorldView())->stringifyWorld($world);

echo PHP_EOL;

/**
 * @param \App\World\World $world
 */
function progress(\App\World\World $world): void
{
    sleep(1);
    echo PHP_EOL;
    echo PHP_EOL;
    echo PHP_EOL;
    echo PHP_EOL;
    echo PHP_EOL;
    echo (new CliWorldView())->stringifyWorld($world);
    $world->addNewEpoch();
}


progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);
progress($world);


