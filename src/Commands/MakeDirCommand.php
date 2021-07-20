<?php

namespace Aloha4\Commands;

use Aloha4\Filesystem\Dir;
use Aloha4\Exceptions\DirectoryAlreadyExistsException;

class MakeDirCommand extends AbstractCommand
{
    public function run()
    {
        $dirName = $this->popOption();
        
        if ($this->getFilesystem()->getCurrentDir()->hasDir($dirName)) {
            throw new DirectoryAlreadyExistsException();
        }
        
        $dir = new Dir($dirName, $this->getFilesystem()->getCurrentDir());
        
        $this->getFilesystem()->getCurrentDir()->addFile($dir);
    }
}