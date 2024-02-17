<?php

namespace App\EdiTransfer\Domain\Exception;

class EdiTransferFactoryException extends EdiTransferException
{
    public function __toString(): string
    {
        $previous = [];
        $e = $this;
        while($e = $e->getPrevious()){
            $previous[] = (string)$e;
        }

        return 'EdiTransfer Factory error [' . $this->getCode() . ']: ' . $this->getMessage() .
            (count($previous) ? "\r\n" . implode("\r\n", $previous) : '');
    }
}