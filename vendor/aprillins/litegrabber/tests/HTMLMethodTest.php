<?php

use Aprillins\LiteGrabber\Classes\HTMLTagsMethod;

class HTMLMethodTest extends PHPUnit_Framework_TestCase
{
    private $hm;

    public function setUp()
    {
        $this->hm = new HTMLTagsMethod;
    }

    public function testAddFunctionTohtmltagsVariable()
    {
        $oldhtmltags = $this->hm->getList();
        $this->hm->add('newhtmltags');
        $newhtmltags = $this->hm->getList();
        $this->assertNotEquals($oldhtmltags, $newhtmltags);
    }

    public function testAddFuntionTohtmlTagsVariableIfTagExistsReturnFalse()
    {
        $oldhtmltags = $this->hm->getList();
        $addIsWorking = $this->hm->add('div');
        $newhtmltags = $this->hm->getList();
        $this->assertFalse($addIsWorking);
    }

    public function testRemoveFunctionTohtmltagsVariable()
    {
        $oldhtmltags = $this->hm->getList();
        $this->hm->remove('div');
        $newhtmltags = $this->hm->getList();
        $this->assertNotEquals($oldhtmltags, $newhtmltags);
    }

    public function testRemoveFunctionTohtmltagsVariableIfTagNotFound()
    {
        $oldhtmltags = $this->hm->getList();
        $this->hm->remove('abc'); // There is no 'abc' value in htmltags array
        $newhtmltags = $this->hm->getList();
        $this->assertEquals($oldhtmltags, $newhtmltags);
    }


}