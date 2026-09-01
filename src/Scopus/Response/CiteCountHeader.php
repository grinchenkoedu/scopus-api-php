<?php

namespace Scopus\Response;

class CiteCountHeader
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getPrevColumnHeader()
    {
        return isset($this->data["prevColumnHeader"]) ? $this->data["prevColumnHeader"] : null;
    }

    public function getColumnHeading()
    {
        return isset($this->data["columnHeading"]) ? $this->data["columnHeading"] : null;
    }

    public function getLaterColumnHeading()
    {
        return isset($this->data["laterColumnHeading"]) ? $this->data["laterColumnHeading"] : null;
    }

    public function getPrevColumnTotal()
    {
        return isset($this->data["prevColumnTotal"]) ? $this->data["prevColumnTotal"] : null;
    }

    public function getColumnTotal()
    {
        return isset($this->data["columnTotal"]) ? $this->data["columnTotal"] : null;
    }

    public function getLaterColumnTotal()
    {
        return isset($this->data["laterColumnTotal"]) ? $this->data["laterColumnTotal"] : null;
    }

    public function getRangeColumnTotal()
    {
        return isset($this->data["rangeColumnTotal"]) ? $this->data["rangeColumnTotal"] : null;
    }

    public function getGrandTotal()
    {
        return isset($this->data["grandTotal"]) ? $this->data["grandTotal"] : null;
    }
}
