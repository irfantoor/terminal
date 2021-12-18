<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use IrfanTOOR\Terminal;

$t = new Terminal();

# to write multiple lines use writeMultiple and pass multiple lines as array
$t->writeMultiple(
    [
        "All of these lines",
        "are printed with white on red background ...",
        " ;-) "
    ], "bg_red, white"
);

