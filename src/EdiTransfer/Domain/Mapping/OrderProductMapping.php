<?php

namespace App\EdiTransfer\Domain\Mapping;

class OrderProductMapping
{
    public static array $data = [
        "product"   => [
            ['FTX', ['1' => 'AAA']], 4, false
        ],
        "weight"    => [
            ['MEA', ['1' => 'WT']], 3, 1
        ],
        "volume"    => [
            ['MEA', ['1' => 'VOL']], 3, 1
        ],
        "reference" => [
            ['RFF', ['1.0' => 'CW']], 1, 1
        ],
    ];
}