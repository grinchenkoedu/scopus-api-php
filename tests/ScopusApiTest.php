<?php

namespace Scopus\Tests;

use Scopus\ScopusApi;
use GuzzleHttp\Psr7\Response;
use Scopus\Response\Abstracts;
use Scopus\Response\Author;
use Scopus\Response\CitationCount;

class ScopusApiTest extends TestCase
{
    public function testRetrieveAuthorLimits()
    {
        $api = $this->getMockedApi([]);
        
        $ids = array_fill(0, 26, '123456');
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("The maximum number of 25 author id's exceeded!");
        
        $api->retrieveAuthor($ids);
    }
    
    public function testRetrieveAbstractLimits()
    {
        $api = $this->getMockedApi([]);
        
        $ids = array_fill(0, 26, '123456');
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("The maximum number of 25 abstract id's exceeded!");
        
        $api->retrieveAbstract($ids);
    }

    public function testRetrieveCitationCountLimits()
    {
        $api = $this->getMockedApi([]);
        
        $ids = array_fill(0, 26, '123456');
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("The maximum number of 25 document id's exceeded!");
        
        $api->retrieveCitationCount($ids);
    }

    public function testRetrieveCitationCountSuccessful()
    {
        $mockJson = '{
            "citation-count-response": {
                "document": {
                    "@status": "found",
                    "dc:identifier": "123",
                    "citation-count": "5"
                }
            }
        }';
        
        $api = $this->getMockedApi([
            new Response(200, [], $mockJson)
        ]);
        
        $results = $api->retrieveCitationCount(['123']);
        
        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(CitationCount::class, $results[0]);
        $this->assertEquals("123", $results[0]->getIdentifier());
        $this->assertEquals("5", $results[0]->getCitationCount());
        $this->assertEquals("found", $results[0]->getStatus());
    }
}
