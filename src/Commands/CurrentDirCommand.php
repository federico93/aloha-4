<?php

namespace Aloha4\Commands;

class CurrentDirCommand extends AbstractCommand
{    
    public function run()
    {
        return $this->getFilesystem()->getCurrentDir()->getName();
    }
}