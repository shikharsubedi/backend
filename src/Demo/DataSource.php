<?php
namespace Demo;

use Demo\Generator\StreamGenerator;

class DataSource
{
    /**
     * @var string
     */
    private $result;
    
    /**
     * @var Stream[];
     */
    private $streams;

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
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return Stream[]
     */
    public function getStreams()
    {
        return $this->streams;
    }

    public function generate()
    {
        $this->streams = $this->streamGenerator->generate($this->inventory);
    }
    

}