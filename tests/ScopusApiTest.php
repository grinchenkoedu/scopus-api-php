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
        $this->assertSame("found", $results[0]->getStatus());
        $this->assertTrue($results[0]->isFound());
    }

    public function testCitationCountNotFound()
    {
        // The whole point of the change: 'NOT_FOUND' used to coerce to true.
        $citationCount = new CitationCount(['@status' => 'NOT_FOUND']);

        $this->assertSame('NOT_FOUND', $citationCount->getStatus());
        $this->assertFalse($citationCount->isFound());
    }

    public function testCitationCountWithoutStatus()
    {
        // retrieve() uses @status to tell a single document from a list, so a
        // document reaching CitationCount through the client always has it.
        // Constructing one directly is how a caller hits the missing-key path.
        $citationCount = new CitationCount(['dc:identifier' => '123']);

        $this->assertNull($citationCount->getStatus());
        $this->assertFalse($citationCount->isFound());
        $this->assertNull($citationCount->getCitationCount());
        $this->assertNull($citationCount->getLinks());
    }

    public function testSearchAuthorsSuccessful()
    {
        $mockJson = '{
            "search-results": {
                "opensearch:totalResults": "1",
                "opensearch:startIndex": "0",
                "opensearch:itemsPerPage": "25",
                "opensearch:Query": {
                    "@role": "request",
                    "@searchTerms": "authlast(smith) and authfirst(john)",
                    "@startPage": "0"
                },
                "link": [],
                "entry": [
                    {
                        "dc:title": "Test Article",
                        "dc:creator": "Smith, J."
                    }
                ]
            }
        }';
        $api = $this->getMockedApi([new Response(200, [], $mockJson)]);
        $results = $api->searchAuthors('smith', 'john');
        
        $this->assertInstanceOf(\Scopus\Response\SearchResults::class, $results);
        $this->assertEquals(1, $results->getTotalResults());
        $this->assertEquals("Test Article", $results->getEntries()[0]->getTitle());
    }

    public function testRetrieveAbstractSuccessful()
    {
        $mockJson = '{
            "abstracts-retrieval-response": {
                "coredata": {
                    "dc:title": "Testing Title",
                    "dc:identifier": "SCOPUS_ID:123"
                }
            }
        }';
        $api = $this->getMockedApi([new Response(200, [], $mockJson)]);
        $results = $api->retrieveAbstract('123');
        
        $this->assertInstanceOf(Abstracts::class, $results);
        $this->assertEquals("Testing Title", $results->getCoredata()->getTitle());
    }

    public function testRetrieveAuthorSuccessful()
    {
        $mockJson = '{
            "author-retrieval-response": [
                {
                    "coredata": {
                        "dc:identifier": "AUTHOR_ID:123"
                    },
                    "author-profile": {
                        "preferred-name": {
                            "surname": "Doe",
                            "given-name": "John"
                        }
                    }
                }
            ]
        }';
        $api = $this->getMockedApi([new Response(200, [], $mockJson)]);
        $results = $api->retrieveAuthor('123');
        
        $this->assertInstanceOf(Author::class, $results);
        $this->assertEquals("AUTHOR_ID:123", $results->getCoredata()->getIdentifier());
        $this->assertEquals("Doe", $results->getProfile()->getPreferredName()->getSurname());
    }

    public function testRetrieveAbstractsWithNoIds()
    {
        $api = $this->getMockedApi([]);

        // No request should be made at all - MockHandler would throw if one were.
        $this->assertSame([], $api->retrieveAbstracts([]));
        $this->assertSame([], $api->retrieveAuthors([]));
    }

    public function testRetrieveAbstractsPropagatesFailures()
    {
        // This used to be swallowed and returned as [], indistinguishable from
        // a document that simply was not found.
        $api = $this->getMockedApi([
            new Response(200, ['Content-Type' => 'application/json'], '{invalid json}')
        ]);

        $this->expectException(\Scopus\Exception\JsonException::class);

        $api->retrieveAbstracts(['123']);
    }

    public function testRetrieveAuthorsDeduplicatesIds()
    {
        $mockJson = '{
            "author-retrieval-response": [
                {
                    "coredata": { "dc:identifier": "AUTHOR_ID:123" }
                }
            ]
        }';

        // Two ids, one distinct: the single-document path, so exactly one
        // response is queued. A second request would exhaust the MockHandler.
        $api = $this->getMockedApi([new Response(200, [], $mockJson)]);

        $authors = $api->retrieveAuthors(['123', '123']);

        $this->assertCount(1, $authors);
        $this->assertArrayHasKey('123', $authors);
        $this->assertInstanceOf(Author::class, $authors['123']);
    }

    public function testRetrieveAbstractsHandlesAChunkOfOne()
    {
        // 26 ids chunk as [25, 1]. Scopus answers a one-id request with a single
        // document rather than a list, which array_combine() cannot key.
        $multi = '{"abstracts-retrieval-multidoc-response":{"abstracts-retrieval-response":['
            . implode(',', array_fill(0, 25, '{"coredata":{"dc:identifier":"SCOPUS_ID:1"}}')) . ']}}';
        $single = '{"abstracts-retrieval-response":{"coredata":{"dc:identifier":"SCOPUS_ID:26"}}}';

        $api = $this->getMockedApi([
            new Response(200, [], $multi),
            new Response(200, [], $single),
        ]);

        $abstracts = $api->retrieveAbstracts(range(1, 26));

        $this->assertCount(26, $abstracts);
        $this->assertInstanceOf(Abstracts::class, $abstracts[26]);
    }

    public function testRetrieveAbstractsRefusesToKeyAPartialResult()
    {
        // Two ids asked for, one document returned: the results cannot be keyed
        // by id, and guessing would silently mislabel them.
        $json = '{"abstracts-retrieval-multidoc-response":{"abstracts-retrieval-response":['
            . '{"coredata":{"dc:identifier":"SCOPUS_ID:111"}}]}}';

        $api = $this->getMockedApi([new Response(200, [], $json)]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Scopus returned 1 of the 2 requested documents');

        $api->retrieveAbstracts(['111', '222']);
    }

    public function testXmlException()
    {
        $mockJson = '<invalid xml>';
        $api = $this->getMockedApi([
            new Response(200, ['Content-Type' => 'text/xml'], $mockJson)
        ]);
        
        $this->expectException(\Scopus\Exception\XmlException::class);
        $api->retrieveCitationCount(['123']);
    }

    public function testXmlExceptionWithoutLibxmlError()
    {
        // An empty body makes simplexml_load_string() return false without
        // recording a libxml error, so there is nothing to read a message from.
        $api = $this->getMockedApi([
            new Response(200, ['Content-Type' => 'text/xml'], '')
        ]);

        $this->expectException(\Scopus\Exception\XmlException::class);
        $this->expectExceptionMessage('Unknown XML parsing error');

        $api->retrieveCitationCount(['123']);
    }

    public function testXmlParsingLeavesTheCallersLibxmlErrorsAlone()
    {
        $previous = libxml_use_internal_errors(true);
        libxml_clear_errors();
        simplexml_load_string('<caller>');
        $callerMessages = $this->libxmlMessages();
        $this->assertNotEmpty($callerMessages);

        try {
            $this->getMockedApi([new Response(200, ['Content-Type' => 'text/xml'], '<broken>')])
                ->retrieveCitationCount(['123']);
        } catch (\Scopus\Exception\XmlException $e) {
            // expected
        }

        // The caller's errors must still be there, in order, ahead of ours.
        $after = $this->libxmlMessages();
        $this->assertSame($callerMessages, array_slice($after, 0, count($callerMessages)));

        libxml_clear_errors();
        libxml_use_internal_errors($previous);
    }

    private function libxmlMessages()
    {
        return array_map(function ($error) {
            return $error->message;
        }, libxml_get_errors());
    }

    public function testJsonException()
    {
        $mockJson = '{invalid json}';
        $api = $this->getMockedApi([
            new Response(200, ['Content-Type' => 'application/json'], $mockJson)
        ]);
        
        $this->expectException(\Scopus\Exception\JsonException::class);
        $api->retrieveCitationCount(['123']);
    }
}
