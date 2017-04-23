<?php
namespace Demo\Generator;

use Demo\Stream;
use SplQueue;

class StringGenerator
{

    /**
     * @param Stream[] $streams
     * @return string
     */
    public function generateInputString($streams)
    {
        $result = [];
        foreach ($streams as $id => $stream) {
            $result['streams'][] = $stream->generateOutputArray();
        }

        return json_encode($result);
    }

    /**
     * @param SplQueue $outputQueue
     * @return string
     */
    public function generateOutputString(SplQueue $outputQueue)
    {
        $output = "";
        while (!$outputQueue->isEmpty()) {
            $queueItem = $outputQueue->dequeue();

            if (is_string($queueItem)) {
                $output .= $queueItem . "\n";
            } else {
                $orderString = $this->processOrderString($queueItem);
                $output .= $orderString . "\n";
            }
        }

        return $output;
    }

    private function processOrderString(array $queueItem)
    {
        $outputArray = [];
        $outputArray[] = $queueItem['id'];
        $outputArray[] = array_values($queueItem['orderArray']);
        $outputArray[] = array_values($queueItem['fulfillArray']);
        $outputArray[] = array_values($queueItem['backOrder']);

        return implode("::", $outputArray);

    }

}