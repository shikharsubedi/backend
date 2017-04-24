<?php
namespace Demo;


class Stream
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Order[]
     */
    private $orders;

    /**
     * @var integer
     */
    private $streamCount;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Order[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @return int
     */
    public function getStreamCount()
    {
        return $this->streamCount;
    }
    

    /**
     * @param integer $id
     * @param Order $order
     */
    public function addOrder($id, Order $order)
    {
        $this->orders[$id] = $order;
    }

    /**
     * @param $id
     * @return Order
     */
    public function getOrder($id)
    {
        return $this->orders[$id];
    }

    /**
     * Stream constructor.
     * @param $id
     * @param $streamCount
     * 
     */
    public function __construct($id, $streamCount)
    {
        $this->id = $id;
        $this->streamCount = $streamCount;
        $this->orders = [];
    }

    /**
     * @return array
     */
    public function getStreamTotal()
    {
        $streamTotal = [];
        foreach ($this->getOrders() as $order) {
            foreach ($order->getOrderTotal() as $key => $value) {
                if (!isset($streamTotal[$key])) {
                    $streamTotal[$key] = $value;
                } else {
                    $streamTotal[$key] += $value;
                }
            }

        }
        return $streamTotal;

    }

    /**
     * used by the JsonGenerator class while generating the final json string
     * @return array
     */
    public function generateOutputArray()
    {
        $result = [];
        $result['id'] = $this->getId();

        foreach ($this->getOrders() as $order) {
            $result['orders'][] = $order->generateOutputArray();
        }

        return $result;
    }


}