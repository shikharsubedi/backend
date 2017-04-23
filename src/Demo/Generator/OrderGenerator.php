<?php
namespace Demo\Generator;

use Demo\Exception\InsufficientQuantityException;
use Demo\Inventory;
use Demo\Order;

class OrderGenerator
{
    const MAX_ITEMS = 5;

    public function generateOrder($id, Inventory $streamInventory, Inventory $totalInventory)
    {
        $resultArray = $this->buildOutputArray($streamInventory, $totalInventory);
        $order = new Order($id);
        $order->setOrderItems($resultArray);
        return $order;
    }

    private function buildOutputArray(Inventory $streamInventory, Inventory $totalInventory)
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
            try {
                $totalInventory->decrement($item, $decrement);
            } catch (InsufficientQuantityException $e) {
                $itemQuantity = $totalInventory->getItem($item);
                $totalInventory->decrement($item, $itemQuantity);
            }


            $resultArray[$item] = $decrement;

        }
        if (array_sum($resultArray) == 0 && $totalInventory->getTotal() != 0) {
            return $this->buildOutputArray($streamInventory, $totalInventory);
        } elseif (array_sum($resultArray) == 0 && $totalInventory->getTotal() == 0) {
            return $this->buildRandomOrderItem();

        } else {
            ksort($resultArray);
            return $resultArray;
        }
    }

    /**
     * @param $id
     * @param Inventory $totalInventory
     * @return Order
     */
    public function generateFinalOrder($id, Inventory $totalInventory)
    {
        $order = new Order($id);

        $orderItems = [];

        foreach ($totalInventory as $item => $quantity) {
            $orderItems[$item] = $quantity;
        }
        $order->setOrderItems($orderItems);
        return $order;
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

    private function buildRandomOrderItem()
    {
        $orderItem = [];
        $items = Inventory::ITEMS;
        $randKeys = array_rand($items, 1);
        $item = $items[$randKeys];
        $orderItem[$item] = rand(1, 5);

        return $orderItem;
    }


}