<?php
namespace Demo;


use Demo\Exception\InsufficientQuantityException;
use Demo\Exception\IllegalStateException;
use SplPriorityQueue;
use SplQueue;

class InventoryAllocator
{
    /**
     * the input json string
     * @var string
     */
    private $inputString;

    /**
     * @var Inventory
     */
    private $inventory;

    /**
     * @return mixed
     */
    public function getInputString()
    {
        return $this->inputString;
    }

    /**
     * @return Inventory
     */
    public function getInventory()
    {
        return $this->inventory;
    }


    /**
     * InventoryAllocator constructor.
     * @param $inputString
     * @param Inventory $inventory
     */
    public function __construct($inputString, Inventory $inventory)
    {
        $this->inputString = $inputString;
        $this->inventory = $inventory;
    }

    /**
     * method that takes the input string a generates the output queue
     * the output queue is used by the StringGenerator object to generate
     * the output string
     * 
     * @return SplQueue
     */
    public function allocate()
    {
        $inputArray = json_decode($this->inputString, true);
        $streams = $inputArray['streams'];

        $streamInputQueue = new MinPriorityQueue();
        $streamInputQueue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

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
            $orderInputQueue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
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

                $orderQueue->next();
            }
        }
        if ($this->inventory->getTotal() != 0) {
            throw new IllegalStateException("Inventory Total must be zero after fulfillment");
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