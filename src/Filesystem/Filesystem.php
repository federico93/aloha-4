<?php

namespace Aloha4\Filesystem;

use Aloha4\Exceptions\DirectoryNotFoundException;

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

    public function setCurrentDir(Dir $currentDir)
    {
        $this->currentDir = $currentDir;
    }

    public function moveUp()
    {
        if (!$this->currentDir->getParent()) {
            throw new DirectoryNotFoundException();
        }

        $this->currentDir = $this->currentDir->getParent();
    }
}