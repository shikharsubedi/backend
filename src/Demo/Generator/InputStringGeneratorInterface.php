<?php
namespace Demo\Generator;

use Demo\StreamInterface;

interface InputStringGeneratorInterface
{
    /**
     * @param StreamInterface[] $streams
     * @return string
     */
    public function generateInputString($streams);
}