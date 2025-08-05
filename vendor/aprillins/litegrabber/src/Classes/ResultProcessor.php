<?php namespace Aprillins\LiteGrabber\Classes;

use DOMNodeList;

class ResultProcessor
{
    /**
     * Storing result of DOMNodeList object in this $result variable
     *
     * @var object DOMNodeList
     */
    private $result;

    /**
     * Storing number of result from DOMNodeList object
     *
     * @var int
     */
    private $length = 0;

    /**
     * Storing nodeValue extracted from DOMNodeList object
     *
     * @var array
     */
    private $nodeValue = array();

    /**
     * Storing the $result to $result private variable and $result->length to
     * $length private variable of this class, and execute the process() 
     * method to fill the $nodeValue variable with DOMNodeList $result content.
     *
     * @param DOMNodeList $result
     */
    public function __construct(DOMNodeList $result)
    { 
        $this->result = $result;
        $this->length = $result->length;
        $this->process();
    }

    /**
     * Process the DOMNodeList result so it return as array instead of 
     * $item(0)->nodeValue. The result stored in $nodeValue variable of this
     * class.
     *
     */
    public function process()
    {
        if ($this->length == 0)
            return false;
        
        for ($i = 0; $i < $this->length; $i++) {
            $this->nodeValue[] = $this->result->item($i)->nodeValue;
        }
        return true;
    }

    /**
     * Get the $nodeValue variable content.
     *
     * @return array
     */
    public function getNodeValue()
    {
        return $this->nodeValue;
    }
}