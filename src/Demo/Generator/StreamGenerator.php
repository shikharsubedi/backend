<?php
namespace Demo\Generator;

use Demo\Exception\InsufficientQuantityException;
use Demo\Order;
use Demo\Stream;
use Demo\Inventory;

class StreamGenerator
{

    /**
     * Total number of Streams
     * @var integer
     */
    private $streamCount;

    /**
     * @var array
     */
    private $randomIncrement;

    /**
     * @var OrderGenerator
     */
    private $orderGenerator;

    /**
     * @var Inventory
     */
    private $totalInventory;


    /**
     * @return int
     */
    public function getStreamCount()
    {
        return $this->streamCount;
    }

    /**
     * @param int $streamCount
     */
    public function setStreamCount($streamCount)
    {
        $this->streamCount = $streamCount;
    }

    /**
     * @return OrderGenerator
     */
    public function getOrderGenerator()
    {
        return $this->orderGenerator;
    }

    /**
     * @return array
     */
    public function getRandomIncrement()
    {
        return $this->randomIncrement;
    }

    /**
     * @param array $randomIncrement
     */
    public function setRandomIncrement(array $randomIncrement)
    {
        $this->randomIncrement = $randomIncrement;
    }

    /**
     * StreamGenerator constructor.
     * @param OrderGenerator $orderGenerator
     * @param $streamCount
     * @param array $randomIncrement
     * @param Inventory $totalInventory
     */
    public function __construct(OrderGenerator $orderGenerator, $streamCount, array $randomIncrement, Inventory $totalInventory)
    {
        $this->orderGenerator = $orderGenerator;
        $this->streamCount = $streamCount;
        $this->randomIncrement = $randomIncrement;
        $this->totalInventory = $totalInventory;
    }

    /**
     * 
     * @return Stream[]
     */
    public function generate()
    {
        $totalAllocation = clone $this->totalInventory;
        $streams = [];
        for ($i = 1; $i <= $this->streamCount; $i++) {
            $streams[$i] = $this->build($i, $totalAllocation);

        }

        $streams = $this->ensureCorrectness($streams);
        return $streams;
    }

    /**
     * @param int $id
     * @param Inventory $totalAllocation
     * @return Stream
     */
    private function build($id, Inventory $totalAllocation)
    {

        $items = $this->generateItemCount($this->totalInventory);
        $streamInventory = new Inventory($items);
        $headerCount = 1;
        $stream = new Stream($id, $this->streamCount);

        while ((($streamInventory->getTotal()) > 0) && (($totalAllocation->getTotal()) > 0)) {
            $order = $this->orderGenerator->generateOrder($headerCount, $streamInventory, $totalAllocation);
            $stream->addOrder($headerCount, $order);
            $headerCount++;
        }

        return $stream;
    }

    /**
     * @param Inventory $totalAllocation
     * @return array
     */
    private function generateItemCount(Inventory $totalAllocation)
    {
        $items = [];
        foreach (Inventory::ITEMS as $item) {
            $items[$item] = ceil(($totalAllocation->getItem($item) + $this->randomIncrement[$item]) / $this->streamCount);
        }
        return $items;

    }

    /**
     * @param Stream[] $streams
     * @return Stream[]
     */
    private function ensureCorrectness(array $streams)
    {
        $testInventory = clone $this->totalInventory;
        $orderId = 0;
        foreach ($streams as $stream) {
            foreach ($stream->getOrders() as $orderId => $order) {
                $order->fulfillOrder($testInventory);
            }
        }
        if ($testInventory->getTotal() != 0) {
            $stream = $streams[$this->streamCount];
            $order = $this->orderGenerator->generateFinalOrder(($orderId + 1), $testInventory);
            $stream->addOrder(($orderId + 1), $order);
            $streams[$this->streamCount] = $stream;
        }

        return $streams;
        
    }
    
}