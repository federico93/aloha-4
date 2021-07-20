<?php

namespace Aloha4\Filesystem;

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