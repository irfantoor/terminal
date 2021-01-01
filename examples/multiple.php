<?php

require dirname(__DIR__) . "/vendor/autoload.php";

$t = new IrfanTOOR\Terminal;

# Banner
# when the text to write is passed as an array of lines, it is displayed as banner
$t->writeMultiple(["White on Red Banner: Hello World!"], "bg_blue, white");

$t->writeMultiple(
    [
        "Its a banner when the text to write is passed as an array of lines, it is displayed as banner",
        "styles: ['bg_light_yellow', 'black']",
        "With multiple lines",
        "    and smiles! :-)",
    ],
    "bg_light_yellow, red"
);

$t->writeMultiple(["White on Red Banner: Hello World!"], "bg_red, white");
