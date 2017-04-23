<?php
namespace Demo;

use Demo\Generator\StreamGenerator;
use Demo\Generator\StringGenerator;

class DataSource
{
    /**
     * @var StreamGenerator
     */
    private $streamGenerator;

    /**
     * @var Inventory
     */
    private $inventory;

    /**
     * @return StreamGenerator
     */
    public function getStreamGenerator()
    {
        return $this->streamGenerator;
    }

    public function __construct(Inventory $inventory, StreamGenerator $streamGenerator)
    {
        $this->inventory = $inventory;
        $this->streamGenerator = $streamGenerator;
    }


    /**
     * @return Inventory
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * @return Stream[]
     */
    public function generate()
    {
        return $this->streamGenerator->generate($this->inventory);

    }

    /**
     * @param StringGenerator $stringGenerator
     * @param Stream[] $streams
     * @return string
     */
    public function generateJsonStream(StringGenerator $stringGenerator, $streams)
    {
        return $stringGenerator->generateInputString($streams);
    }

}