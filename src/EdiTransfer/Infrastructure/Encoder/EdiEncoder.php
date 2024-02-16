<?php

namespace App\EdiTransfer\Infrastructure\Encoder;

use EDI\Encoder as EEncoder;

class EdiEncoder extends Encoder
{
    public function encode(array $data): string
    {
        $encoder = new EEncoder($data, false); //one segment per line
        return "UNA:+.? '\n" . $encoder->get();
    }
}