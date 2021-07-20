<?php

namespace Aloha4\Commands;

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