<?php

/**
 * IrfanTOOR\Terminal
 * php version 7.3
 *
 * @author    Irfan TOOR <email@irfantoor.com>
 * @copyright 2021 Irfan TOOR
 */

namespace IrfanTOOR;

use IrfanTOOR\Terminal\{
    AbstractClient,
    CliClient,
    HtmlClient
};

/**
 * Terminal which function as a CliClient or HtmlCLient according to the 
 * Environment i.e. Output colors etc. are normalized according to medium
 */
class Terminal
{
    const NAME        = "Terminal";
    const DESCRIPTION = "Terminal for your cli or html clients";
    const VERSION     = "0.1.3";

    /** @var string -- "cli" or "html" */
    protected $client_type;

    /** @var CliClient|HtmlClient */
    protected $client;

    /**
     * Terminal constructor
     */
    # @param client_type null|"cli"|"html", if null the the client is selected
    # according to the environment
    public function __construct(?string $client_type = null)
    {
        $this->client_type = $client_type ?? PHP_SAPI;
        $this->client =
            ($this->client_type === "cli")
            ? new CliClient()
            : new HtmlClient()
        ;
    }

    /**
     * Passes all of the calls to the client
     *
     * @param string $method
     * @param array  $args
     */
    public function __call(string $method, array $args)
    {
        return call_user_func_array([$this->client, $method], $args);
    }

    /**
     * Returns client type
     *
     * @return string Returns , "cli" or "html"
     */
    public function getClientType(): string
    {
        return $this->client_type;
    }

    /**
     * Returns the client
     *
     * @return CliClient|HtmlClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Reads a value read from terminal
     *
     * @param string      $text   Optional text to be written before input prompt
     * @param null|string $styles Comma separated styles for text to be written in
     * @return string Return the text entered
     */
    public function read(string $text = "", ?string $styles = null): string
    {
        return $this->client->read($text, $styles);
    }

    /**
     * Write text to terminal with style
     *
     * @param string      $text
     * @param null|string $style
     * @param bool        $foce_ansi forces the ansi color output
     */
    public function write(
        string $text,
        ?string $styles = null,
        bool $force_ansi = false
    )
    {
        $this->client->write($text, $styles, $force_ansi);
    }

    /**
     * Write text to terminal adding new line at the end
     *
     * @param string      $text
     * @param null|string $style
     * @param bool        $foce_ansi forces the ansi color output
     */
    public function writeln(
        string $text = "",
        ?string $styles = null,
        bool $force_ansi = false
    )
    {
        $this->client->writeln($text, $styles, $force_ansi);
    }

    /**
     * Writes multiple lines of text to terminal
     *
     * @param array       $text      Array of text lines to be written
     * @param null|string $style     Style of 
     * @param bool        $foce_ansi Forces the ansi color output
     */
    public function writeMultiple(
        array $text,
        ?string $style = null,
        $force_ansi = false
    )
    {
        foreach ($text as $txt) {
            $this->writeln($txt, $style, $force_ansi);
        }
    }
}
