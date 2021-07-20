<?php

namespace Aloha4\Commands;

interface CommandInterface
{
    public function __construct($filesystem);
    public function run();
}