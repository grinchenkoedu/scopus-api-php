<?php

namespace Scopus\Tests\Response;

use Scopus\Tests\TestCase;
use Scopus\Response\SearchResults;
use Scopus\Response\Entry;

class SearchResultsTest extends TestCase
{
    public function testSearchResultsGetters()
    {
        $mockData = [
            'opensearch:totalResults' => '100',
            'opensearch:startIndex' => '0',
            'opensearch:itemsPerPage' => '25',
            'opensearch:Query' => [
                '@role' => 'request',
                '@searchTerms' => 'author(smith)',
                '@startPage' => '0'
            ],
            'link' => [
                ['@ref' => 'self', '@href' => 'http://example.com/self'],
                ['@ref' => 'first', '@href' => 'http://example.com/first']
            ],
            'entry' => [
                [
                    'dc:title' => 'Sample Article',
                    'dc:creator' => 'Smith, J.',
                    'prism:publicationName' => 'Journal of Samples'
                ],
                [
                    'dc:title' => 'Another Article',
                    'dc:creator' => 'Doe, J.'
                ]
            ]
        ];

        $results = new SearchResults($mockData);

        $this->assertEquals(100, $results->getTotalResults());
        $this->assertEquals(0, $results->getStartIndex());
        $this->assertEquals(25, $results->getItemsPerPage());
        
        $query = $results->getQuery();
        $this->assertEquals('request', $query['@role']);
        
        $this->assertEquals('http://example.com/self', $results->getLinks()->getSelf());
        $this->assertEquals('http://example.com/first', $results->getLinks()->getFirst());
        
        $entries = $results->getEntries();
        $this->assertCount(2, $entries);
        $this->assertInstanceOf(Entry::class, $entries[0]);
        $this->assertEquals('Sample Article', $entries[0]->getTitle());
        $this->assertEquals('Journal of Samples', $entries[0]->getPublicationName());
        
        $this->assertEquals(2, $results->countEntries());
    }

    public function testSearchResultsGettersWithSparseData()
    {
        $results = new SearchResults([]);

        $this->assertNull($results->getTotalResults());
        $this->assertNull($results->getStartIndex());
        $this->assertNull($results->getItemsPerPage());
        $this->assertNull($results->getQuery());
        $this->assertNull($results->getNextCursor());
        $this->assertNull($results->getLinks());
        $this->assertNull($results->getEntries());
        $this->assertEquals(0, $results->countEntries());
    }

    public function testEntryGettersWithSparseData()
    {
        $results = new SearchResults(['entry' => [[]]]);

        $entry = $results->getEntries()[0];
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertNull($entry->getTitle());
        $this->assertNull($entry->getCreator());
        $this->assertNull($entry->getAuthkeywords());
        $this->assertNull($entry->getLinks());
        $this->assertNull($entry->getAuthors());
        $this->assertNull($entry->getAffiliations());
        $this->assertEquals(0, $entry->countAuthors());
    }
}
