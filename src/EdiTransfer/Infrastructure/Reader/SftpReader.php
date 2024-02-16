<?php

namespace App\EdiTransfer\Infrastructure\Reader;

class SftpReader extends Reader
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

    public function read(string $fileName): string
    {
        return file_get_contents($this->getConnectionString() . $fileName);
    }

    public function getList(string $directory): array
    {
        $content = [];
        $handle = opendir($this->getConnectionString() . $directory);
        while (false !== ($entry = readdir($handle))) {
            if (( $entry !== '.' ) && ( $entry !== '..' )) {
                $content[] = $directory . '/' . $entry;
            }
        }
        closedir($handle);
        sort($content);
        return array_reverse($content);
    }

    private function getConnectionString()
    {
        return 'ssh2.sftp://' . (int)$this->sftp;
    }
}