<?php
namespace Demo;

use SplQueue;

interface InventoryAllocatorInterface
{
    /**
     * @return mixed
     */
    public function getInputString();

    /**
     * @return Inventory
     */
    public function getInventory();

    /**
     * method that takes the input string and generates the output queue
     * the output queue is used by the StringGenerator object to generate
     * the output string
     *
     * @return SplQueue
     */
    public function allocate();
}