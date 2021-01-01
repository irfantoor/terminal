<?php

use IrfanTOOR\Terminal;
use IrfanTOOR\Terminal\HtmlClient;
use IrfanTOOR\Test;

class HtmlClientTest extends Test
{
    function testHtmlClientInstance()
    {
        $t = new Terminal("html");
        $this->assertInstanceOf(Terminal::class, $t);
        $this->assertInstanceOf(HtmlClient::class, $t->getClient());
    }

    function testHtmlClientCanRead()
    {
        $t = new terminal("html");

        $this->assertMethod($t, 'read');

        $this->assertMethod($t, 'read');
        $t->ob_start();
        $input = $t->read("say something");
        $output = $t->ob_get_clean();

        $expected = '<form method="post" style="font-family:monospace"><pre style="padding:0; margin:0">say something<input style="font-family:monospace; font-size:100%;" id="__ti1" name="__t[i1]" type="text"></form><script>this.document.getElementById("__ti1").focus();</script>';
        $this->assertEquals($expected, $output);

        # todo -- test html reading ...
        // fclose($handle);
    }

    function testHtmlClientCanWrite()
    {
        $t = new HtmlClient();

        $t->ob_start();
        $t->write('Hello World!');
        $t->write('1');
        $t->write('2');
        $t->writeln();
        $output = $t->ob_get_clean();

        $this->assertEquals('<pre style="padding:0; margin:0">Hello World!12</pre>', $output);

        $t->ob_start();
        $t->writeln();
        $output = $t->ob_get_clean();
        $this->assertEquals('<br>', $output);

        $t->ob_start();
        $t->write(" ");
        $t->writeln();
        $output = $t->ob_get_clean();
        $this->assertEquals('<pre style="padding:0; margin:0"> </pre>', $output);

        $t->ob_start();
        $t->writeln(" ");
        $output = $t->ob_get_clean();
        $this->assertEquals('<pre style="padding:0; margin:0"> </pre>', $output);
    }

    function testHtmlClientCanWriteWithStyle()
    {
        $t = new HtmlClient();

        foreach ($t->getStyles() as $k => $v) {
            $txt = 'Hello World!';
            if ($v) {
                $expected = '<pre style="padding:0; margin:0"><code style="' . $v . '">' . $txt . '</code></pre>';
            } else {
                $expected = '<pre style="padding:0; margin:0">' . $txt . '</pre>';
            }

            $t->ob_start();
                $t->writeln($txt, $k);
            $output = $t->ob_get_clean();

            $this->assertEquals($expected, $output);
        }
    }
    
    function testHtmlClientCanWriteMultipleLines()
    {
        $t = new Terminal("html");

        $t->ob_start();
        $t->writeMultiple(["Hello World!", "its a test."]);
        $output = $t->ob_get_clean();

        $t->ob_start();
        $t->writeln("Hello World!");
        $t->writeln("its a test.");

        $this->assertEquals($output, $t->ob_get_clean());

        $t->ob_start();
        $t->writeMultiple(["Hello World!", "its a test."], "red");
        $output = $t->ob_get_clean();

        $t->ob_start();
        $t->writeln("Hello World!", "red");
        $t->writeln("its a test.", "red");

        $this->assertEquals($output, $t->ob_get_clean());
    } 
}
