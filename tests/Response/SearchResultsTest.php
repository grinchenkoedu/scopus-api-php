<?php

namespace Scopus\Tests\Response;

use PHPUnit\Framework\TestCase;
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
}
