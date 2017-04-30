<?php
namespace Demo\Generator;

use Demo\Stream;
use SplQueue;

class StringGenerator implements OutputStringGeneratorInterface, InputStringGeneratorInterface
{

    /**
     * @param StreamInterface[] $streams
     * @return string
     */
    public function generateInputString($streams)
    {
        $result = [];
        foreach ($streams as $id => $stream) {
            $result['streams'][] = $stream->generateOutputArray();
        }

        $string = json_encode($result);

        return $string;
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
        $outputArray = array(
            $queueItem['id'],
            implode(",", array_values($queueItem['orderArray'])),
            implode(",", array_values($queueItem['fulfillArray'])),
            implode(",", array_values($queueItem['backOrder'])),
        );


        $output = join("::", $outputArray);

        return $output;

    }

}