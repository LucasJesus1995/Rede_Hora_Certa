<?php

use Aprillins\LiteGrabber\LiteGrabber;
use Stringy\Stringy as S;

class LiteGrabberTest extends PHPUnit_Framework_TestCase
{
    private $lg;

    public function setUp()
    {
        
        $this->lg = new LiteGrabber('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-2.html');
        
    }
    
    public function testStaticFunctionCreateReturnLiteGrabber()
    {
        $im = LiteGrabber::create('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-2.html');
        $this->assertInstanceOf('Aprillins\LiteGrabber\LiteGrabber', $im);
    }
    
    public function testQueryIfItIsNotEmptyNotNull()
    {
        $query = '//article/h2/a';
        $xpath = $this->lg->query($query);
        $this->assertNotEmpty($xpath);
        $this->assertNotNull($xpath);
    }

    public function testCallingOneDynamicFunctionEndWithGetQuery()
    {
        $query = $this->lg->article()->getQuery();
        $this->assertNotNull($query);
        $this->assertEquals('//article', $query);
    }

    public function testCallingTwoDynamicFunctionsEndWithGetQuery()
    {
        $query = $this->lg->article()->h2()->getQuery();
        $this->assertNotNull($query);
        $this->assertEquals('//article/h2', $query);

        $this->lg->clearQuery();
        $query = $this->lg->article()->h2(['class' => 'post'])->getQuery();
        $this->assertEquals('//article/h2[@class="post"]', $query);
    }

    public function testCallingOneDynamicFunctionWithArgumentsEndWithGetQuery()
    {
        // Set XPath anywhere selector // to false
        $query = $this->lg->article(['class' => 'post'])->getQuery();
        $this->assertNotNull($query);
        $this->assertEquals('//article[@class="post"]', $query);

        // Clear the $query
        $this->lg->clearQuery();

        // Set XPath anywhere selector // to true
        $query = $this->lg->article(['class' => 'post'], true)->getQuery();
        
        $this->assertNotNull($query);
        $this->assertEquals('//article[@class="post"]', $query);
    }

    public function testIfMethodIsNotFoundReturnFalseWhetherUsingGetQueryOrNot()
    {
        $query = $this->lg->badhtmltag();
        // var_dump($query);
        $this->assertFalse($query);
    }

    public function testClearQueryReturnsNullOnGetQuery()
    {
        $query = $this->lg->div()->getQuery();
        $this->lg->clearQuery();
        $query = $this->lg->getQuery();
        $this->assertNull($query);
    }

    public function testCallingOneAtDynamicFunctionEndWithGetQuery()
    {
        $query = $this->lg->div(['class' => 'carousel-inner'], true)->div()->img()->atSrc()->getQuery();
        $this->assertNotNull($query);
    }

    public function testIfCallingDynamicFunctionsEndWithGetQueryEndsWithNonObject()
    {
        $query = $this->lg->div()->div()->a()->img()->atSrc()->getQuery();
        $title = $this->lg->query($query);
        $this->assertFalse(is_object($title->item(0)));
        
    }

    
}
