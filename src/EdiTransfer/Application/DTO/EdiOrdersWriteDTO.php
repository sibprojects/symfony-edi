<?php

namespace App\EdiTransfer\Application\DTO;

use App\EdiTransfer\Domain\Mapping\OrderStatusMapping;

class EdiOrdersWriteDTO
{
    private array $orders;

    public function __construct(
        array $orders = [],
    )
    {
        $this->orders = $orders;
    }

    public function formatData(int $transactionNumber): array
    {
        $dateTime = new \DateTime();
        $dataOrders = [
            ["UNB", ["UNOY", "3"], "CARRIER", "NAME", [$dateTime->format('ymd'), $dateTime->format('Hi')], $transactionNumber],
            ["UNH", "1", ["IFTSTA", "D", "01B", "UN"]],
            ["BGM", "7", $transactionNumber, "9"],
            ["DTM", ["137", $dateTime->format('YmdHis'), "204"]],
            ["NAD", "FP", ["614792", "", "87"]],
        ];

        /** @var Commandes $order */
        foreach ($this->orders as $key => $order) {
            $status = $this->getOrderStatus($order);
            if ($status === '') {
                continue;
            }
            $dataOrders[] = ["CNI", $key + 1, $order->getReference()];
            $dataOrders[] = ["STS", "1", $status];
            $dataOrders[] = ["RFF", ["CU", $order->getReference()]];
            $dataOrders[] = ["RFF", ["SRN", $order->getRoute()?->getRouteRef()]];
            $dataOrders[] = ["DTM", ["78", $dateTime->format('YmdHis'), "204"]];
        }

        $dataOrders[] = ["UNT", count($dataOrders), "1"];
        $dataOrders[] = ["UNZ", "1", $transactionNumber];

        return $dataOrders;
    }

    private function getOrderStatus(Commandes $order)
    {
        return OrderStatusMapping::getStatus($order->getCurrentStatus());
    }
}
