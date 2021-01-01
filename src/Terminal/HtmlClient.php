<?php

/**
 * IrfanTOOR\Terminal\HtmlClient
 * php version 7.3
 *
 * @author    Irfan TOOR <email@irfantoor.com>
 * @copyright 2021 Irfan TOOR
 */

namespace IrfanTOOR\Terminal;

use IrfanTOOR\Terminal\AbstractClient;

# HtmlClient to read and write from and to an Html Navigator
class HtmlClient extends AbstractClient
{
    protected static $starting;
    protected static $reading;

    public function __construct()
    {
        parent::__construct();

        $this->setInput(null);
        $this->setOutput(null);

        $this->styles = [
            'bold'             => 'font-weight:bold',
            'dark'             => 'color: #333',
            'italic'           => 'font-style:italic',
            'underline'        => 'text-decoration:underline',
            'blink'            => 'text:blink',
            'reverse'          => 'color: #fff; background-color:#000',
            'concealed'        => 'color:transparent;',

            'default'          => '',
            'black'            => 'color:#000',
            'red'              => 'color:#900',
            'green'            => 'color:#090',
            'yellow'           => 'color:#990',
            'blue'             => 'color:#36c',
            'magenta'          => 'color:#939',
            'cyan'             => 'color:#66c',
            'light_gray'       => 'color:#999',

            'dark_gray'        => 'color:#333',
            'light_red'        => 'color:#d00',
            'light_green'      => 'color:#0d0',
            'light_yellow'     => 'color:#dd0',
            'light_blue'       => 'color:#00d',
            'light_magenta'    => 'color:#d0d',
            'light_cyan'       => 'color:#99d',
            'white'            => 'color:#fff',

            'bg_default'       => '',
            'bg_black'         => 'background-color:#000',
            'bg_red'           => 'background-color:#900',
            'bg_green'         => 'background-color:#090',
            'bg_yellow'        => 'background-color:#990',
            'bg_blue'          => 'background-color:#36c',
            'bg_magenta'       => 'background-color:#099',
            'bg_cyan'          => 'background-color:#339',
            'bg_light_gray'    => 'background-color:#999',

            'bg_dark_gray'     => 'background-color:#333',
            'bg_light_red'     => 'background-color:#f00',
            'bg_light_green'   => 'background-color:#0f0',
            'bg_light_yellow'  => 'background-color:#ff0',
            'bg_light_blue'    => 'background-color:#00f',
            'bg_light_magenta' => 'background-color:#0ff',
            'bg_light_cyan'    => 'background-color:#33f',
            'bg_white'         => 'background-color:#fff',
        ];

        self::$starting = true;
        self::$reading = false;
    }

    /**
     * Returns true if the terminal is in reading state
     */
    public function isReading(): bool
    {
        return self::$reading;
    }

    /**
     * Discard the previously read inputs
     * Note: by default it discards all
     *
     * @param int $preserve Number of reads to be preserved
     */
    public function discardReads(int $preserve = 0)
    {
        if (!isset($_POST['__t']))
            return;

        if ($preserve === 0) {
            unset($_POST['__t']);
        } elseif (count($_POST['__t']) <= $preserve) {
            return;
        } else {
            $i = count($_POST['__t']) - $preserve;
            $preserved = [];

            while ($i>0) {
                array_shift($_POST['__t']);
                $i--;
            }

            $preserved = [];
            $i = 1;

            foreach ($_POST['__t'] as $k => $v) {
                $preserved['i' . $i] = $v;
                $i++;
            }

            $_POST['__t'] = $preserved;
        }        
    }

    /**
     * @see IrfanTOOR\Terminal::read
     */
    public function read($text = "", ?string $styles = null): string
    {
        static $counter = 0;
        $counter++;

        if ($this->isReading())
            return "";

        $list = $_POST['__t'] ?? [];
        $name = 'i' . $counter;
        $result = $list[$name] ?? null;
        unset($list[$name]);

        if ($result !== null) {
            $this->write($text, $styles);
            $this->writeln($result);
            return $result;
        } else {
            $this->ob_start();
            $this->write($text, $styles);
            $text = $this->ob_get_clean();

            $text = '<form method="post" style="font-family:monospace">' . $text;

            foreach ($list as $k => $v) {
                $text .= '<input name="__t['.$k.']" type="hidden" value="'.$v.'">';
            }

            $text .= '<input style="font-family:monospace; font-size:100%;" id="__t'.$name.'" name="__t['.$name.']" type="text">';
            $text .= '</form>';
            $text .= '<script>this.document.getElementById("__t'.$name.'").focus();</script>';

            $this->write($text);

            self::$reading = true;
            return "";
        }
    }

    /**
     * @see IrfanTOOR\Terminal::write
     */
    public function write(
        string $text, 
        ?string $styles = null, 
        bool $force_ansi = false
    )
    {
        if ($this->isReading())
            return;

        if (self::$starting) {
            $start = "<pre style=\"padding:0; margin:0\">";
            self::$starting = false;
        } else {
            $start = "";
        }

        # use the theme styles in case of 'error', 'info' etc.
        $styles = $this->theme[$styles] ?? $styles;

        # process all of the styles separated by comma in the styles
        $styles = $styles ? explode(',', $styles) : [];
        $output = "";
        $sep = "";

        foreach ($styles as $style) {
            $style = trim($style);
            $style = $this->styles[$style] ?? null;

            if ($style) {
                if ($style[0] === '<') {
                    $text = str_replace($style, '{$text}', $text);
                } else {
                    $output .= $sep . $style;
                    $sep = "; ";
                }
            }
        }

        # if there is style, echo it then echo text and normalize at the end
        # if the output stream is defined, then write to the stream rather
        if ($output !== "") {
            $text = $start . '<code style="' . $output . '">' . $text . "</code>";
        } else {
            $text = $start . $text;
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
        if ($this->isReading())
            return;

        if ($text) {
            $this->write($text, $styles);
        }

        if (self::$starting) {
            $text = '<br>'; # empty writeln, just to append a new line
        } else {
            $text = '</pre>'; # close the <pre> started by write
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

        self::$starting = true;
    }
}
