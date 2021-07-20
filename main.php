<?php
 /* Enter your code here. Read input from STDIN. Print output to STDOUT */
 
class AppException extends Exception
{
    
}
 
class QuitException extends AppException
{
    
}

class UnrecognizedCommandException extends AppException
{
    
}

class DirectoryAlreadyExistsException extends AppException
{
    
}

interface CommandInterface
{
    public function __construct($filesystem);
    public function run();
}

abstract class AbstractCommand implements CommandInterface
{
    protected $filesystem;
    protected $options;
    
    public function __construct($filesystem = null, $options = [])
    {
        $this->filesystem = $filesystem;
        $this->options = $options;
    }
    
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
    public function hasOption($option)
    {
        foreach ($this->options as $o) {
            if ($option === $o) {
                return true;
            }
        }
        
        return false;
    }
    
    public function popOption()
    {
        return array_shift($this->options);
    }
    
    abstract public function run();
}

class QuitCommand extends AbstractCommand
{
    public function run()
    {
        throw new QuitException();
    }
}

class CurrentDirCommand extends AbstractCommand
{    
    public function run()
    {
        return $this->getFilesystem()->getCurrentDir()->getName();
    }
}

class ListContentsCommand extends AbstractCommand
{
    public function run()
    {   
        $recursive = $this->hasOption('-r');
        $files = $this->listDirContents($this->getFilesystem()->getCurrentDir(), $recursive);

        return implode("\n", $files);
    }
    
    protected function listDirContents($dir, $recursive, $rootPath = null)
    {
        $files = [];
        $contents = $dir->getContents();
        if (!$recursive) {
            $path = '';
        } elseif ($rootPath) {
            $path = $rootPath . $dir->getName() . '/';
        } else {
            $path = $dir->getName() . '/';
        }
        
        foreach ($contents as $file) {
            $files[] = $path . $file->getName();
            if (is_a($file, 'Dir') && $this->hasOption('-r')) {
                $files = array_merge($files, $this->listDirContents($file, $recursive, $path));
            }
        }
        
        return $files;
    }
}

class MakeDirCommand extends AbstractCommand
{
    public function run()
    {
        $dirName = $this->popOption();
        
        if ($this->getFilesystem()->getCurrentDir()->hasDir($dirName)) {
            throw new DirectoryAlreadyExistsException();
        }
        
        $dir = new Dir($dirName);
        
        $this->getFilesystem()->getCurrentDir()->addFile($dir);
    }
}

class File
{
    protected $name;
    
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getPath()
    {
        return $this->path;
    }
}

class Dir extends File
{
    protected $contents;
    
    public function __construct($name, $contents = [])
    {
        $this->contents = $contents;
        parent::__construct($name);
    }
    
    public function getContents()
    {
        return $this->contents;
    }
    
    public function addFile(File $file)
    {
        $this->contents[] = $file;
    }
    
    public function hasDir($dirName)
    {
        foreach ($this->contents as $content) {
            if (is_a($content, 'Dir') && $content->getName() === $dirName) {
                return true;
            }
        }
        
        return false;
    }
}

class Filesystem
{
    const ROOT_DIR = '/root';

    protected $currentDir;
    protected $map;

    public function __construct(Dir $currentDir)
    {
        $this->currentDir = $currentDir;
        $this->map = [];
    }
    
    public function getCurrentDir()
    {
        return $this->currentDir;
    }
}

class Processor
{
    const QUIT_COMMAND = 'quit';
    const CURRENT_DIR_COMMAND = 'pwd';
    const LIST_CONTENTS_COMMAND = 'ls';
    const MAKE_DIR_COMMAND = 'mkdir';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function run()
    {
        $quit = false;
        while (!$quit) {
            try {
                $input = $this->readline();
                $command = $this->buildCommand(array_shift($input), $input);
                $this->print($command->run());
            } catch (QuitException $e) {
                $quit = true;
            } catch (UnrecognizedCommandException $e) {
                $this->print('Unrecognized command');
            } catch (DirectoryAlreadyExistsException $e) {
                $this->print('Directory already exists');
            }
        }
    }
    
    protected function readline()
    {
        $txt = readline('Insert a command...');
        
        return explode(' ', $txt);
    }
    
    protected function buildCommand($txt, $options)
    {
        switch ($txt) {
            case self::QUIT_COMMAND:
                return new QuitCommand();
            case self::CURRENT_DIR_COMMAND:
                return new CurrentDirCommand($this->filesystem);
            case self::LIST_CONTENTS_COMMAND:
                return new ListContentsCommand($this->filesystem, $options);
            case self::MAKE_DIR_COMMAND:
                return new MakeDirCommand($this->filesystem, $options);
            default:
                throw new UnrecognizedCommandException();
        }
    }
    
    protected function print($message)
    {
        echo $message . "\n";
    }
}

$rootDir = new Dir(Filesystem::ROOT_DIR);
$processor = new Processor(new Filesystem($rootDir));
$processor->run();
