<?php

namespace App\EdiTransfer\Infrastructure\Parser;

abstract class Parser
{
    abstract public function read(string $fileContent): array;

    abstract public function parse($parser): array;
}