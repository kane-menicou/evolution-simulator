<?php

declare(strict_types=1);

use App\God\CreatureCreator;
use App\God\WorldCreator;
use App\Inspection\HtmlTableWorldView;
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
    } catch (Throwable $e) {
        echo "World couldn't be created";
    }
} else {
    $world->addNewEpoch();
}

$worldHtml = (new HtmlTableWorldView())->stringifyWorld($world);

// Ouch my eyes, at least it works though...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Evolution Simulator</title>
</head>
<body>
<hr>
<a href="/">
    <button>New World</button>
</a>
<form action="/" method="POST">
    <label>
        <input type="text" name="world" value="<?=  base64_encode(serialize($world)) ?>" hidden="hidden"/>
    </label>
    <label>
        <input type="submit" name="submit"/>
    </label>
</form>
<?= $worldHtml ?>
</body>
</html>
