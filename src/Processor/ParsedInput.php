<?php

namespace Aloha4\Processor;

class ParsedInput
{
    protected $commandName;
    protected $options;

    public function __construct($commandName, $options)
    {
        $this->commandName = $commandName;
        $this->options = $options;
    }

    public function getCommandName()
    {
        return $this->commandName;
    }

    public function getOptions()
    {
        return $this->options;
    }
}