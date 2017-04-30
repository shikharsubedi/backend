<?php
/**
 * Created by PhpStorm.
 * User: shikharsubedi
 * Date: 4/29/17
 * Time: 4:47 PM
 */
namespace Demo\Generator;

use Demo\IteratorInventoryInterface;
use Demo\Order;

interface OrderGeneratorInterface
{
    /**
     * @param $id
     * @param IteratorInventoryInterface $streamInventory
     * @param IteratorInventoryInterface $totalInventory
     * @return Order
     */
    public function generateOrder(
        $id,
        IteratorInventoryInterface $streamInventory,
        IteratorInventoryInterface $totalInventory
    );

    /**
     * @param $id
     * @param IteratorInventoryInterface $totalInventory
     * @return Order
     */
    public function generateFinalOrder($id, IteratorInventoryInterface $totalInventory);
}