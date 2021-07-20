<?php

namespace Aloha4\Processor;

class InputParser
{
    public static function parse($input)
    {
        $parts = explode(' ', $input);
        return new ParsedInput(array_shift($parts), $parts);
    }
}