<?php
/**
 * Created by PhpStorm.
 * User: shikharsubedi
 * Date: 4/29/17
 * Time: 4:41 PM
 */
namespace Demo\Generator;

use SplQueue;

interface OutputStringGeneratorInterface
{
    /**
     * @param SplQueue $outputQueue
     * @return string
     */
    public function generateOutputString(SplQueue $outputQueue);
}