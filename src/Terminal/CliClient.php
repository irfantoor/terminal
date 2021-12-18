<?php

/**
 * IrfanTOOR\Terminal\CliClient
 * php version 7.3
 *
 * @author    Irfan TOOR <email@irfantoor.com>
 * @copyright 2021 Irfan TOOR
 */

namespace IrfanTOOR\Terminal;

use IrfanTOOR\Terminal\AbstractClient;

/**
 * CliClient to read or write to a Cli console
 */
class CliClient extends AbstractClient
{
    public function __construct()
    {
        parent::__construct();

        # terminal styles
        $this->styles = [
            'bold'             => '1',
            'dark'             => '2',
            'italic'           => '3',
            'underline'        => '4',
            'blink'            => '5',
            'reverse'          => '7',
            'concealed'        => '8',

            'default'          => '39',
            'black'            => '30',
            'red'              => '31',
            'green'            => '32',
            'yellow'           => '33',
            'blue'             => '34',
            'magenta'          => '35',
            'cyan'             => '36',
            'light_gray'       => '37',

            'dark_gray'        => '90',
            'light_red'        => '91',
            'light_green'      => '92',
            'light_yellow'     => '93',
            'light_blue'       => '94',
            'light_magenta'    => '95',
            'light_cyan'       => '96',
            'white'            => '97',

            'bg_default'       => '49',
            'bg_black'         => '40',
            'bg_red'           => '41',
            'bg_green'         => '42',
            'bg_yellow'        => '43',
            'bg_blue'          => '44',
            'bg_magenta'       => '45',
            'bg_cyan'          => '46',
            'bg_light_gray'    => '47',

            'bg_dark_gray'     => '100',
            'bg_light_red'     => '101',
            'bg_light_green'   => '102',
            'bg_light_yellow'  => '103',
            'bg_light_blue'    => '104',
            'bg_light_magenta' => '105',
            'bg_light_cyan'    => '106',
            'bg_white'         => '107',
        ];
    }

    /**
     * @see IrfanTOOR\Terminal::read
     */
    public function read($text = "", ?string $styles = null): string
    {
        if ($text) {
            $this->write($text, $styles);
        }

        $str = fgets($this->input);
        return preg_replace('{\r?\n$}D', '', $str);
    }

    /**
     * @see IrfanTOOR\Terminal::write
     */
    public function write(string $text, ?string $styles = null)
    {
        if (!$this->ansi)
        {
            if ($this->ob_level) {
                $this->ob[$this->ob_level] .= $text;
                return;
            }

            if ($this->output) {
                fwrite($this->output, $text);
            } else {
                echo $text;
            }

            return;
        }

        # use the theme styles in case of 'error', 'info' etc.
        $styles = $this->theme[$styles] ?? $styles;

        # process all of the styles separated by comma in the styles
        $styles = $styles ? explode(',', $styles) : [];
        $output = "";

        foreach ($styles as $style) {
            $style = trim($style);
            $s = $this->styles[$style] ?? null;

            if ($s) {
                $output .= "\033[{$s}m";
            }
        }


        # if there is style, echo it then echo text and normalize at the end
        # if the output stream is defined, then write to the stream rather
        if ($output !== "") {
            $text = $output . $text . "\033[0m";
        }

        # output to ob if ob_level
        if ($this->ob_level) {
            $this->ob[$this->ob_level] .= $text;
        } else {
            if ($this->output) {
                fwrite($this->output, $text);
            } else {
                echo $text;
            }
        }
    }

    /**
     * @see IrfanTOOR\Terminal::writeln
     */
    public function writeln($text = "", ?string $styles = null)
    {
        if ($text !== "") {
            $this->write($text, $styles);
        }

        $this->write(PHP_EOL);
    }
}
