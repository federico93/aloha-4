<?php

namespace Aloha4\Processor;

class Console implements ConsoleInterface
{
    public function readline($message = null)
    {
        return readline($message);
    }

    public function printline($message)
    {
        echo $message . "\n";
    }
}