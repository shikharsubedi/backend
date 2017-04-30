<?php
namespace Demo;

interface StreamInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return Order[]
     */
    public function getOrders();

    /**
     * @return integer
     */
    public function getStreamCount();

    /**
     * @param $id
     * @param OrderInterface $order
     * 
     */
    public function addOrder($id, OrderInterface $order);

    /**
     * @param  integer $id
     * @return OrderInterface
     */
    public function getOrder($id);

    /**
     * the items and their total quantities in the stream
     * @return array
     */
    public function getStreamTotal();

    /**
     * used by the JsonGenerator class while generating the final json string
     * @return array
     */
    public function generateOutputArray();
}