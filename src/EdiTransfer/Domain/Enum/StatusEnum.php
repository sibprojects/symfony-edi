<?php

namespace App\EdiTransfer\Domain\Enum;

class StatusEnum
{
    public static array $data = [
        'ST01'     => 'Pick-up delayed by carrier',
        'ST02'     => 'Pick-up failed due to consignor issue',
        'ST03'     => 'Delayed due to extreme weather conditions',
        'ST04'     => 'Delivery delayed by carrier',
        'ST05'     => 'Delivery attempt failed (Recipient closed)',
    ];

    public static function getValue(string $keyName)
    {
        return self::$data[$keyName] ?? null;
    }
}
