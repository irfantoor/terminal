<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use IrfanTOOR\Terminal;

$t = new Terminal();

$txt = $t->read("Hello: ", "red");

# Banner
# when the text to write is passed as an array of lines, it is displayed as banner
$t->writeMultiple(
    [
        "you entered: " . $txt,
        "Its a white on red banner",

    ], "bg_blue, white"
);

$t->writeMultiple(
    [
        "Its a banner when the text to write is passed as an array of lines, it is displayed as banner",
        "styles: ['bg_light_yellow', 'black']",
        "With multiple lines",
        "    and smiles! :-)"
    ],
    "bg_light_yellow, red"
);
$t->writeMultiple(["White on Red Banner: Hello World!"], "bg_red, white");
