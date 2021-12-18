<?php

# execute this on terminal php shell.php
# --OR--
# serve this file on a server e.g. php -S localhost:8000 shell.php

require dirname(__DIR__) . "/vendor/autoload.php";

use IrfanTOOR\Terminal;

$t = new Terminal();
$t->writeln("Irfan's Shell", "info");

for (;;) {
    # discard all reads but the last
    $t->discardReads(1);
    
    # prompt
    $cmd = $t->read("$ ", "red");

    # helps reading in the browser
    if ($t->isReading())
        break;    

    # execute the typed command
    if ($cmd) {
        ob_start();
        system($cmd);
    }

    # writes the result on terminal in dark color
    $t->writeln($cmd ? ob_get_clean() : "", "dark");
}
