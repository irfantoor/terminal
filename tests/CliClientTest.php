<?php

use IrfanTOOR\Terminal;
use IrfanTOOR\Terminal\CliClient;
use IrfanTOOR\Test;

class CliClientTest extends Test
{
    function testCliClientInstance()
    {
        $t = new Terminal();
        $this->assertInstanceOf(Terminal::class, $t);
        $this->assertInstanceOf(CliClient::class, $t->getClient());

        $t = new Terminal("cli");
        $this->assertInstanceOf(CliClient::class, $t->getClient());
    }

    function testCliClientCanRead()
    {
        $t = new Terminal();

        $this->assertMethod($t, 'read');
        $handle = tmpfile();
        fwrite($handle, "hello world ...\n");
        fseek($handle, 0);
        $t->setInput($handle);

        $t->ob_start();
        $input = $t->read("say something");
        $output = $t->ob_get_clean();

        $this->assertEquals("say something", $output);
        $this->assertEquals("hello world ...", $input);

        fclose($handle);
    }

    function testCliClientCanWrite()
    {
        $t = new Terminal();

        $t->ob_start();
        $t->write('Hello World!');
        $output = $t->ob_get_clean();

        $this->assertEquals('Hello World!', $output);

        $t->ob_start();
        $t->writeln('Hello World!');
        $output = $t->ob_get_clean();

        $this->assertEquals('Hello World!' . PHP_EOL, $output);
    }

    function testCliClientCanWriteWithStyle()
    {
        $t = new Terminal();
        $supported = stream_isatty(STDOUT);

        foreach ($t->getStyles() as $k => $v) {
            $txt = 'Hello World!';

            if ($v && $supported) {
                $expected = "\033[{$v}m" . $txt . "\033[0m";
            } else {
                $expected = $txt;
            }

            $t->ob_start();
                $t->write($txt, $k);
            $output = $t->ob_get_clean();

            $this->assertEquals($expected, $output);
        }
    }

    function testCliClientCanWriteMultipleLines()
    {
        $t = new Terminal();

        $t->ob_start();
        $t->writeMultiple(['Hello', 'World!']);
        $output = $t->ob_get_clean();

        $this->assertEquals("Hello\nWorld!\n", $output);

        $t->ob_start();
        $t->writeMultiple(["Hello World!", "its a test."], "red");
        $output = $t->ob_get_clean();

        $t->ob_start();
        $t->writeln("Hello World!", "red");
        $t->writeln("its a test.", "red");

        $this->assertEquals($output, $t->ob_get_clean());
    }
}
