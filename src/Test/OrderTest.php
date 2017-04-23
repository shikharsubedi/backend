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
        $dataSource->generate();

        $streams = $dataSource->getStreams();

        foreach ($streams as $stream) {
            foreach ($stream->getOrders() as $order) {
                $this->assertGreaterThan(0, array_sum($order->getOrderItems()));
            }
        }

    }

    public function testOrderHasItemCountLessThan6()
    {
        $dataSource = $this->getDataSource();
        $dataSource->generate();

        $streams = $dataSource->getStreams();

        foreach ($streams as $stream) {
            foreach ($stream->getOrders() as $order) {

                foreach ($order->getOrderItems() as $item => $quantity) {
                    $this->assertLessThanOrEqual(Order::MAX_ITEMS, $quantity);
                }

            }
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
            'C' => 0,
            'D' => 0,
            'E' => 2,
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

        $dataSource = new DataSource($inventory, $streamGenerator);

        return $dataSource;

    }

}