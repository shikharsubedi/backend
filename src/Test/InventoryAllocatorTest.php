<?php
namespace Test;

use Demo\Generator\StringGenerator;
use Demo\Inventory;
use Demo\InventoryAllocator;
use PHPUnit_Framework_TestCase;
use Demo\Stream;

class InventoryAllocatorTest extends PHPUnit_Framework_TestCase
{

    public function testInventoryTotalZeroAfterAllocation()
    {
        /**@var Inventory $inventory */
        list($dataSource, $inventory) = StreamTest::getDataSource();

        /** @var Stream[] $streams */
        $streams = $dataSource->generate();

        $stringGenerator = new StringGenerator();
        $inputString = $stringGenerator->generateInputString($streams);

        $inventoryAllocator = new InventoryAllocator($inputString, $inventory);

        $outputQueue = $inventoryAllocator->allocate();

        foreach ($inventory as $key => $value) {
            $this->assertEquals(0, $value);
        }


    }

}