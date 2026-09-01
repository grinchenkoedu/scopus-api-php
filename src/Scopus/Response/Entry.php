<?php

namespace Scopus\Response;

class Entry extends AbstractCoredata implements IAbstract
{
    /** @var array */
    protected $data;

    /** @var EntryLinks */
    protected $links;

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

    public function getLinks()
    {
        if (!isset($this->data['link'])) {
            return null;
        }

        return $this->links ?: $this->links = new EntryLinks($this->data['link']);
    }


    public function getCreator()
    {
        return isset($this->data['dc:creator']) ? $this->data['dc:creator'] : null;
    }

    /**
     * @return EntryAuthor
     */
    public function getCreatorAuthor()
    {
        if (!$this->getAuthors()) return null;
        $matches = array_filter($this->getAuthors(), function (EntryAuthor $author) {
            return $author->getName() === $this->getCreator();
        });
        if ($matches) {
            return array_values($matches)[0];
        }
    }

    public function getCoverDisplayDate()
    {
        return isset($this->data['prism:coverDisplayDate']) ? $this->data['prism:coverDisplayDate'] : null;
    }

    public function getAffiliations()
    {
        if (isset($this->data['affiliation'])) {
            return $this->affiliations ?: $this->affiliations = array_map(function ($affiliation) {
                return new Affiliation($affiliation);
            }, $this->data['affiliation']);
        }
    }

    public function countAffiliations()
    {
        return isset($this->data['affiliation']) ? count($this->data['affiliation']) : 0;
    }

    public function getSubtype()
    {
        return isset($this->data['subtype']) ? $this->data['subtype'] : null;
    }

    public function getSubtypeDescription()
    {
        return isset($this->data['subtypeDescription']) ? $this->data['subtypeDescription'] : null;
    }

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

    /**
     * @return EntryAuthor[]
     */
    public function getCoAuthor()
    {
        if (!$this->getAuthors()) return null;
        $matches = array_filter($this->getAuthors(), function (EntryAuthor $author) {
            return $author->getName() !== $this->getCreator();
        });
        if ($matches) {
            return array_values($matches);
        }
    }

    public function countAuthors()
    {
        return isset($this->data['author']) ? count($this->data['author']) : 0;
    }

    public function getAuthkeywords()
    {
        return isset($this->data['authkeywords']) ? $this->data['authkeywords'] : null;
    }

    public function getYear()
    {
        if ($this->getCoverDate() != null) {
            $date = date_parse($this->getCoverDate());
        } else if ($this->getCoverDisplayDate() != null) {
            $date = date_parse($this->getCoverDisplayDate());
        }
        return (isset($date)) ? $date['year'] : null;
    }

    //Entry di SEARCH_AUTHOR_URI
    public function getPreferredName()
    {
        if (!isset($this->data['preferred-name'])) {
            return null;
        }

        return $this->preferredName ?: $this->preferredName = new AuthorName($this->data['preferred-name']);
    }

    public function getAffiliation()
    {
        return isset($this->data['affiliation-current']) ? $this->data['affiliation-current']['affiliation-name'] : null;
    }

    public function getAuthorID()
    {
        $identifier = $this->getIdentifier();
        if (substr($identifier, 0, 10) === 'AUTHOR_ID:') {
            return explode(':', $identifier, 2)[1];
        }
    }

    public function isEmpty()
    {
        return isset($this->data['error']);
    }

    public function isOpenAccess()
    {
        if (isset($this->data["openaccessFlag"])) return $this->data["openaccessFlag"];
        return (isset($this->data["openaccess"]) && $this->data["openaccess"] == "1") ? true : false;
    }
}
