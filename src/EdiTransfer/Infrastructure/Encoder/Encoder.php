<?php

namespace App\EdiTransfer\Infrastructure\Encoder;

abstract class Encoder
{
    abstract public function encode(array $data): string;
}