# Irfan's Terminal

A terminal for your cli or html clients.

## Usage

See different examples in the examples folder.

```php
<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use IrfanTOOR\Terminal;

$t = new Terminal;

# printing with style
$t->write("Hello ", "green");
$t->writeln("World ", "red");

# use writeMultiple to write multiple lines.
$t->writeMultiple(
    [
        'These are multiple lines',
        'with a common style applied',
        'to all lines'
    ], 
    'bg_blue, white'
);

# reading from console
$response = $t->read("Are you ok? [Y/N] ", "info");

$t->write("you responded with: ");
$t->writeln($response, "info, reverse");
```

## Styles

### Foreground Styles
 - none
 - bold
 - dark
 - italic
 - underline
 - blink
 - reverse
 - concealed
 - default
 - black
 - red
 - green
 - yellow
 - blue
 - magenta
 - cyan
 - light_gray
 - dark_gray
 - light_red
 - light_green
 - light_yellow
 - light_blue
 - light_magenta
 - light_cyan
 - white

### Background Styles
 - bg_default
 - bg_black
 - bg_red
 - bg_green
 - bg_yellow
 - bg_blue
 - bg_magenta
 - bg_cyan
 - bg_light_gray
 - bg_dark_gray
 - bg_light_red
 - bg_light_green
 - bg_light_yellow
 - bg_light_blue
 - bg_light_magenta
 - bg_light_cyan
 - bg_white

### Theme Styles
 - info
 - error
 - warning
 - success
 - note
 - footnote
 - url

Note: All the theme styles can be modiied by providing the definition while creating the console.
or by using the function setTheme.

```php
<?php
 
require dirname(__DIR__) . "/vendor/autoload.php";

$t = new IrfanTOOR\Terminal();
$t->setTheme(
    [
        'info' => 'bg_black, yellow',
        'url'  => 'red, underline',
    ]
);

# Theme
$t->writeln("Modified theme >> info", "info");
$t->writeln("https://github.com/irfantoor/console", "url");

$t->setTheme(
    [
        'url' => 'red, bg_light_yellow, underline'
    ]
);

$t->writeln("https://github.com/irfantoor/console", "url");
```
