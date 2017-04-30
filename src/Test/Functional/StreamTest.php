<?php
namespace Test\Functional;

use Demo\Inventory;
use Demo\Generator\OrderGenerator;
use Demo\Generator\StreamGenerator;
use Demo\DataSource;
use Demo\Stream;
use PHPUnit_Framework_TestCase;

class StreamTest extends PHPUnit_Framework_TestCase
{
    
    public function testStreamTotalIsGreaterThanInventory()
    {
        list($dataSource, $inventory) = static::getDataSource();

        /** @var Stream[] $streams */
        $streams = $dataSource->generate();

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
     * @return array
     */
    public static function getDataSource()
    {
        $inventoryArray = array(
            'A' => 5,
            'B' => 4,
            'C' => 3,
            'D' => 0,
            'E' => 1,
        );

        $randomIncrement = array(
            'A' => 2,
            'B' => 8,
            'C' => 3,
            'D' => 0,
            'E' => 6,
        );

        $inventory = new Inventory($inventoryArray);

        $orderGenerator = new OrderGenerator();

        $streamGenerator = new StreamGenerator($orderGenerator, 1, $randomIncrement, $inventory);

        $dataSource = new DataSource($inventory, $streamGenerator);

        return array($dataSource, $inventory);

    }

}