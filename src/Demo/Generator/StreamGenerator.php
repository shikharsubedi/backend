<?php
namespace Demo\Generator;

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
     */
    public function __construct(OrderGenerator $orderGenerator, $streamCount, array $randomIncrement)
    {
        $this->orderGenerator = $orderGenerator;
        $this->streamCount = $streamCount;
        $this->randomIncrement = $randomIncrement;
    }

    /**
     * @param Inventory $totalAllocation
     * @return Stream[]
     */
    public function generate(Inventory $totalAllocation)
    {
        $streams = [];
        for ($i = 1; $i <= $this->streamCount; $i++) {
            $streams[$i] = $this->build($i, $totalAllocation);
        }
        return $streams;
    }

    /**
     * @param int $id
     * @param Inventory $totalAllocation
     * @return Stream
     */
    private function build($id, Inventory $totalAllocation)
    {
        $items = $this->generateItemCount($totalAllocation);
        $streamInventory = new Inventory($items);
        $streamAllocation = clone $streamInventory;
        $headerCount = 1;
        $stream = new Stream($id, $this->streamCount, $streamAllocation);

        while (($streamInventory->getTotal()) > 0) {
            $order = $this->orderGenerator->generateOrder($headerCount, $streamInventory);
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
}