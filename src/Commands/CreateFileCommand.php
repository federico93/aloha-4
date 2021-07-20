<?php

namespace Aloha4\Commands;

use Aloha4\Filesystem\File;
use Aloha4\Exceptions\FileAlreadyExistsException;

class CreateFileCommand extends AbstractCommand
{
    public function run()
    {
        $filename = $this->popOption();

        if ($this->getFilesystem()->getCurrentDir()->hasFile($filename)) {
            throw new FileAlreadyExistsException();
        }

        $file = new File($filename);

        $this->getFilesystem()->getCurrentDir()->addFile($file);
    }
}