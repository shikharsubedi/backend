<?php
namespace Demo;

use Demo\Exception\OrderNotAvailableException;

interface OrderInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * $orderItems in the array of items and quantities in the order
     * @return array
     */
    public function getOrderItems();

    /**
     * 
     * @param array $orderItems
     */
    public function setOrderItems(array $orderItems);

    /**
     * get the map of item and quantity values for the order.
     * If the item is not present, a 0 is placed as quantity
     *
     * @return array
     * @throws OrderNotAvailableException
     */
    public function getOrderTotal();

    /**
     * @param IteratorInventoryInterface $inventory
     * 
     */
    public function fulfillOrder(IteratorInventoryInterface $inventory);

    /**
     *  used by the JsonGenerator to generate the final json String
     * @return array
     */
    public function generateOutputArray();
}