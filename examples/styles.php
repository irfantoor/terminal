<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use IrfanTOOR\Terminal;
use IrfanTOOR\HtmlClient;

$t = new Terminal();

$c1 = 20;
$c2 = 50;

# a separator line
$line = str_repeat('-', $c1+$c2+6);

# title
$t->writeln("Foreground Styles", 'bold');
$t->writeln($line);

foreach ($t->getStyles() as $k => $v) {
    if (strpos($k, 'bg_') !== false)
            continue;

    $txt = "Its written with style -- $k";
    $l  = strlen(" $k ");
    $l2 = strlen($txt);

    $t->write("| $k " . str_repeat(' ', $c1 - $l) . '| ');
    $t->write("$txt " . str_repeat(' ', $c2 - $l2), $k);
    $t->writeln(" |");
}

$t->writeln($line);

$t->writeln("Background Styles", 'bold');
$t->writeln($line);

foreach ($t->getStyles() as $k => $v) {
    if (strpos($k, 'bg_') === false)
            continue;

    $txt = "Its written with style -- $k";
    $l  = strlen(" $k ");
    $l2 = strlen($txt);

    $t->write("| $k " . str_repeat(' ', $c1 - $l) . '| ');
    $t->write("$txt " . str_repeat(' ', $c2 - $l2), $k);
    $t->writeln(" |");
}

$t->writeln($line);
$t->writeln("Theme Styles", 'bold');
$t->writeln($line);

foreach ($t->getTheme() as $k => $v) {
    if (strpos($k, 'bg_') !== false)
            continue;

    $txt = "Its written with style -- $k";
    $l  = strlen(" $k ");
    $l2 = strlen($txt);

    $t->write("| $k " . str_repeat(' ', $c1 - $l) . '| ');
    $t->write("$txt " . str_repeat(' ', $c2 - $l2), $k);
    $t->writeln(" |");
}

$t->writeln($line);
