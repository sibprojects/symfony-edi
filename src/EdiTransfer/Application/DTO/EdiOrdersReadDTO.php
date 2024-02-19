<?php

namespace App\EdiTransfer\Application\DTO;

class EdiOrdersReadDTO
{
    private int $shipperId;
    private array $data;

    public function __construct(
        int   $shipperId = 0,
        array $data = [],
    )
    {
        $this->shipperId = $shipperId;
        $this->data = $data;
    }

    public function formatData(): array
    {
        $dataOrders = [];
        foreach ($this->data['orders'] as $order) {
            $weight = 0;
            $volume = 0;
            $products = [];
            foreach ($order['products'] as $product) {
                $products[] = [
                    "product" => $product['name'],
                    "product_reference" => $product['reference'],
                    "units"             => 1,
                ];
                $weight += $product['weight'];
                $volume += $product['volume'];
            }
            $dataOrders[] = [
                "order_reference"    => $order['reference'],
                "status"             => $order['status'],
                "planning_datetimes" => [
                    "start_loading"   => $order['date_start_loading']->format('c'),
                    "end_loading"     => $order['date_end_loading']->format('c'),
                    "start_unloading" => $order['date_start_delivery']->format('c'),
                    "end_unloading"   => $order['date_end_delivery']->format('c'),
                ],
                "load"               => [
                    "units"                 => count($products),
                    "weight"                => $weight,
                    "volume"                => $volume,
                    "products"              => $products,
                ],
                "comment"            => $order['comment'],
                "route_reference"    => $this->data['route']['reference'],
                "pickup_address"   => $order['loading_address'],
                "delivery_address" => $order['delivery_address'],
                "assignment"       => [
                    "vehicle" => $this->data['route']['vehicle_type'],
                    "driver"  => $this->data['route']['carrier'],
                    "shipper" => $this->shipperId,
                ],
            ];
        }
        return $dataOrders;
    }
}
