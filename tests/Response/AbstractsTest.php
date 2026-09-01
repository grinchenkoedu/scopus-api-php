<?php

namespace Scopus\Tests\Response;

use PHPUnit\Framework\TestCase;
use Scopus\Response\Abstracts;
use Scopus\Response\AbstractCoredata;
use Scopus\Response\AuthorGroup;

class AbstractsTest extends TestCase
{
    public function testAbstractsGetters()
    {
        $mockData = [
            'coredata' => [
                'dc:title' => 'Advanced Testing Techniques',
                'dc:identifier' => 'SCOPUS_ID:123456789'
            ],
            'authors' => [
                'author' => [
                    [
                        '@_fa' => 'true',
                        '@seq' => '1',
                        'ce:indexed-name' => 'Smith J.',
                        'ce:surname' => 'Smith',
                        'ce:given-name' => 'John'
                    ]
                ]
            ],
            'item' => [
                'bibrecord' => [
                    'head' => [
                        'author-group' => [
                            [
                                'affiliation' => [
                                    '@afid' => '100',
                                    'organization' => 'University of Testing'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $abstract = new Abstracts($mockData);

        $this->assertInstanceOf(AbstractCoredata::class, $abstract->getCoredata());
        $this->assertEquals('Advanced Testing Techniques', $abstract->getCoredata()->getTitle());
        $this->assertEquals('SCOPUS_ID:123456789', $abstract->getCoredata()->getIdentifier());
        
        $authors = $abstract->getAuthors();
        $this->assertCount(1, $authors);
        $this->assertEquals('Smith J.', $authors[0]->getIndexedName());
        
        $authorGroup = $abstract->getItem()->getBibrecord()->getHead()->getAuthorGroup();
        $this->assertInstanceOf(AuthorGroup::class, $authorGroup);
    }
}
