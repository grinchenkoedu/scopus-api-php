<?php

namespace Scopus\Response;

class EntryAuthor extends AuthorName implements IAuthor 
{
    public function __construct(array $data)
    {
        parent::__construct(array_merge($data, [
            'indexed-name' => $data['authname']
        ]));
    }
    
    public function getId()
    {
        return isset($this->data['authid']) ? $this->data['authid'] : null;
    }
    
    public function getName()
    {
        return isset($this->data['authname']) ? $this->data['authname'] : null;
    }
    
    public function getAffiliationId()
    {
        return isset($this->data['afid'][0]['$']) ? $this->data['afid'][0]['$'] : null;
    }
    
    public function getUrl()
    {
        return isset($this->data['author-url']) ? $this->data['author-url'] : null;
    }
}