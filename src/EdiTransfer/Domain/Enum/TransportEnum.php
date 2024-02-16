<?php

namespace App\EdiTransfer\Domain\Enum;

class TransportEnum
{
    public static array $data = [
        '12T'               => 'Truck 12T',
        '3T5'               => 'Truck 3T5',
        '3T5 1,2T'          => 'Truck 3T5',
        'PV'                => 'Panel Van',
    ];

    public static function getValue(string $keyName)
    {
        return self::$data[$keyName] ?? null;
    }
}
