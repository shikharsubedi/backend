<?php
namespace Demo;

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


    public function __construct($id)
    {
        $this->id;
    }

    public function setOrderItems(array $orderItems)
    {
        $this->orderItems = $orderItems;
    }
    

    public function getOrderTotal()
    {
        if (is_null($this->orderItems)) {
            throw new \Exception("OrderItems not available");
        }
        $total = [];

        foreach (Inventory::ITEMS as $item) {
            if (!isset($this->orderItems[$item])) {
                $total[$item] = 0;
            }else{
                $total[$item] = $this->orderItems[$item];
            }
        }
        
        return $total;
    }
}