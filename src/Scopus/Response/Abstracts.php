<?php

namespace Scopus\Response;

class Abstracts implements IAbstract
{
    /** @var array */
    protected $data;
    
    /** @var AbstractCoredata */
    protected $coredata;
    
    /** @var AbstractAuthor[] */
    protected $authors;
    
    /** @var AbstractItem */
    protected $item;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getCoredata()
    {
        if (!isset($this->data['coredata'])) {
            return null;
        }

        return $this->coredata ?? $this->coredata = new AbstractCoredata($this->data['coredata']);
    }

    /**
     * @return AbstractAuthor[]
     */
    public function getAuthors(): array
    {
        if (isset($this->data['authors']['author'])) {
            return $this->authors ?? $this->authors = array_map(function($author) {
                return new AbstractAuthor($author);
            }, $this->data['authors']['author']);
        }

        return [];
    }

    /**
     * @return int
     */
    public function countAuthors()
    {
        return count($this->getAuthors());
    }
    
    public function getItem()
    {
        if (!isset($this->data['item'])) {
            return null;
        }

        return $this->item ?? $this->item = new AbstractItem($this->data['item']);
    }
}