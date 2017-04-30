<?php
namespace Demo;

interface InventoryInterface
{
    /**
     * @param string $item
     * @param integer $quantity
     */
    public function decrement($item, $quantity);

    /**
     * @param string $item
     * @return integer
     */
    public function getItemQuantity($item);

    /**
     * @return integer
     */
    public function getTotal();
}