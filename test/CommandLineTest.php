<?php

use PHPUnit\Framework\TestCase;

use Aloha4\Processor\Processor;
use Aloha4\Processor\Console;
use Aloha4\Filesystem\Filesystem;
use Aloha4\Filesystem\Dir;

class ConsoleMock extends Console
{
    protected $input;
    protected $output;

    public function __construct($input = [])
    {
        $this->input = $input;
        $this->output = [];
    }

    public function readline($message = null)
    {
        return array_shift($this->input);
    }

    public function printline($message)
    {
        if ($message) {
            $this->output[] = $message;
        }
    }

    public function getOutput()
    {
        return $this->output;
    }
}


class CommandLineTest extends TestCase
{
    public function testPwd(): void
    {
        $filesystem = $this->initFilesystem();

        $input = ['pwd', 'quit'];
        $console = $this->initConsoleMock($input);

        $processor = new Processor($filesystem, $console);

        $processor->run();

        $expectedOutput = ['/root'];

        foreach($console->getOutput() as $key => $value) {
            $this->assertEquals($expectedOutput[$key], $value);
        }
    }

    public function testOne(): void
    {
        /**
         * Created filesystem
         *
         * /root/
         *     -> a-dir/
         *         -> otherfile.txt
         *     -> file.txt
         */
        $input = [
            'pwd',
            'mkdir a-dir',
            'ls',
            'touch file.txt',
            'ls',
            'ls -r',
            'cd a-dir',
            'ls',
            'touch otherfile.txt',
            'ls',
            'ls -r',
            'cd ..',
            'ls',
            'quit'
        ];

        $expectedOutput = [
            '/root',
            'a-dir',
            "a-dir\nfile.txt",
            "/root/a-dir\n/root/file.txt",
            'otherfile.txt',
            'a-dir/otherfile.txt',
            "a-dir\nfile.txt",
        ];

        $this->testProcessor($input, $expectedOutput);
    }

    protected function testProcessor($input, $expectedOutput)
    {
        $filesystem = $this->initFilesystem();

        $console = $this->initConsoleMock($input);

        $processor = new Processor($filesystem, $console);

        $processor->run();

        foreach($console->getOutput() as $key => $value) {
            $this->assertEquals($expectedOutput[$key], $value);
        }
    }

    protected function initFilesystem()
    {
        $rootDir = new Dir(Filesystem::ROOT_DIR);

        return new Filesystem($rootDir);
    }

    protected function initConsoleMock($input)
    {
        return new ConsoleMock($input);
    }
}