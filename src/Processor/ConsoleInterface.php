<?php

namespace Aloha4\Processor;

interface ConsoleInterface
{
    public function readline($message = null);
    public function printline($message);
}