<?php

namespace App\EdiTransfer\Infrastructure\Reader;

abstract class Reader
{
    abstract public function connect(string $server, int $port, string $user, string $password): void;
    abstract public function read(string $fileName): string;
    abstract public function getList(string $directory): array;
    abstract public function disconnect(): void;
}