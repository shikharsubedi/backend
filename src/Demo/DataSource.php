<?php
namespace Demo;

use Demo\Generator\InputStringGeneratorInterface;
use Demo\Generator\StreamGenerator;
use Demo\Generator\StreamGeneratorInterface;


class DataSource
{
    /**
     * @var StreamGenerator
     */
    private $streamGenerator;

    /**
     * @var IteratorInventoryInterface
     */
    private $inventory;

    /**
     * @return StreamGenerator
     */
    public function getStreamGenerator()
    {
        return $this->streamGenerator;
    }

    /**
     * DataSource constructor.
     * @param IteratorInventoryInterface $inventory
     * @param StreamGeneratorInterface $streamGenerator
     */
    public function __construct(IteratorInventoryInterface $inventory, StreamGeneratorInterface $streamGenerator)
    {
        $this->inventory = $inventory;
        $this->streamGenerator = $streamGenerator;
    }


    /**
     * @return IteratorInventoryInterface
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
        return $this->streamGenerator->generate();

    }

    /**
     * @param InputStringGeneratorInterface $stringGenerator
     * @param StreamInterface $streams
     * @return string
     */
    public function generateJsonStream(InputStringGeneratorInterface $stringGenerator, $streams)
    {
        return $stringGenerator->generateInputString($streams);
    }

}