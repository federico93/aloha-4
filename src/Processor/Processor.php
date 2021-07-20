<?php

namespace Aloha4\Processor;

use Aloha4\Filesystem\Filesystem;

use Aloha4\Commands\QuitCommand;
use Aloha4\Commands\CurrentDirCommand;
use Aloha4\Commands\ListContentsCommand;
use Aloha4\Commands\MakeDirCommand;
use Aloha4\Commands\ChangeDirectoryCommand;
use Aloha4\Commands\CreateFileCommand;

use Aloha4\Exceptions\UnrecognizedCommandException;
use Aloha4\Exceptions\DirectoryAlreadyExistsException;
use Aloha4\Exceptions\DirectoryNotFoundException;
use Aloha4\Exceptions\QuitException;
use Aloha4\Exceptions\FileAlreadyExistsException;

class Processor
{
    const QUIT_COMMAND = 'quit';
    const CURRENT_DIR_COMMAND = 'pwd';
    const LIST_CONTENTS_COMMAND = 'ls';
    const MAKE_DIR_COMMAND = 'mkdir';
    const CHANGE_DIR_COMMAND = 'cd';
    const CREATE_FILE_COMMAND = 'touch';

    protected $filesystem;
    protected $console;

    public function __construct(Filesystem $filesystem, ConsoleInterface $console)
    {
        $this->filesystem = $filesystem;
        $this->console = $console;
    }

    public function run()
    {
        $quit = false;
        while (!$quit) {
            try {
                $parsedInput = $this->parseInput();
                $command = $this->buildCommand($parsedInput);
                $this->print($command->run());
            } catch (QuitException $e) {
                $quit = true;
            } catch (UnrecognizedCommandException $e) {
                $this->print('Unrecognized command');
            } catch (DirectoryAlreadyExistsException $e) {
                $this->print('Directory already exists');
            } catch (DirectoryNotFoundException $e) {
                $this->print('Directory not found');
            } catch (FileAlreadyExistsException $e) {
                $this->print('File already exists');
            }
        }
    }
    
    protected function parseInput()
    {
        return InputParser::parse($this->console->readline('Insert a command...'));
    }
    
    protected function buildCommand($parsedInput)
    {
        switch ($parsedInput->getCommandName()) {
            case self::QUIT_COMMAND:
                return new QuitCommand();
            case self::CURRENT_DIR_COMMAND:
                return new CurrentDirCommand($this->filesystem);
            case self::LIST_CONTENTS_COMMAND:
                return new ListContentsCommand($this->filesystem, $parsedInput->getOptions());
            case self::MAKE_DIR_COMMAND:
                return new MakeDirCommand($this->filesystem, $parsedInput->getOptions());
            case self::CHANGE_DIR_COMMAND:
                return new ChangeDirectoryCommand($this->filesystem, $parsedInput->getOptions());
            case self::CREATE_FILE_COMMAND:
                return new CreateFileCommand($this->filesystem, $parsedInput->getOptions());
            default:
                throw new UnrecognizedCommandException();
        }
    }
    
    protected function print($message)
    {
        return $this->console->printline($message);
    }
}