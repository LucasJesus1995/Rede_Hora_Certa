<?php namespace Aprillins\LiteGrabber\Classes;

use DomDocument;
use DOMXPath;

/**
 * The LiteGrabberProcessor class is the core of functionality of LiteGrabber 
 * class.
 *
 */
class LiteGrabberProcessor
{
    private $xPathObj;

    public function __construct($url)
    {
        $source = $this->curlGet($url);
        $this->xPathObj = $this->returnXPathObject($source);
    }

    /**
     * Return DOMXPath
     *
     * @return object DOMXPath
     */
    public function getXPathObj()
    {
        return $this->xPathObj;
    }

    /**
     * The cURL method to get content from a web page.
     *
     * @return string
     */
    private function curlGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * XPathObject builder using DomDocument class to process curlGet() method
     * result. The DomDocument object then becomes the resource for DOMXPath
     * to build the XPathObject.
     *
     * @param string $source
     * @return object DOMXPath
     */
    private function returnXPathObject($source)
    {
        $xmlPageDom = new DomDocument();
        @$xmlPageDom->loadHTML($source);
        return new DOMXPath($xmlPageDom);
    }
}