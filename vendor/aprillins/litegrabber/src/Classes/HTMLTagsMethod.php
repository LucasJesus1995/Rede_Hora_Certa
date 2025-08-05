<?php namespace Aprillins\LiteGrabber\Classes;

use Aprillins\LiteGrabber\Abstracts\HTMLMethod;

/**
 * HTMLTagsMethod Class
 * 
 * This is the class where the HTML tags become methods
 */
class HTMLTagsMethod extends HTMLMethod
{
    /**
     * The HTML tags must be registered before it can be used as methods.
     * All tags are available at http://www.w3schools.com/tags/
     *
     * @var array;
     */
    private $htmltags = [
        'html', 'head', 'body', 'style', 'title', 'meta', 'link', 'script',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'header', 'footer', 'nav', 'div', 'section', 'aside', 'article', 'form', 'table', 'pre', 'blockquote', 'code',
        'ul', 'ol', 'li', 'dd', 'dl', 'dt', 'tr', 'td', 'th', 'thead', 'tfoot',
        'p', 'a', 'span', 'strong', 'em', 'label', 'cite', 'u', 'i', 'b',
        'img', 'figure', 'embed', 'canvas', 'caption',
        'input', 'select', 'option', 'button', 'textarea'
    ];

    public function __construct()
    {
        parent::__construct($this->htmltags);
        return $this->htmltags;
    }

}