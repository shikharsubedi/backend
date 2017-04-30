<?php
namespace Demo\Generator;

use Demo\Exception\InsufficientQuantityException;
use Demo\Inventory;
use Demo\IteratorInventoryInterface;
use Demo\Order;

class OrderGenerator implements OrderGeneratorInterface
{
    const MAX_ITEMS = 5;

    /**
     * @param $id
     * @param IteratorInventoryInterface $streamInventory
     * @param IteratorInventoryInterface $totalInventory
     * @return Order
     */
    public function generateOrder($id, IteratorInventoryInterface $streamInventory, IteratorInventoryInterface $totalInventory)
    {
        $resultArray = $this->buildOutputArray($streamInventory, $totalInventory);
        $order = new Order($id);
        $order->setOrderItems($resultArray);
        return $order;
    }

    /**
     * @param IteratorInventoryInterface $streamInventory
     * @param IteratorInventoryInterface $totalInventory
     * @return array
     */
    private function buildOutputArray(IteratorInventoryInterface $streamInventory, IteratorInventoryInterface $totalInventory)
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
                $decrement = $streamInventory->getItemQuantity($item);
                $streamInventory->decrement($item, $decrement);
            }
            try {
                $totalInventory->decrement($item, $decrement);
            } catch (InsufficientQuantityException $e) {
                $itemQuantity = $totalInventory->getItemQuantity($item);
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
     * @param IteratorInventoryInterface $totalInventory
     * @return Order
     */
    public function generateFinalOrder($id, IteratorInventoryInterface $totalInventory)
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

    /**
     * @return array
     */
    private function buildRandomOrderItem()
    {
        $orderItem = [];
        $items = Inventory::ITEMS;
        $randKeys = array_rand($items, 1);
        $item = $items[$randKeys];
        $orderItem[$item] = rand(1, Order::MAX_ITEMS);

        return $orderItem;
    }


}