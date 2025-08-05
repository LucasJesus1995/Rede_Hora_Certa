<?php

use Aprillins\LiteGrabber\Classes\LiteGrabberProcessor as LGP;

class LiteGrabberProcessorTest extends PHPUnit_Framework_TestCase
{
    private $lgp;

    public function setUp()
    {
        $this->lgp = new LGP('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-2.html');
    }

    public function testIfLGPGetXPathObjReturnDOMXPathObject()
    {
        $this->assertInstanceOf('DOMXPath', $this->lgp->getXPathObj());
    }
}