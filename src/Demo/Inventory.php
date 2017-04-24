<?php
namespace Demo;

use ArrayIterator;
use Demo\Exception\IllegalItemException;
use Demo\Exception\InsufficientQuantityException;
use Demo\Exception\ZeroQuantityException;

class Inventory implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $snapshot;

    const ITEMS = array('A', 'B', 'C', 'D', 'E');

    /**
     * Inventory constructor.
     * @param array $snapshot
     */
    public function __construct(array $snapshot)
    {
        $this->validate($snapshot);
        $this->snapshot = $snapshot;
    }

    /**
     * @param array $input
     * @throws IllegalItemException
     * @throws InsufficientQuantityException
     * @throws ZeroQuantityException
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
        if (array_sum($input) == 0) {
            throw new ZeroQuantityException("All items cannot have 0 quantity");
        }
    }

    /**
     * @param $item
     * @param $quantity
     */
    public function increment($item, $quantity)
    {
        if (!array_key_exists($item, $this->snapshot)) {
            throw new IllegalItemException("Item {$item} not found in inventory");
        }
        $this->snapshot[$item] += $quantity;

    }

    /**
     * @param $item
     * @param $quantity
     */
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

    /**
     * @param $item
     * @return mixed
     */
    public function getItem($item)
    {
        if (!array_key_exists($item, $this->snapshot)) {
            throw new IllegalItemException("Item {$item} not found in inventory");
        }
        return $this->snapshot[$item];
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return array_sum($this->snapshot);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->snapshot);
    }

}