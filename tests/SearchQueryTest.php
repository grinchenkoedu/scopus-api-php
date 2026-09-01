<?php

namespace Scopus\Tests;

use Scopus\SearchQuery;
use Scopus\ScopusApi;

class SearchQueryTest extends TestCase
{
    public function testQueryBuilder()
    {
        // We use a dummy API since we just need to pass it to the constructor
        $api = $this->createMock(ScopusApi::class);
        $query = new SearchQuery($api, 'au-id(12345)');
        
        $query->start(10)
              ->count(25)
              ->viewComplete();
              
        $array = $query->toArray();
        
        $this->assertArrayHasKey('query', $array);
        $this->assertEquals('au-id(12345)', $array['query']);
        
        $this->assertArrayHasKey('start', $array);
        $this->assertEquals(10, $array['start']);
        
        $this->assertArrayHasKey('count', $array);
        $this->assertEquals(25, $array['count']);
        
        $this->assertArrayHasKey('view', $array);
        $this->assertEquals('COMPLETE', $array['view']);
    }
}
