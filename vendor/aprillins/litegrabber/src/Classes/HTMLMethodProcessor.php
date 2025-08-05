<?php namespace Aprillins\LiteGrabber\Classes;

use Aprillins\LiteGrabber\LiteGrabber;
use Aprillins\LiteGrabber\Classes\ProcessorArguments;
use Stringy\Stringy as S;

/**
 * This class searches target method if available and processes it in order
 * to get the proper XPath query string.
 *
 */
class HTMLMethodProcessor
{
    
    /**
     * HTMLTagsMethod instantiation variable.
     *
     * @var object;
     */
    private $tagsMethod; 

    /**
     * HTMLAttributeMethod instantiation variable.
     *
     * @var object;
     */
    private $attributesMethod;

    /**
     * HTMLMethodProcessor instantiation variable.
     *
     * @var object;
     */
    private $htmlMethodProcessor;

    public function __construct()
    {
        $htmlTagsMethod = new HTMLTagsMethod;
        $htmlAttributesMethod = new HTMLAttributesMethod;
        $this->tagsMethod = $htmlTagsMethod->getList();
        $this->attributesMethod = $htmlAttributesMethod->getAsProperty();
    }

    /**
     * Filtering the method, arguments, and query given by method chaining
     * 
     * @param string $method
     * @param array $args
     * @param string $query
     * @return nothing|false
     */
    public function filter($method, $args, &$query)
    {
        if (in_array($method, $this->tagsMethod))
            return $this->methodProcessor($method, $args, $query);
        
        if (in_array($method, $this->attributesMethod)) 
            return $this->atMethodProcessor($method, $args, $query);

        return false;
    }

    /**
     * This build query for XPath attribute.
     *
     * @param array $args
     * @return string
     */
    public function htmlTagsProcessor($args = array())
    { 
        if (!empty($args[0])) {
            $query = '[@'.key($args[0]).'="'.current($args[0]).'"]';
        } else {
            return;
        }
        return $query;
    }

    /**
     * Method processor for HTML tags.
     *
     * @param string $method
     * @param array $args
     * @return object
     */
    public function methodProcessor($method, $args, &$query)
    // public function process(ProcessorArguments $pa)
    {   
        $query = $this->htmlTagsProcessor($args);
        
        if (!empty($args[1]) && $args[1] == true) {
            $this->query .= '//'.$method.$query;
        } else {
            $this->query .= '/'.$method.$query;
        }
    }   
    
    /**
     * Method processor for HTML attributes.
     *
     * @param string $method
     * @param array $args
     * @return object
     */
    public function atMethodProcessor($method, $args, &$query)
    {
        $query = $this->htmlTagsProcessor($args);
        $method = S::create($method)->removeLeft('at')->toLowerCase();
        if (!empty($args[1]) && $args[1] == true) {
            $this->query .= '//@'.$method.$query;
        } else {
            $this->query .= '/@'.$method.$query;
        }
        //return $this->context;
    }

    /**
     * Get default query string from $query variable.
     *
     * @return string
     */
    public function getQuery()
    {     
        return $this->query;
    }

    /**
     * Clear or make the $query variable empty
     *
     */
    public function clearQuery()
    {
        $this->query = null;
    }

}