<?php

namespace App\EdiTransfer\Infrastructure\Writer;

use App\EdiTransfer\Infrastructure\Writer\Writer;

class StdoutWriter extends Writer
{
    public function connect(string $server, int $port, string $user, string $password): void
    {
    }

    public function disconnect(): void
    {
    }

    public function write(string $fileName, string $string): void
    {
        $this->getIo()->writeLn($string);
    }

    public function writeDelay(string $fileName, string $string, int $retriesCount): void
    {
        $this->write($fileName, $string);
    }
}