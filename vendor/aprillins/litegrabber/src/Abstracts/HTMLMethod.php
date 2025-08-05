<?php namespace Aprillins\LiteGrabber\Abstracts;

abstract class HTMLMethod
{

    /**
     * HTML tags or HTML attributes array are now recognized as HTML item(s).
     * So, it will be stored in this variable and must have array data type.
     *
     * @var array
     */
    private $item;

    /**
     * Setting up the item
     *
     * @param array $item
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

     /**
     * Add a html item as a method to $item variable.
     *
     * @param string $method;
     */
    public function add($method)
    {
        if (!in_array($method, $this->item)) {
            $this->item[] = $method;
            return true;
        }    
        return false;
    }

    /**
     * Get available html items.
     *
     * @return array
     */
    public function getList()
    {
        return $this->item;
    }

    /**
     * Remove a html item from $item variable.
     * This function uses best array value removal according this test
     * http://stackoverflow.com/questions/1883421/removing-array-item-by-value
     *
     * @param string $method;
     */
    public function remove($method)
    {
        $tag = array_keys($this->item, $method);
        foreach ($tag as $t) {
            unset($this->item[$t]);
        }
    }
}