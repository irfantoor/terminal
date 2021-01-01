<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use IrfanTOOR\Terminal;

$t = new Terminal();

$t->writeln("Hello World! - info", "info");
$t->writeln("Hello World! - success", "success");
$t->writeln("Hello World! - warning", "warning");
$t->writeln("Hello World! - error", "error");
$t->writeln("Hello World! - note", "note");
$t->writeln("Hello World! - footnote", "footnote");
$t->writeln("Hello World! - url", "url");

# silence is golden
// $t->setOutput("/dev/null");
$t->writeln("The values are entered without any javascript ...", "warning");

# but I speak ...
// $t->setOutput("/dev/stdout");

$w = (float) $t->read("width: ");
$l = (float) $t->read("length: ");
$h = (float) $t->read("hight: ");

$t->writeln();
$t->writeln("Volume = " . ($w * $l * $h), "white, bg_blue");
