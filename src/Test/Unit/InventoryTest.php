<?php
namespace Test\Unit;

use Demo\Exception\IllegalItemException;
use Demo\Exception\InsufficientQuantityException;
use Demo\Exception\ZeroQuantityException;
use Demo\Inventory;
use PHPUnit_Framework_TestCase;


class InventoryTest extends PHPUnit_Framework_TestCase
{

    public function testInstantiationThrowsIllegalItemException()
    {
        $inputArray = array(
            'A' => 5,
            'B' => 2,
            'C' => 3,
            'D' => 4,
            'E' => 4,
            'F' => 1,
        );
        $this->expectException(IllegalItemException::class);
        $inventory = new Inventory($inputArray);
    }

    public function testInstantiationThrowsInsufficientQuantityException()
    {
        $inputArray = array(
            'A' => -2,
            'B' => 3,
            'C' => 4,
            'D' => 4,
            'E' => 5,
        );
        $this->expectException(InsufficientQuantityException::class);
        $inventory = new Inventory($inputArray);
    }

    public function testInstantiationThrowsZeroQuantityException()
    {
        $inputArray = array(
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0,
        );
        $this->expectException(ZeroQuantityException::class);
        $inventory = new Inventory($inputArray);
    }

    public function testGetItemQuantity()
    {
        $inventory = $this->getInventory();
        $this->assertSame(5, $inventory->getItemQuantity('A'));

    }

    public function testGetItemThrowsIllegalItemException()
    {
        $inventory = $this->getInventory();
        $this->expectException(IllegalItemException::class);
        $inventory->getItemQuantity('G');
    }

    public function testDecrement()
    {
        $inventory = $this->getInventory();
        $inventory->decrement('A', 3);
        $this->assertEquals(2, $inventory->getItemQuantity('A'));
    }

    public function testDecrementThrowsInsufficientQuantityException()
    {
        $inventory = $this->getInventory();

        $this->expectException(InsufficientQuantityException::class);

        $inventory->decrement('E', 3);

    }

    public function testDecrementThrowsIllegalItemException()
    {
        $inventory = $this->getInventory();

        $this->expectException(IllegalItemException::class);

        $inventory->decrement('F', 3);
    }

    public function testGetTotal()
    {
        $inventory = $this->getInventory();

        $this->assertEquals(26, $inventory->getTotal());
    }


    /**
     * @return Inventory
     */
    private function getInventory()
    {
        $input = array(
            'A' => 5,
            'B' => 6,
            'C' => 7,
            'D' => 6,
            'E' => 2,
        );
        $inventory = new Inventory($input);
        return $inventory;
    }
}