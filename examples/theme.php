<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use IrfanTOOR\Terminal;

$t = new Terminal();

# Theme
$t->writeln("theme -> info: This is written with style 'info'", "info");
$t->writeln("theme -> url: https://github.com/irfantoor/console", "url");

$t->writeln();
$t->writeln("Modifying theme ...");

$t->setTheme(
    [
        'info' => 'bg_black, yellow',
        'url'  => 'red, underline',
    ]
);

$t->writeln("theme -> info: This is written with style 'info'", "info");
$t->writeln("theme -> url: https://github.com/irfantoor/console", "url");

$t->writeln();
$t->writeln("Modifying again ...");

$t->setTheme(
    [
        'url' => 'bg_blue, white'
    ]
);
$t->writeln("theme -> url: https://github.com/irfantoor/terminal", "url");
