<?php

namespace Aloha4\Commands;

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
            if (is_a($file, 'Aloha4\Filesystem\Dir') && $this->hasOption('-r')) {
                $files = array_merge($files, $this->listDirContents($file, $recursive, $path));
            }
        }
        
        return $files;
    }
}