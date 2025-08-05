<?php

use Aprillins\LiteGrabber\Classes\ArgumentProcessor;

class ArgumentProcessorTest extends PHPUnit_Framework_TestCase
{
    private $pa;

    public function setUp()
    {
        $this->pa = new ArgumentProcessor('get', ['the', 'args'], '//div/img/@src');
    }

    public function testIfGetMethodReturnsEquals()
    {
        $method = $this->pa->getMethod();
        $this->assertEquals('get', $method);
    }

    public function testIfArgumentProcessorCanBeInstantiated()
    {
        $this->assertInstanceOf('Aprillins\LiteGrabber\Classes\ArgumentProcessor', $this->pa, 'WORKS!');
    }
}