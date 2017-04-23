<?php
namespace Demo\Generator;

use Demo\Stream;

class JsonGenerator
{
    private $jsonResult;
    
    /**
     * @var Stream[]
     */
    private $streams;
    
    public function __construct(array $streams)
    {
        $this->streams = $streams;
    }

    public function generate()
    {
        $result = [];
        foreach ($this->streams as $id => $stream) {
            $result['streams'][] = $stream->generateOutputArray();
        }
        
        $this->jsonResult = json_encode($result);
        
        return $this->jsonResult;
    }
    

}