<?php

namespace Aloha4\Commands;

use Aloha4\Exceptions\QuitException;

class QuitCommand extends AbstractCommand
{
    public function run()
    {
        throw new QuitException();
    }
}