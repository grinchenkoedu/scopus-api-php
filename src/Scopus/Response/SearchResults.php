<?php

namespace Scopus\Response;

class SearchResults
{
    /** @var array */
    protected $data;

    /** @var SearchLinks */
    protected $links;

    /** @var Entry[] */
    protected $entries;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getTotalResults()
    {
        return isset($this->data['opensearch:totalResults']) ? $this->data['opensearch:totalResults'] : null;
    }

    public function getStartIndex()
    {
        return isset($this->data['opensearch:startIndex']) ? $this->data['opensearch:startIndex'] : null;
    }

    public function getItemsPerPage()
    {
        return isset($this->data['opensearch:itemsPerPage']) ? $this->data['opensearch:itemsPerPage'] : null;
    }

    public function getQuery()
    {
        return isset($this->data['opensearch:Query']) ? $this->data['opensearch:Query'] : null;
    }

    public function getNextCursor()
    {
        return isset($this->data['cursor']['@next']) ? $this->data['cursor']['@next'] : null;
    }

    public function getLinks()
    {
        if (!isset($this->data['link'])) {
            return null;
        }

        return $this->links ?: $this->links = new SearchLinks($this->data['link']);
    }

    /**
     * @return Entry[]
     */
    public function getEntries(): array
    {
        if (isset($this->data['entry'])) {
            return $this->entries ?: $this->entries = array_map(function ($entry) {
                return new Entry($entry);
            }, $this->data['entry']);
        }

        return [];
    }

    public function countEntries()
    {
        return isset($this->data['entry']) ? count($this->data['entry']) : 0;
    }
}
