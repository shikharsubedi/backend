<?php
require_once __DIR__ . '/vendor/autoload.php';

use Demo\DataSource;
use Demo\Inventory;
use Demo\Generator\StreamGenerator;
use Demo\Generator\OrderGenerator;
use Demo\InventoryAllocator;
use Demo\Generator\StringGenerator;


$inventoryArray = array(
    'A' => 150,
    'B' => 150,
    'C' => 100,
    'D' => 100,
    'E' => 200,
);
/**
 * this randomIncrement allows the generation of back orders
 */
$randomIncrement = array(
    'A' => 2,
    'B' => 8,
    'C' => 3,
    'D' => 5,
    'E' => 6,
);

$inventory = new Inventory($inventoryArray);
$orderGenerator = new OrderGenerator();
$totalNumberOfStreams = 3;
$streamGenerator = new StreamGenerator($orderGenerator, $totalNumberOfStreams, $randomIncrement, $inventory);
$dataSource = new DataSource($inventory, $streamGenerator);
$streams = $dataSource->generate();
$stringGenerator = new StringGenerator();

$inputString = $stringGenerator->generateInputString($streams);
$inventoryAllocator = new InventoryAllocator($inputString, $inventory);
$outputQueue = $inventoryAllocator->allocate();

echo $stringGenerator->generateOutputString($outputQueue);



