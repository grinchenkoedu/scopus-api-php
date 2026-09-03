<?php

namespace Scopus\Tests\Response;

use Scopus\Tests\TestCase;
use Scopus\Response\AbstractCitations;
use Scopus\Response\AbstractCoredata;
use Scopus\Response\CiteInfo;
use Scopus\Response\EntryAuthor;

class AbstractCitationsTest extends TestCase
{
    public function testCitationsGetters()
    {
        $citations = new AbstractCitations([
            'h-index' => '7',
            'identifier-legend' => [
                'identifier' => [
                    ['dc:identifier' => 'SCOPUS_ID:111'],
                    ['dc:identifier' => 'SCOPUS_ID:222'],
                ],
            ],
            'citeInfoMatrix' => [
                'citeInfoMatrixXML' => [
                    'citationMatrix' => [
                        'citeInfo' => [
                            ['sort-year' => '2020', 'author' => [['authname' => 'Smith J.']]],
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertSame('7', $citations->getHindex());

        $identifiers = $citations->getIdentifiers();
        $this->assertCount(2, $identifiers);
        $this->assertInstanceOf(AbstractCoredata::class, $identifiers[0]);
        $this->assertSame('SCOPUS_ID:111', $identifiers[0]->getIdentifier());

        $citeInfos = $citations->getCiteInfos();
        $this->assertCount(1, $citeInfos);
        $this->assertInstanceOf(CiteInfo::class, $citeInfos[0]);
        $this->assertSame('2020', $citeInfos[0]->getSortYear());

        $authors = $citeInfos[0]->getAuthors();
        $this->assertCount(1, $authors);
        $this->assertInstanceOf(EntryAuthor::class, $authors[0]);
    }

    public function testCitationsGettersWithSparseData()
    {
        $citations = new AbstractCitations([]);

        $this->assertNull($citations->getHindex());
        $this->assertSame([], $citations->getIdentifiers());
        $this->assertSame([], $citations->getCiteInfos());
        $this->assertSame([], (new CiteInfo([]))->getAuthors());
    }
}
