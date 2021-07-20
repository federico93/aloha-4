<?php

namespace Aloha4;

require __DIR__ . "/../vendor/autoload.php";

use Aloha4\Processor\Processor;
use Aloha4\Processor\Console;
use Aloha4\Filesystem\Filesystem;
use Aloha4\Filesystem\Dir;

$rootDir = new Dir(Filesystem::ROOT_DIR);
$processor = new Processor(new Filesystem($rootDir), new Console());

$processor->run();