<?php

namespace App\EdiTransfer\Domain\Mapping;

class RouteMapping
{
    public static array $data = [
        "reference"           => [
            ['RFF', ['1.0' => 'SRN']], 1, 1
        ],
        "status"              => "Pending",
        "comment"             => [
            ['FTX', ['1' => 'ZZ1']], 4, false
        ],
        "freight_payer"       => [
            ['NAD', ['1' => 'FP']], 2, 0
        ],
        "carrier"             => [
            ['NAD', ['1' => 'CA']], 2, false
        ],
        "vehicle_type"        => [
            ['TDT', ['1' => '20']], 4, 1
        ],
        "date_start_loading"  => [
            ['DTM', ['1.0' => '189']], 1, 1
        ],
        "date_start_delivery" => [
            ['DTM', ['1.0' => '232']], 1, 1
        ],

        "loading_mode"  => "",
        "units"         => [
            ['CNT', ['1.0' => '11']], 1, 1
        ],
        "pallets_count" => "",
        "weight"        => [
            ['CNT', ['1.0' => '7']], 1, 1
        ],
        "volume"        => [
            ['CNT', ['1.0' => '57']], 1, 1
        ],
        "value"         => "",

        "service1" => [
            ['TSR', ['1' => 'TRM']], 2, false
        ],
        "service2" => [
            ['TOD', ['1' => '6']], 3, false
        ],
    ];
}