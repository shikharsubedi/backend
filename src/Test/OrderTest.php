<?php
namespace Test;

use Demo\Order;
use Demo\Stream;


class OrderTest extends \PHPUnit_Framework_TestCase
{

    public function testOrderHasCountGreaterThanZero()
    {
        list($dataSource, $inventory) = StreamTest::getDataSource();

        /** @var Stream[] $streams */
        $streams = $dataSource->generate();

        foreach ($streams as $stream) {
            foreach ($stream->getOrders() as $order) {
                $this->assertGreaterThan(0, array_sum($order->getOrderItems()));
            }
        }

    }

    public function testOrderHasItemCountLessThan6()
    {
        list($dataSource, $inventory) = StreamTest::getDataSource();

        /** @var Stream[] $streams */
        $streams = $dataSource->generate();

        foreach ($streams as $stream) {
            foreach ($stream->getOrders() as $order) {

                foreach ($order->getOrderItems() as $item => $quantity) {
                    $this->assertLessThanOrEqual(Order::MAX_ITEMS, $quantity);
                }

            }
        }
    }


}