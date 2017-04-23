<?php
namespace Demo;

use Demo\Exception\OrderNotAvailableException;

class Order
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var array
     */
    private $orderItems;

    const MAX_ITEMS = 5;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * Order constructor.
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $orderItems
     */
    public function setOrderItems(array $orderItems)
    {
        $this->orderItems = $orderItems;
    }


    /**
     * get the map of item and quantity values for the order.
     * If the item is not present, a 0 is placed as quantity
     *
     * @return array
     * @throws OrderNotAvailableException
     */
    public function getOrderTotal()
    {
        if (is_null($this->orderItems)) {
            throw new OrderNotAvailableException("OrderItems not available");
        }
        $total = [];

        foreach (Inventory::ITEMS as $item) {
            if (!isset($this->orderItems[$item])) {
                $total[$item] = 0;
            } else {
                $total[$item] = $this->orderItems[$item];
            }
        }

        return $total;
    }

    /**
     *  used by the JsonGenerator to generate the final json String
     * @return array
     */
    public function generateOutputArray()
    {
        $result = [];

        if (is_null($this->orderItems)) {
            throw new OrderNotAvailableException("OrderItems should be present");
        }

        $result['id'] = $this->getId();

        $result['items'] = $this->orderItems;

        return $result;
    }
}