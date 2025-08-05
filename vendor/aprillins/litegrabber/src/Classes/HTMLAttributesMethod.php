<?php namespace Aprillins\LiteGrabber\Classes;

use Aprillins\LiteGrabber\Abstracts\HTMLMethod;


/**
 * HTMLAttributesMethod Class
 * 
 * This is the class where the HTML tags become methods
 */
class HTMLAttributesMethod extends HTMLMethod
{
    /**
     * This is alphabetical list of some attribute often used in HTML
     *
     * @var array
     */
    protected $htmlattributes = [
        'alt', 'disabled', 'href', 'id', 'src', 'style', 'title', 'value',
        'name', 'class', 'rel', 'type'
    ];

    public function __construct()
    {
        parent::__construct($this->htmlattributes);
    }

    public function getAsProperty()
    {
        $htmlattributes = $this->getList();
        array_walk($htmlattributes, function(&$item){ $item = 'at'.ucfirst($item); } );
        return $htmlattributes;
    }

}