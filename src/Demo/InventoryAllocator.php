<?php
namespace Demo;


use Demo\Exception\InsufficientQuantityException;
use Demo\Exception\IllegalStateException;
use SplQueue;

class InventoryAllocator
{
    private $inputStream;

    private $inventory;

    private $outputQueue;

    /**
     * @return mixed
     */
    public function getInputStream()
    {
        return $this->inputStream;
    }

    /**
     * @return Inventory
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * @return mixed
     */
    public function getOutputQueue()
    {
        return $this->outputQueue;
    }


    public function __construct($inputStream, Inventory $inventory)
    {
        $this->inputStream = $inputStream;
        $this->inventory = $inventory;
    }

    public function allocate()
    {
        $inputArray = json_decode($this->inputStream, true);

        $streams = $inputArray['streams'];

        $streamInputQueue = new MinPriorityQueue();

        foreach ($streams as $stream) {
            $streamInputQueue->insert($stream, $stream['id']);
        }

        $orderInputQueues = $this->generateOrderInputQueues($streamInputQueue);

        return $this->fulfillOrders($orderInputQueues);

    }

    /**
     * @param MinPriorityQueue $streamInputQueue
     * @return MinPriorityQueue[]
     */
    private function generateOrderInputQueues(MinPriorityQueue $streamInputQueue)
    {
        $orderInputQueues = [];
        while ($streamInputQueue->valid()) {
            $current = $streamInputQueue->current();
            $orderInputQueue = new MinPriorityQueue();
            $orders = $current['data']['orders'];
            foreach ($orders as $order) {
                $orderInputQueue->insert($order['items'], $order['id']);
            }
            $orderInputQueues[$current['priority']] = $orderInputQueue;

            $streamInputQueue->next();
        }

        return $orderInputQueues;
    }

    /**
     * @param MinPriorityQueue[] $orderInputQueues
     * @return SplQueue
     * @throws IllegalStateException
     */
    private function fulfillOrders(array $orderInputQueues)
    {
        $outputQueue = new SplQueue();

        foreach ($orderInputQueues as $streamId => $orderQueue) {
            $outputQueue->enqueue("Stream" . $streamId);

            while ($orderQueue->valid()) {
                $current = $orderQueue->current();
                $outputQueue->enqueue($this->fulfillSingleOrder($current['data'], $current['priority']));

                if ($this->inventory->getTotal() == 0) {
                    return $outputQueue;
                }
            }
        }
        if ($this->inventory->getTotal() != 0) {
            throw new IllegalStateException("Inventory total must be 0 after order fulfillment");
        }
        return $outputQueue;
    }

    private function fulfillSingleOrder(array $orderItems, $id)
    {

        $backOrder = $this->getEmptyOrder();
        $orderArray = $this->getOrder($orderItems);
        $fulfillArray = $this->getEmptyOrder();
        foreach ($orderItems as $item => $quantity) {

            try {
                $this->inventory->decrement($item, $quantity);
                $fulfillArray[$item] = $quantity;

            } catch (InsufficientQuantityException $e) {
                $backOrder[$item] = $quantity;
            }

        }

        return array(
            'id' => $id,
            'orderArray' => $orderArray,
            'fulfillArray' => $fulfillArray,
            'backOrder' => $backOrder,
        );

    }

    private function getEmptyOrder()
    {
        $emptyOrder = [];
        foreach (Inventory::ITEMS as $item) {
            $emptyOrder[$item] = 0;
        }
        return $emptyOrder;
    }

    private function getOrder(array $orderItems)
    {
        $order = [];
        foreach (Inventory::ITEMS as $item) {

            if (!isset($orderItems[$item])) {
                $order[$item] = 0;
            } else {
                $order[$item] = $orderItems[$item];
            }
        }

        return $order;
    }

}