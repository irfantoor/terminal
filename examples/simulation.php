<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use IrfanTOOR\Terminal;

$t = new Terminal();
$t->writeln("Simulation Example", "info");

for (;;) {
    # discard all reads but the last 3
    $t->discardReads(3);
    
    # read the variables from console/browser
    $age      = $t->read("What is your age? (years only) ", "red");
    $upfront  = $t->read("How much you can pay up front? (in euro) ", "red"); 
    $permonth = $t->read("How much you can pay per month? (in euro) ", "red");
    
    # helps reading in the browser
    if ($t->isReading())
        break;

    # calculate the result
    $value = $upfront + (70 - $age) * $permonth * 12;

    # print the result
    $t->writeln("you can buy a house of: " . $value . " Euro", "dark");
    
    # press enter to continue
    $t->discardReads(); 
    $t->read("enter to continue ", "light");
}
