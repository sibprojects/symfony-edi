<?php

namespace App\EdiTransfer\Infrastructure\Writer;

use App\EdiTransfer\Infrastructure\Writer\Writer;

class SftpWriter extends Writer
{
    private $connection;
    private $sftp;

    public function connect(string $server, int $port, string $user, string $password): void
    {
        $this->connection = ssh2_connect($server, $port);
        ssh2_auth_password($this->connection, $user, $password);
        $this->sftp = ssh2_sftp($this->connection);
    }

    public function disconnect(): void
    {
        ssh2_disconnect($this->connection);
    }

    public function write(string $fileName, string $string): void
    {
        file_put_contents($this->getConnectionString() . $fileName, $string);
    }

    public function writeDelay(string $fileName, string $string, int $retriesCount): void
    {
        $this->write($fileName, $string);
    }

    private function getConnectionString()
    {
        return 'ssh2.sftp://' . (int)$this->sftp;
    }
}