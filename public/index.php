<?php

declare(strict_types=1);

use App\Creature\Creature;
use App\God\CreatureCreator;
use App\God\WorldCreator;
use App\World\Entity\Food;
use App\World\World;

require_once '../vendor/autoload.php';

$world = $_POST['world'] ?? null;

if (is_string($world)) {
    try {
        $world = unserialize((string)base64_decode($world));
    } catch (Throwable $throwable) {
    }
}

if ( ! ($world instanceof World)) {
    echo "New World Created";
    try {
        $world = (new WorldCreator(new CreatureCreator()))->create(50, 50, 0.01, 0.01);
    } catch (Exception $e) {
        echo "World couldn't be created";
        exit;
    }
} else {
    $world->addNewEpoch();
}

?>

<?php // Ouch my eyes, at least it works though... ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Evolution Simulator</title>
</head>
<body>
<form action="/" method="POST">
    <label>
        <input type="text" name="world" value="<?php echo base64_encode(serialize($world)) ?>" hidden="hidden"/>
    </label>
    <label>
        Progress
        <input type="submit" name="submit"/>
    </label>
</form>
<table>
    <?php for ($x = 0; $x < $world->getWidth(); $x++):?>
        <tr>
            <?php for ($y = 0; $y < $world->getHeight(); $y++): ?>
                <td style="width: 20px; height: 20px; text-align: center">
                    <?php
                        foreach ($world->getEntitiesAtCoordinate($x, $y) as $entity) {
                            if ($entity instanceof Creature) {
                                echo "0";
                            }

                            if ($entity instanceof Food) {
                                echo "1";
                            }
                        }
                    ?>
                </td>
            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>
</body>
</html>
