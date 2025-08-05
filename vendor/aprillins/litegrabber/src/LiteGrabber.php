<?php namespace Aprillins\LiteGrabber;

use Aprillins\LiteGrabber\Interfaces\HtmlTag;
use Aprillins\LiteGrabber\Classes\HTMLTagsMethod;
use Aprillins\LiteGrabber\Classes\HTMLAttributesMethod;
use Aprillins\LiteGrabber\Classes\HTMLMethodProcessor;
use Aprillins\LiteGrabber\Classes\ArgumentProcessor;
use Aprillins\LiteGrabber\Classes\LiteGrabberProcessor;
use Aprillins\LiteGrabber\Classes\ResultProcessor;
use DomDocument;
use DOMXPath;
use Exception;

/**
 * LiteGrabber main class.
 * For xpath syntax go to http://www.w3schools.com/xpath/xpath_syntax.asp
 * 
 * @package    LiteGrabber
 * @author     Aprillins <aprillins@gmail.com>
 * @link       http://github.com/aprillins/LiteGrabber
 * @license    MIT
 */
class LiteGrabber
{
    /**
     * Variable to put all the processed query in.
     *
     * @var string
     */
    protected $query;

    /**
     * DOMXPath instantiation stored in this variable.
     *
     * @var object
     */
    protected $xPathObj;

    /**
     * HTMLTagsMethod instantiation variable.
     *
     * @var object
     */
    private $tagsMethod; 

    /**
     * HTMLAttributeMethod instantiation variable.
     *
     * @var object
     */
    private $attributesMethod;

    /**
     * HTMLMethodProcessor instantiation variable.
     *
     * @var object
     */
    private $htmlMethodProcessor;

    /**
     * Determine first call of the get function as true.
     * It means if the first method fired then first call should be false
     *
     * @var boolean
     */
    private $firstCall = true;

    /**
     * Calling callFunctions() and initGrabber() functions as initialization.
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->htmlMethodProcessor = new HTMLMethodProcessor();
        $this->initGrabber($url);
    }

    /**
     * Static function to create this class instance
     *
     * @return object LiteGrabber
     */
    public static function create($url)
    {
        $instance = new self($url);
        return $instance;
    }

    /**
     * If you're gonna test the class using PHPUnit you can call either
     * by using this method or the class constructor argument. This method
     * disable the curlGet() method, so you can get the test results faster.
     *
     * @param string $method
     * @param array $args
     * @return null
     */
    public function initGrabber($url)
    {
        $this->clearQuery();
        $lgp = new LiteGrabberProcessor($url);
        $this->xPathObj = $lgp->getXPathObj();
    }

    /**
     * This is magic method to filter the method chains. It requires
     * HMTLMethodProcessor class to filter the method. If one of the method
     * in the chain is not found, then this method will return false
     *
     * @param string $method
     * @param array $args
     * @return object|false
     */
    public function __call($method, $args)
    {   
        $args = $this->checkArgs($args);
        $this->htmlMethodProcessor->filter($method, $args, $this->query);
        $query = $this->htmlMethodProcessor->getQuery();
        $this->setQuery($query);
        
        if (!$this->getQuery())
            return false;

        return $this;
    }

    /**
     * Check if the function is the first function called by this class.
     * It means LiteGrabber->div()->h2()->getQuery() the div is considered
     * as the first call. Then the isFirstCall will return true for the first
     * time and return false for the secont time when it goes to h2() function.
     *
     * @return boolean
     */
    public function isFirstCall()
    {
        if (!$this->firstCall)
            return false;

        return true;
    }   

    /**
     * Checks and modifies the $args variable if isFirstCall() method returns
     * true.
     *
     * @return array $args
     */
    private function checkArgs($args)
    {
        if ($this->isFirstCall()) {
            $this->firstCall = false;
            if (empty($args[0]))
                $args[0] = array();
            if (empty($args[1]))    
                $args[1] = true;
        }

        return $args;
    }

    /**
     * A shortcut method of query() method of DOMXPath.
     *
     * @param string $query
     * @return object
     */
    public function query($query)
    {
        try {
            return $this->xPathObj->query($query);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * An alternative method of query() method of DOMXPath.
     *
     * @param string $query
     * @return object
     */
    public function execute($query)
    {
        return $this->query($query);
    }

    /**
     * Set query string to $query variable.
     *
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
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
     * @return null
     */
    public function clearQuery()
    {
        $this->firstCall = true;
        $this->query = null;
        $this->htmlMethodProcessor->clearQuery();
    }

    /**
     * Utilizing the ResultProcessor class, you can easily get the result
     * in form of array. When this method called the $query private variable
     * will be automatically cleared.
     *
     * @return array
     */
    public function getResult()
    {
        $result = new ResultProcessor($this->execute($this->query));
        $this->clearQuery();
        return $result->getNodeValue();
    }

}