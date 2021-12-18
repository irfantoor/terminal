<?php

use IrfanTOOR\Terminal;
use IrfanTOOR\Terminal\{
    AbstractClient,
    CliClient,
    HtmlClient,
};

use IrfanTOOR\Test;

class TerminalTest extends Test
{
    function test_instance()
    {
        $t = new Terminal();
        $this->assertInstanceOf(Terminal::class, $t);
    }

    function test_getStyles()
    {
        $t = new Terminal();
        $styles = $t->getStyles();
        $this->assertArray($styles);

        foreach ($styles as $name => $def) {
            $this->assertString($name);
            if ($name === 'none') {
                $this->assertNull($def);
            } else {
                $this->assertString($def);
            }

            $this->assertEquals((int) $def, $def);
        }
    }

    function test_getTheme()
    {
        $t = new Terminal();
        $theme = $t->getTheme();
        $this->assertArray($theme);

        foreach ($theme as $style => $def) {
            $this->assertString($def);
        }

        $this->assertTrue(isset($theme['info']));
        $this->assertTrue(isset($theme['error']));
        $this->assertTrue(isset($theme['warning']));
        $this->assertTrue(isset($theme['success']));
    }

    function test_setOutput()
    {
        $t = new Terminal();

        // a filename
        $filename = "tmp_file.txt";

        $t->setOutput($filename);
        $this->assertFile($filename);
        $this->assertEquals('', file_get_contents($filename));

        $random_txt = "";
        for ($i = 100; $i>0; $i--)
            $random_txt .= rand(32, 127);

        $t->write($random_txt);

        $read = file_get_contents($filename);
        $this->assertEquals($random_txt, $read);
        unlink($filename);

        // a resource
        $handle = tmpfile();

        $t->setOutput($handle);
        $this->assertResource($handle);
        fseek($handle, 0);
        $this->assertEquals('', fread($handle, 1));

        $random_txt = "";
        for ($i = 100; $i>0; $i--)
            $random_txt .= rand(32, 65);

        $t->write($random_txt);

        $len = strlen($random_txt);
        fseek($handle, 0);
        $read = fread($handle, $len);
        $this->assertEquals($random_txt, $read);
        fclose($handle);
    }

    function test_ob_start()
    {
        $t = new Terminal();

        # no $t->ob_start() ... output is visible
        ob_start();
        $t->write("Hello");
        $output = ob_get_clean();
        $this->assertEquals("Hello", $output);

        # no $t->ob_start() ... output is not visible, it is buffered
        $t->ob_start();

        ob_start();
        $t->write("Hello");
        $output = ob_get_clean();
        $this->assertEquals("", $output);
        $output = $t->ob_get_clean();
        $this->assertEquals("Hello", $output);

        # $t->ob_start() ... every starts increases the buffer level
        $t->ob_start();
        $t->write("Hello");
        $t->ob_start();
        $t->write("World");
        $output = $t->ob_get_clean();
        $this->assertEquals("World", $output);
        $output = $t->ob_get_clean();
        $this->assertEquals("Hello", $output);
    }

    function test_ob_get_level()
    {
        $t = new Terminal();

        $this->assertEquals(0, $t->ob_get_level());

        # ob_get_contents
        $t->ob_start();
            $t->write("Hello");
            $this->assertEquals(1, $t->ob_get_level());
            $t->ob_start();
                $t->write("World");
                $this->assertEquals(2, $t->ob_get_level());
            $t->ob_get_contents();
            $this->assertEquals(2, $t->ob_get_level());
        $t->ob_get_contents();
        $this->assertEquals(2, $t->ob_get_level());
    }

    # ob_end_clean
    function test_ob_end_clean()
    {
        $t = new Terminal();

        # ob_get_contents
        $t->ob_start();
            $t->write("Hello");
            $t->ob_start();
                $t->write("World");

        $t->ob_end_clean();
        $this->assertEquals(0, $t->ob_get_level());
    }


    function test_ob_clean()
    {
        $t = new Terminal();

        # ob_clean
        $t->ob_start();
            $t->write("Hello");
            $this->assertEquals(1, $t->ob_get_level());
            $t->ob_start();
                $t->write("World");
                $this->assertEquals(2, $t->ob_get_level());
            $t->ob_clean();
            $this->assertEquals(1, $t->ob_get_level());
        $t->ob_clean();
        $this->assertEquals(0, $t->ob_get_level());
    }

    function test_ob_get_clean()
    {
        $t = new Terminal();

        # ob_get_clean
        $t->ob_start();
            $t->write("Hello");
            $this->assertEquals(1, $t->ob_get_level());
            $t->ob_start();
                $t->write("World");
                $this->assertEquals(2, $t->ob_get_level());
            $t->ob_get_clean();
            $this->assertEquals(1, $t->ob_get_level());
        $t->ob_get_clean();
        $this->assertEquals(0, $t->ob_get_level());
    }

    function test_ob_flush()
    {
        $t = new Terminal();

        # ob_flush
        $t->ob_start();
            $t->write("Hello");
            $this->assertEquals(1, $t->ob_get_level());
            $t->ob_start();
                $t->write("World");
                $this->assertEquals(2, $t->ob_get_level());
            ob_start();
            $output_returned = $t->ob_flush();
            $output_dumped = ob_get_clean();
            $this->assertEquals("", $output_returned);
            $this->assertEquals("World", $output_dumped);
            $this->assertEquals(1, $t->ob_get_level());
        ob_start();
        $t->ob_flush();
        $output = ob_get_clean();
        $this->assertEquals("Hello", $output);
        $this->assertEquals(0, $t->ob_get_level());
    }

    function test_ob_get_flush()
    {
        $t = new Terminal();

        # ob_get_flush
        $t->ob_start();
            $t->write("Hello");
            $this->assertEquals(1, $t->ob_get_level());
            $t->ob_start();
                $t->write("World");
                $this->assertEquals(2, $t->ob_get_level());
            ob_start();
            $output_returned = $t->ob_get_flush();
            $output_dumped = ob_get_clean();
            $this->assertEquals("World", $output_returned);
            $this->assertEquals("World", $output_dumped);
            $this->assertEquals(1, $t->ob_get_level());
        ob_start();
        $output_returned = $t->ob_get_flush();
        $output_dumped = ob_get_clean();
        $this->assertEquals("Hello", $output_returned);
        $this->assertEquals("Hello", $output_dumped);
        $this->assertEquals(0, $t->ob_get_level());
    }

    function test_ob_end_flush()
    {
        $t = new Terminal();

        # ob_get_flush
        $t->ob_start();
            $t->write("Hello");
            $t->ob_start();
                $t->write("World");

        ob_start();
        $output_returned = $t->ob_end_flush();
        $output_dumped = ob_get_clean();

        $this->assertEquals("", $output_returned);
        $this->assertEquals("WorldHello", $output_dumped);
    }

    function test_ob_get_length()
    {
        $t = new Terminal();

        $this->assertEquals(0, $t->ob_get_length());

        # ob_get_flush
        $t->ob_start();
            $t->write("Hello");
            $this->assertEquals(5, $t->ob_get_length());

            $t->ob_start();
                $t->write("World!");
                $this->assertEquals(6, $t->ob_get_length());

        $t->ob_end_clean();
    }
}
