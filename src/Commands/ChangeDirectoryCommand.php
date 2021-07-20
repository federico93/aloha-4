<?php

namespace Aloha4\Commands;

use Aloha4\Exceptions\DirectoryNotFoundException;

class ChangeDirectoryCommand extends AbstractCommand
{
    public function run()
    {
        $path = $this->popOption();

        $parts = explode('/', $path);

        foreach ($parts as $part) {
            if ($part == '..') {
                $this->getFilesystem()->moveUp();
            } else {
                $this->openDir($part);
            }
        }
    }

    protected function openDir($dirName)
    {
        $contents = $this->getFilesystem()->getCurrentDir()->getContents();
        foreach ($contents as $content) {
            if (is_a($content, 'Aloha4\Filesystem\Dir') && $content->getName() === $dirName) {
                $this->getFilesystem()->setCurrentDir($content);
                return;
            }
        }

        throw new DirectoryNotFoundException();
    }
}