<?php

namespace Scopus\Response;

class AbstractAuthor extends AuthorName implements IAuthor
{
    /** @var array */
    protected $data;

    /** @var AuthorName */
    protected $preferredName;

    public function __construct(array $data)
    {
        parent::__construct($data, 'ce');
    }

    public function getId()
    {
        return isset($this->data['@auid']) ? $this->data['@auid'] : null;
    }

    public function getSeq()
    {
        return isset($this->data['@seq']) ? $this->data['@seq'] : null;
    }

    public function getPreferredName()
    {
        if (!isset($this->data['preferred-name'])) {
            return null;
        }

        return $this->preferredName ?: $this->preferredName = new AuthorName($this->data['preferred-name'], 'ce');
    }

    public function getUrl()
    {
        return isset($this->data['author-url']) ? $this->data['author-url'] : null;
    }
}
