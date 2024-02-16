<?php

namespace App\EdiTransfer\Infrastructure\Writer;

use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Writer
{
    private SymfonyStyle $io;

    abstract public function connect(string $server, int $port, string $user, string $password): void;
    abstract public function disconnect(): void;
    abstract public function write(string $fileName, string $string): void;
    abstract public function writeDelay(string $fileName, string $string, int $retriesCount): void;

    public function setIo(SymfonyStyle $io): void
    {
        $this->io = $io;
    }

    public function getIo(): SymfonyStyle
    {
        return $this->io;
    }
}