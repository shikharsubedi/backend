<?php
namespace Demo\Generator;

use Demo\Exception\InsufficientQuantityException;
use Demo\Inventory;
use Demo\Order;

class OrderGenerator
{
    const MAX_ITEMS = 5;
    
    public function generateOrder($id, Inventory $streamInventory)
    {
        $resultArray = $this->buildOutputArray($streamInventory);
        $order = new Order($id);
        $order->setOrderItems($resultArray);
        return $order;
    }

    private function buildOutputArray(Inventory $streamInventory)
    {
        $resultArray = [];
        $items = Inventory::ITEMS;
        $randKeys = $this->getRandKeys($items);
        
        foreach ($randKeys as $key) {
            $decrement = rand(0, static::MAX_ITEMS);
            $item = $items[$key];
            try {
                $streamInventory->decrement($item, $decrement);
            } catch (InsufficientQuantityException $e) {
                $decrement = $streamInventory->getItem($item);
                $streamInventory->decrement($item, $decrement);
            }
            $resultArray[$item] = $decrement;

        }
        if (array_sum($resultArray) === 0) {
            return $this->buildOutputArray($streamInventory);
        } else {
            ksort($resultArray);
            return $resultArray;
        }
    }

    /**
     * @param $items
     * @return array
     */
    private function getRandKeys($items)
    {
        $randKeys = array_rand($items, rand(1, count($items)));

        if (!is_array($randKeys)) {
            $randKeys = [$randKeys];
            return $randKeys;
        }
        return $randKeys;
    }

}