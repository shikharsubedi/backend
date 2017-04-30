<?php
namespace Demo\Generator;

use Demo\IteratorInventoryInterface;
use Demo\Stream;
use Demo\Inventory;
use Demo\StreamInterface;

class StreamGenerator implements StreamGeneratorInterface
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
     * StreamGenerator constructor.
     * @param OrderGeneratorInterface $orderGenerator
     * @param integer $streamCount
     * @param array $randomIncrement
     * @param IteratorInventoryInterface $totalInventory
     */
    public function __construct(OrderGeneratorInterface $orderGenerator, $streamCount, array $randomIncrement, IteratorInventoryInterface $totalInventory)
    {
        $this->orderGenerator = $orderGenerator;
        $this->streamCount = $streamCount;
        $this->randomIncrement = $randomIncrement;
        $this->totalInventory = $totalInventory;
    }

    /**
     * 
     * @return StreamInterface[]
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
     * @param integer $id
     * @param IteratorInventoryInterface $totalAllocation
     * @return StreamInterface
     */
    private function build($id, IteratorInventoryInterface $totalAllocation)
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
     * @param IteratorInventoryInterface $totalAllocation
     * @return array
     */
    private function generateItemCount(IteratorInventoryInterface $totalAllocation)
    {
        $items = [];
        foreach (Inventory::ITEMS as $item) {
            $items[$item] = ceil(($totalAllocation->getItemQuantity($item) + $this->randomIncrement[$item]) / $this->streamCount);
        }
        return $items;

    }

    /**
     * @param StreamInterface[] $streams
     * @return StreamInterface[]
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