<?php
namespace Test;

use Demo\Inventory;
use Demo\Order;
use Demo\Generator\OrderGenerator;
use Demo\Generator\StreamGenerator;
use Demo\DataSource;

class OrderTest extends \PHPUnit_Framework_TestCase
{

    public function testOrderHasCountGreaterThanZero()
    {
        $dataSource = $this->getDataSource();
    }

    public function testOrderHasItemCountLessThan6()
    {
       
    }

    /**
     * @return DataSource
     */
    protected function getDataSource()
    {
        $inventoryArray = array(
            'A' => 18,
            'B' => 12,
            'C' => 8,
            'D' => 0,
            'E' => 10,
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
        
        $streamGenerator = new StreamGenerator($orderGenerator, 2, $randomIncrement);
        
        $dataSource = new DataSource($inventory,$streamGenerator);

        return $dataSource;
        
    }

}