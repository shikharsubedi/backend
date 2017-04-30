<?php
namespace Demo\Generator;

use Demo\Stream;

interface InputStringGeneratorInterface
{
    /**
     * @param Stream[] $streams
     * @return string
     */
    public function generateInputString($streams);
}