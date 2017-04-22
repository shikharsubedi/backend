<?php
namespace Demo;

use Demo\Exception\IllegalItemException;
use Demo\Exception\InsufficientQuantityException;

class Inventory  implements \IteratorAggregate 
{
    /**
     * @var array
     */
    private $snapshot;

    const ITEMS = array('A', 'B', 'C', 'D', 'E');


    public function __construct(array $snapshot)
    {
        $this->validate($snapshot);
        $this->snapshot = $snapshot;
    }

    /**
     * @param array $input
     * @throws IllegalItemException
     * @throws InsufficientQuantityException
     */
    private function validate(array $input)
    {

        foreach ($input as $item => $quantity) {
            if (!in_array($item, static::ITEMS)) {
                throw new IllegalItemException("Invalid Item {$item} in Snapshot");
            }
            if ($quantity < 0) {
                throw new InsufficientQuantityException("Quantity cannot be less than 0");
            }
        }
    }

    public function increment($item, $quantity)
    {
        if (!array_key_exists($item, $this->snapshot)) {
            throw new IllegalItemException("Item {$item} not found in inventory");
        }
        $this->snapshot[$item] += $quantity;

    }

    public function decrement($item, $quantity)
    {
        if (!array_key_exists($item, $this->snapshot)) {
            throw new IllegalItemException("Item {$item} not found in inventory");
        }
        if ($this->snapshot[$item] < $quantity) {
            throw new InsufficientQuantityException("Item {$item} decrement cannot be satisfied");
        }

        $this->snapshot[$item] -= $quantity;
    }

    public function getItem($item)
    {
        if (!array_key_exists($item, $this->snapshot)) {
            throw new IllegalItemException("Item {$item} not found in inventory");
        }
        return $this->snapshot[$item];
    }


    public function getTotal()
    {
        return array_sum($this->snapshot);
    }
    
    public function getIterator()
    {
        return new \ArrayIterator($this->snapshot);
    }

}