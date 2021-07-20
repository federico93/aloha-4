<?php

namespace Aloha4\Filesystem;

class Dir extends File
{
    protected $contents;
    protected $parent;
    
    public function __construct($name, $parent = null, $contents = [])
    {
        $this->contents = $contents;
        $this->parent   = $parent;

        parent::__construct($name);
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function getParent()
    {
        return $this->parent;
    }
    
    public function addFile(File $file)
    {
        $this->contents[] = $file;
    }
    
    public function hasDir($dirName)
    {
        foreach ($this->contents as $content) {
            if (is_a($content, get_class($this)) && $content->getName() === $dirName) {
                return true;
            }
        }
        
        return false;
    }

    public function hasFile($filename)
    {
        foreach ($this->contents as $content) {
            if ($content->getName() === $filename) {
                return true;
            }
        }
        
        return false;
    }
}