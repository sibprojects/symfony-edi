<?php

namespace App\EdiTransfer\Infrastructure\Parser;

use App\EdiTransfer\Domain\Enum\TransportEnum;
use App\EdiTransfer\Domain\Mapping\OrderMapping;
use App\EdiTransfer\Domain\Mapping\OrderProductMapping;
use App\EdiTransfer\Domain\Mapping\RouteMapping;
use EDI\Parser as EParser;
use EDI\Reader as EReader;
use DateTime;

class EdiParser extends Parser
{
    public function read(string $fileContent): array
    {
        $parser = new EParser();
        $parser->loadString($fileContent)->parse();
        return $this->parse($parser);
    }

    public function parse($parser): array
    {
        return $this->parseRoute($parser);
    }

    private function parseRoute($parser): array
    {
        $eReader = new EReader($parser);
        $result = [
            'route'  => [],
            'orders' => [],
        ];
        $result['route'] = $this->readByMapping(RouteMapping::$data, $eReader);
        $result['route']['vehicle_type_name'] = TransportEnum::getValue($result['route']['vehicle_type']);
        $result['route']['date_start_loading'] = new DateTime($result['route']['date_start_loading']);
        $result['route']['date_start_delivery'] = new DateTime($result['route']['date_start_delivery']);
        $result['orders'] = $this->parseOrders($parser);
        return $result;
    }

    private function parseOrders($parser): array
    {
        $result = [];
        $eReader = new EReader($parser);
        $orders = $eReader->groupsExtract('CNI', ['UNT']);
        foreach ($orders as $order) {
            $parser->loadArray($order, false);
            $eReaderOrder = new EReader($parser);
            $orderData = $this->readByMapping(OrderMapping::$data, $eReaderOrder);
            $orderData['date_start_loading'] = new DateTime($orderData['date_start_loading']);
            $orderData['date_end_loading'] = new DateTime($orderData['date_end_loading']);
            $orderData['date_start_delivery'] = new DateTime($orderData['date_start_delivery']);
            $orderData['date_end_delivery'] = new DateTime($orderData['date_end_delivery']);
            $orderData['products'] = $this->parseProducts($parser);
            $result[] = $orderData;
        }
        return $result;
    }

    private function parseProducts($parser): array
    {
        $result = [];
        $products = $this->extractEdiBlocks($parser, 'GID');
        foreach ($products as $product) {
            $parser->loadArray($product, false);
            $eReaderProduct = new EReader($parser);
            $productData = $this->readByMapping(OrderProductMapping::$data, $eReaderProduct);
            $result[] = $productData;
        }
        return $result;
    }

    private function readByMapping(array $mappingData, EReader $eReader): array
    {
        $result = [];
        foreach ($mappingData as $key => $val) {
            $value = $val;
            if (is_array($val)) {
                $value = $eReader->readEdiDataValue($val[0], $val[1], $val[2]);
                if (isset($val[3])) {
                    $value = explode($val[3][0], $value)[$val[3][1]];
                }
            }
            $result[$key] = $value;
        }
        return $result;
    }

    private function extractEdiBlocks(EParser $parser, string $blockName): array
    {
        $result = [];
        $data = $parser->get();
        $found = false;
        $blockData = [];
        foreach ($data as $val) {
            if ($val[0] === $blockName) {
                $found = true;
                if (count($blockData) > 0) {
                    $result[] = $blockData;
                    $blockData = [];
                }
            }
            if ($found) {
                $blockData[] = $val;
            }
        }
        if (count($blockData) > 0) {
            $result[] = $blockData;
        }
        return $result;
    }
}