<?php

namespace Scopus\Response;

class CiteInfo extends AbstractCoredata implements IAbstract
{
    /** @var array */
    protected $data;

    /** @var Affiliation[] */
    protected $affiliations;

    /** @var EntryAuthor[] */
    protected $authors;

    //Entry of SEARCH_AUTHOR_URI
    /** @var AuthorName[] */
    protected $preferredName;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    //identifier is in AbstractCoredata

    //url is in AbstractCoredata

    //title is in AbstractCoredata

    /**
     * @return EntryAuthor[]|null
     */
    public function getAuthors()
    {
        if (isset($this->data['author'])) {
            return $this->authors ?: $this->authors = array_map(function ($author) {
                return new EntryAuthor($author);
            }, $this->data['author']);
        }
    }

    public function getCitationType()
    {
        return isset($this->data["$"]) ? $this->data["$"] : null;
    }

    public function getCitationTypeCode()
    {
        return isset($this->data["@code"]) ? $this->data["@code"] : null;
    }

    public function getSortYear()
    {
        return isset($this->data["sort-year"]) ? $this->data["sort-year"] : null;
    }

    public function getStartingPage()
    {
        return isset($this->data["prism:startingPage"]) ? $this->data["prism:startingPage"] : null;
    }

    public function getEndingPage()
    {
        return isset($this->data["prism:endingPage"]) ? $this->data["prism:endingPage"] : null;
    }

    //getPublicationName is in AbstractCoredata

    //issn is in AbstractCoredata

    public function getPreviousColumnCount()
    {
        return isset($this->data["pcc"]) ? $this->data["pcc"] : null;
    }

    public function getColumnCount()
    {
        return isset($this->data["cc"]) ? $this->data["cc"] : null;
    }

    public function getLaterColumnCount()
    {
        return isset($this->data["lcc"]) ? $this->data["lcc"] : null;
    }

    public function getRangeCount()
    {
        return isset($this->data["rangeCount"]) ? $this->data["rangeCount"] : null;
    }

    public function getRowTotal()
    {
        return isset($this->data["rowTotal"]) ? $this->data["rowTotal"] : null;
    }
}
