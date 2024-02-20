<?php

namespace App\EdiTransfer\Domain\Mapping;

class OrderMapping
{
    public static array $data = [
        "reference"             => [
            ['RFF', ['1.0' => 'CU']], 1, 1
        ],
        "comment"               => [
            ['FTX', ['1' => 'AAA']], 4, false
        ],
        "date_start_loading"    => [
            ['DTM', ['1.0' => '398']], 1, 1, ['-', 0]
        ],
        "date_end_loading"      => [
            ['DTM', ['1.0' => '398']], 1, 1, ['-', 1]
        ],
        "date_start_delivery"   => [
            ['DTM', ['1.0' => '406']], 1, 1, ['-', 0]
        ],
        "date_end_delivery"     => [
            ['DTM', ['1.0' => '406']], 1, 1, ['-', 1]
        ],
        "shipper"               => [
            ['NAD', ['1' => 'CZ']], 2, false
        ],
        "shipper_name"          => [
            ['NAD', ['1' => 'CZ']], 4, 0
        ],
        "carrier"               => [
            ['NAD', ['1' => 'CN']], 2, false
        ],
        "carrier_name"          => [
            ['NAD', ['1' => 'CN']], 4, 0
        ],
        "loading_address"       => [
            ['NAD', ['1' => 'PW']], 2, false
        ],
        "loading_address_name"  => [
            ['NAD', ['1' => 'PW']], 4, 0
        ],
        "delivery_address"      => [
            ['NAD', ['1' => 'DP']], 2, false
        ],
        "delivery_address_name" => [
            ['NAD', ['1' => 'DP']], 4, 0
        ],
        "status" => "Pending",
        "volume" => [
            ['MEA', ['1' => 'LMT']], 3, 1
        ],
    ];
}