<?php

use Aprillins\LiteGrabber\LiteGrabber;
use Aprillins\LiteGrabber\Classes\ResultProcessor;

class ResultProcessorTest extends PHPUnit_Framework_TestCase
{
    private $rp;
    private $lg;
    private $result;

    public function setUp()
    {
        $this->lg = new LiteGrabber('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-2.html');
        $query = $this->lg
            ->article([], true)
            ->h2(['class' => 'post-title'])
            ->a()
            ->getQuery();
        $this->result = $this->lg->execute($query);
        $this->rp = new ResultProcessor($this->result);
    }

    public function testIfThisResultIsObjectAndIsDOMNodeList()
    {
        $this->assertTrue(is_object($this->result));
        $this->assertInstanceOf('DOMNodeList', $this->result);
    }

    public function testProcessWithManyResults()
    {
        $this->assertTrue($this->rp->process());
    }

    public function testProcessWithSingleResult()
    {
        // $this->lg->clearQuery();
        $this->lg->setUrl('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-1.html');
        $this->lg->initGrabber('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-1.html');
        $query = $this->lg->div([], true)->a()->h1(['class' => 'blog-name'])->getQuery();
        $this->result = $this->lg->execute($query);
        $this->rp = new ResultProcessor($this->result);
        $this->assertTrue($this->rp->process());
        
    }
    public function testProcessNoResultReturned()
    {
        // $this->lg->clearQuery();
        $this->lg->setUrl('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-1.html');
        $this->lg->initGrabber('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-1.html');
        $query = $this->lg->article([], true)->h6()->getQuery();
        $this->result = $this->lg->execute($query);
        $this->rp = new ResultProcessor($this->result);
        $this->assertFalse($this->rp->process());

    }

    public function testIfNodeValueVariableReturnArray()
    {
        $this->rp->process();
        $this->assertNotEmpty($this->rp->getNodeValue());
    }
}