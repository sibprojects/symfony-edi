<?php

namespace App\EdiTransfer\Domain\Mapping;

class OrderStatusMapping
{
    public static array $data = [
        OrderStatusEnum::PENDING                => 'PEN',
        OrderStatusEnum::ONSITE_LOADING         => 'OSL',
        OrderStatusEnum::LOADED                 => 'LOD',
        OrderStatusEnum::NOT_LOADED             => 'NTL',
        OrderStatusEnum::LEFT_SITE_LOADING      => 'LSL',
        OrderStatusEnum::ONSITE_DELIVERY        => 'OND',
        OrderStatusEnum::DELIVERED              => 'DEL',
        OrderStatusEnum::LEFT_SITE_DELIVERY     => 'LSD',
        OrderStatusEnum::RETURNED               => 'RET',
        OrderStatusEnum::CANCELED               => 'CNL',
    ];

    public static function getStatus($currentStatus)
    {
        return self::$data[$currentStatus];
    }
}