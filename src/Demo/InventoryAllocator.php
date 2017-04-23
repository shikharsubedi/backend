<?php
namespace Demo;


use Demo\Exception\InsufficientQuantityException;
use Demo\Exception\IllegalStateException;
use SplQueue;

class InventoryAllocator
{
    private $inputStream;

    private $inventory;

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

        $outputQueue = $this->fulfillOrders($orderInputQueues);

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

        $result = [];

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

        return [];

    }

}