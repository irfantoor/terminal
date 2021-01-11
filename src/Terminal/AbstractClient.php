<?php

/**
 * IrfanTOOR\Terminal\AbstractClient
 * php version 7.3
 *
 * @author    Irfan TOOR <email@irfantoor.com>
 * @copyright 2021 Irfan TOOR
 */

namespace IrfanTOOR\Terminal;

/**
 * AbstractClient is a base class to be used for CliClient or HtmlClient 
 */
abstract class AbstractClient
{
    /** @var Stream -- the input stream */
    protected $input    = null;

    /** @var Stream -- the output stream */
    protected $output   = null;

    /** @var int -- the buffering level */
    protected $ob_level = 0;

    /** @var array -- the output buffers */
    protected $ob       = [];

    /** @var array -- definition of styles */
    protected $styles = [];

    /** @var array -- definition of a theme */
    protected $theme;

    /** @var string -- root path - to be used when executing commands */
    protected $path;

    /**
     * Basic constructor, defines the base path and theme
     */
    public function __construct()
    {
        # open standard input and output
        $this->setInput('php://stdin');

        # basic theme
        $this->theme = [
            'info'     => 'blue',
            'error'    => 'bg_red',
            'warning'  => 'bg_light_yellow, red',
            'success'  => 'bg_green',

            'note'     => 'bg_light_yellow, black',
            'footnote' => 'dark',
            'url'      => 'blue, underline',
        ];
    }

    /**
     * Sets the input stream
     *
     * @param filepath|resource $stream
     */
    public function setInput($stream)
    {
        if (is_string($stream)) {
            $this->input = fopen($stream, 'r');
        } elseif (is_resource($stream)) {
            $this->input = $stream;
        }
    }

    /**
     * Sets the output stream
     *
     * @param filepath|resource $stream
     */
    public function setOutput($stream)
    {
        if (is_string($stream)) {
            $this->output = fopen($stream, 'w');
        } elseif (is_resource($stream)) {
            $this->output = $stream;
        }
    }

    /**
     * Starts the output buffer, if already started, it increases the output
     * buffer level, so that the printing goes to this next level
     */
    public function ob_start()
    {
        $this->ob_level++;
        $this->ob[$this->ob_level] = "";
    }

    /**
     * Returns the current buffer level
     *
     * @return int Buffering level, 0 means not buffering
     */
    public function ob_get_level()
    {
        return $this->ob_level;
    }

    /**
     * Retuns the contents of the buffer at current level
     * Note: it does not clears the buffer
     */
    public function ob_get_contents()
    {
        return $this->ob_level ? $this->ob[$this->ob_level] : "";
    }

    /**
     * Clear the current buffer and reducess the buffer level
     */
    public function ob_clean()
    {
        if ($this->ob_level) {
            unset($this->ob[$this->ob_level]);
            $this->ob_level--;
        }
    }

    /**
     * Returns the contents of the current buffer, clears the current buffer
     * and reduces buffer level
     *
     * @return string
     */
    public function ob_get_clean(): string
    {
        $contents = $this->ob_get_contents();
        $this->ob_clean();

        return $contents;
    }

    /**
     * Cleans all of the buffers at different levels and returns to no buffering
     *
     * @return string
     */
    public function ob_end_clean()
    {
        while ($this->ob_level) {
            $this->ob_clean();
        }
    }

    /**
     * Flushes the current buffer to output stream and reduces buffer level
     */
    public function ob_flush()
    {
        $this->ob_get_flush();
    }

    /**
     * Flushes the current buffer to output stream, reduce level and return the 
     * flushed contents as well
     *
     * @return string
     */
    public function ob_get_flush(): string
    {
        $contents = $this->ob_get_clean();

        if ($this->output) {
            fwrite($this->output, $contents);
        } else {
            echo $contents;
        }

        return $contents;
    }

    /**
     * Flushes contents of all the buffered contents at all levels,
     * in the reverse order of buffering 
     */
    public function ob_end_flush()
    {
        while ($this->ob_level) {
            $this->ob_flush();
        }
    }

    /**
     * Returns the length of the contents of current buffer
     *
     * @return int
     */
    public function ob_get_length()
    {
        return strlen($this->ob_get_contents());
    }

    /**
     * Returns the style definitions
     *
     * @return array The definition of styles color => definition
     */
    # 
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * Returns the current definition of theme
     *
     * @return array The definition of keyword => style definition
     */
    public function getTheme(): array
    {
        return $this->theme;
    }

    /**
     * Sets a new theme, merging with the basic
     *
     * @param array $theme Array of associative elements of keyword => styles
     */
    public function setTheme(array $theme)
    {
        $this->theme = array_merge(
            $this->theme,
            $theme
        );
    }

    /**
     * @see IrfanTOOR\Terminal\HtmlClient::isReading
     * Note: add for the uniformity/consistancy of code
     */
    public function isReading(): bool
    {
        return false;
    }

    /**
     * @see IrfanTOOR\Terminal\HtmlClient::discardReads
     * Note: add for the uniformity/consistancy of code
     */
    public function discardReads()
    {
    }

    /**
     * @see IrfanTOOR\Terminal::read
     */
    abstract public function read($text = "", ?string $style = null): string;

    /**
     * @see IrfanTOOR\Terminal::write
     */
    abstract public function write(
        string $text,
        ?string $styles = null,
        bool $force_ansi = false
    );

    /**
     * @see IrfanTOOR\Terminal::writeln
     */
    abstract public function writeln($text = "", ?string $styles = null);
}
