<?php
namespace Test;

use Demo\Generator\JsonGenerator;
use Demo\Inventory;
use Demo\Stream;
use Demo\Order;
use Demo\Generator\OrderGenerator;
use Demo\Generator\StreamGenerator;
use Demo\DataSource;

class StreamTest extends \PHPUnit_Framework_TestCase
{

    public function testStreamTotal()
    {
        $dataSource = $this->getDataSource();

        $dataSource->generate();

        $streams = $dataSource->getStreams();

        foreach ($streams as $key => $stream) {
            $streamAllocation = $stream->getStreamAllocation();
            $streamTotal = $stream->getStreamTotal();
            foreach (Inventory::ITEMS as $item) {
                $this->assertLessThanOrEqual($streamTotal[$item], $streamAllocation->getItem($item));
            }
        }
        
    }

    public function testStreamTotalIsGreaterThanInventory()
    {
        $dataSource = $this->getDataSource();

        $dataSource->generate();

        $inventory = $dataSource->getInventory();

        $streams = $dataSource->getStreams();
        $total = [];

        foreach ($streams as $key => $stream) {
            $streamTotal = $stream->getStreamTotal();
            foreach (Inventory::ITEMS as $item) {
                if (!isset($total[$item])) {
                    $total[$item] = $streamTotal[$item];
                } else {
                    $total[$item] += $streamTotal[$item];
                }
            }
        }

        foreach ($inventory as $item => $quantity) {
            $this->assertLessThanOrEqual($total[$item], $quantity);
        }

    }

    /**
     * @return DataSource
     */
    protected function getDataSource()
    {
        $inventoryArray = array(
            'A' => 5,
            'B' => 4,
            'C' => 3,
            'D' => 2,
            'E' => 1,
        );

        $randomIncrement = array(
            'A' => 2,
            'B' => 8,
            'C' => 3,
            'D' => 0,
            'E' => 3,
        );

        $inventory = new Inventory($inventoryArray);

        $orderGenerator = new OrderGenerator();

        $streamGenerator = new StreamGenerator($orderGenerator, 1, $randomIncrement);

        $dataSource = new DataSource($inventory, $streamGenerator);

        return $dataSource;

    }

}