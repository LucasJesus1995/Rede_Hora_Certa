<?php namespace Aprillins\LiteGrabber\Classes;

class ArgumentProcessor
{
        
    protected $args;
    protected $method;
    protected $query;

    public function __construct($method = '', $args = '', $query = '')
    {
        $this->args = $args;
        $this->method = $method;
        $this->query = $query;
    }

    public function setArgs($args = '')
    {
        $this->args = $args;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function setMethod($method = '')
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setQuery($query = '')
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }
}