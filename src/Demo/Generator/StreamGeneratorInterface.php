<?php
/**
 * Created by PhpStorm.
 * User: shikharsubedi
 * Date: 4/29/17
 * Time: 4:37 PM
 */
namespace Demo\Generator;

use Demo\Stream;

interface StreamGeneratorInterface
{
    /**
     *
     * @return Stream[]
     */
    public function generate();
}