<?php

namespace Scopus\Response;

class CitationCount
{
    const STATUS_FOUND = 'found';
    const STATUS_NOT_FOUND = 'not_found';

    /**
     * @var array
     */
    protected $data;

    /**
     * @var CitationCountLinks
     */
    protected $links;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * The raw status Scopus reported for this document, e.g. "found".
     *
     * @return string|null
     */
    public function getStatus()
    {
        return isset($this->data['@status']) ? $this->data['@status'] : null;
    }

    public function isFound(): bool
    {
        return isset($this->data['@status']) && $this->data['@status'] === 'found';
    }

    public function getIdentifier()
    {
        return isset($this->data['dc:identifier']) ? $this->data['dc:identifier'] : null;
    }

    public function getUrl()
    {
        return isset($this->data['prism:url']) ? $this->data['prism:url'] : null;
    }

    public function getDoi()
    {
        return isset($this->data['prism:doi']) ? $this->data['prism:doi'] : null;
    }

    public function getPii()
    {
        return isset($this->data['pii']) ? $this->data['pii'] : null;
    }

    public function getPumbedId()
    {
        return isset($this->data['pubmed_id']) ? $this->data['pubmed_id'] : null;
    }

    public function getEID()
    {
        return isset($this->data['eid']) ? $this->data['eid'] : null;
    }

    public function getArticleNumber()
    {
        return isset($this->data['article-number']) ? $this->data['article-number'] : null;
    }

    public function getCitationCount()
    {
        return isset($this->data['citation-count']) ? $this->data['citation-count'] : null;
    }

    public function getLinks()
    {
        if (!isset($this->data['link'])) {
            return null;
        }

        return $this->links ?: $this->links = new CitationCountLinks($this->data['link']);
    }
}
